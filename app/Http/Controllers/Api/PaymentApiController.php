<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Http\Request;

class PaymentApiController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'nullable|string',
            'reference' => 'nullable|string',
            'status' => 'nullable|in:pending,paid,failed,refunded',
        ]);

        $payment = Payment::create([
            'sale_id' => $data['sale_id'],
            'amount' => $data['amount'],
            'method' => $data['method'] ?? 'api',
            'status' => $data['status'] ?? 'paid',
            'reference' => $data['reference'] ?? null,
            'paid_at' => isset($data['status']) && $data['status'] === 'paid' ? now() : null,
        ]);

        $sale = Sale::find($data['sale_id']);
        $paid = $sale->paidAmount();
        if ($paid >= $sale->total) {
            $sale->update(['payment_status' => 'paid', 'status' => 'paid']);
        } else {
            $sale->update(['payment_status' => 'partial']);
        }

        return response()->json(['success' => true, 'payment' => $payment], 201);
    }
}
