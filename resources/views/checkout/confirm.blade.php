@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Konfirmasi Pesanan</h1>
            <p class="text-sm text-slate-400">Periksa pesanan sebelum melakukan pembayaran.</p>
        </div>
        <a href="{{ route('sales.show', $sale) }}" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 rounded-md hover:bg-slate-200 transition">Lihat Transaksi</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="mb-4">
            <p class="text-sm text-slate-500">Nomor: <strong>{{ $sale->nomor }}</strong></p>
            <p class="text-sm text-slate-500">Total: <strong>Rp {{ number_format($sale->total,2,',','.') }}</strong></p>
        </div>

        <h4 class="text-sm font-medium">Items</h4>
        <div class="overflow-x-auto mt-2">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600">Barang</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-slate-600">Qty</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-slate-600">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($sale->items as $item)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $item->barang?->nama_barang ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-right">{{ $item->qty }} {{ $item->satuan ?? '' }}</td>
                            <td class="px-4 py-3 text-sm text-right">Rp {{ number_format($item->total_price,2,',','.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <form action="{{ route('payments.store') }}" method="POST" class="grid grid-cols-1 gap-3 md:grid-cols-2">
                @csrf
                <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                <div>
                    <label class="block text-sm text-slate-600">Jumlah Bayar</label>
                    <input name="amount" type="number" step="0.01" class="mt-1 block w-full rounded border-gray-200" value="{{ $sale->total }}" required>
                </div>
                <div>
                    <label class="block text-sm text-slate-600">Metode</label>
                    <input name="method" type="text" class="mt-1 block w-full rounded border-gray-200" value="cash">
                </div>

                <div class="md:col-span-2">
                    <button class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Bayar & Selesaikan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
