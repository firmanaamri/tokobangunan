<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function payment(Request $request)
    {
        $data = $request->validate([
            'reference' => 'required|string',
            'status' => 'required|in:pending,paid,failed,refunded',
            'amount' => 'nullable|numeric',
        ]);

        $payment = Payment::where('reference', $data['reference'])->first();
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        $payment->update([
            'status' => $data['status'],
            'paid_at' => $data['status'] === 'paid' ? now() : $payment->paid_at,
        ]);

        $sale = $payment->sale;
        $paid = $sale->paidAmount();
        if ($paid >= $sale->total) {
            $sale->update(['payment_status' => 'paid', 'status' => 'paid']);
        }

        return response()->json(['success' => true]);
    }
}
