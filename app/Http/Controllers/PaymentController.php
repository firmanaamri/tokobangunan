<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'nullable|string',
            'reference' => 'nullable|string',
        ]);

        $sale = Sale::findOrFail($data['sale_id']);

        $payment = Payment::create([
            'sale_id' => $sale->id,
            'amount' => $data['amount'],
            'method' => $data['method'] ?? 'cash',
            'status' => 'paid',
            'reference' => $data['reference'] ?? null,
            'paid_at' => now(),
        ]);

        // update sale payment status
        $paid = $sale->paidAmount();
        if ($paid >= $sale->total) {
            $sale->update(['payment_status' => 'paid', 'status' => 'paid']);
        } else {
            $sale->update(['payment_status' => 'partial']);
        }

        return redirect()->route('sales.show', $sale)->with('success', 'Pembayaran dicatat.');
    }
}
