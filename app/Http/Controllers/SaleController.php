<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $query = Sale::with('customer')->latest();

        if ($search = request('search')) {
            $query->where('nomor', 'like', "%{$search}%");
        }

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        if ($payment = request('payment_status')) {
            $query->where('payment_status', $payment);
        }

        $sales = $query->paginate(20)->withQueryString();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        return view('sales.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor' => 'nullable|string|unique:sales,nomor',
            'customer_id' => 'nullable|exists:customers,id',
            'notes' => 'nullable|string',
        ]);

        $data['nomor'] = $data['nomor'] ?? 'S'.now()->format('YmdHis').rand(10,99);

        $sale = Sale::create(array_merge($data, [
            'subtotal' => 0,
            'tax' => 0,
            'discount' => 0,
            'total' => 0,
            'status' => 'draft',
            'payment_status' => 'pending',
        ]));

        return redirect()->route('sales.show', $sale)->with('success', 'Sale created.');
    }

    public function show(Sale $sale)
    {
        $sale->load('items.barang', 'payments', 'customer');
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        return view('sales.edit', compact('sale'));
    }

    public function update(Request $request, Sale $sale)
    {
        $data = $request->validate([
            'status' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $sale->update($data);

        return redirect()->route('sales.show', $sale)->with('success', 'Sale updated.');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted.');
    }
}
