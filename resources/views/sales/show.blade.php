@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Detail Penjualan</h1>
            <p class="text-sm text-slate-400">Transaksi: <strong>{{ $sale->nomor }}</strong></p>
        </div>
        <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 rounded-md hover:bg-slate-200 transition">Kembali</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-slate-500">Pelanggan</p>
                <p class="text-lg font-semibold">{{ $sale->customer?->name ?? 'Umum' }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-slate-500">Total</p>
                <p class="text-lg font-semibold">Rp {{ number_format($sale->total, 2, ',', '.') }}</p>
            </div>
        </div>

        <h3 class="text-sm font-medium text-slate-700 mb-2">Items</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600">Barang</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-slate-600">Qty</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-slate-600">Harga</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-slate-600">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($sale->items as $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-sm">{{ $item->barang?->nama_barang ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-right">{{ $item->qty }} {{ $item->satuan ?? '' }}</td>
                        <td class="px-4 py-3 text-sm text-right">Rp {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm text-right">Rp {{ number_format($item->total_price, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-4">
            <div>
                <h4 id="pembayaran" class="text-sm font-medium">Pembayaran</h4>
                <ul class="mt-2">
                    @foreach($sale->payments as $p)
                        <li class="text-sm text-slate-700">{{ ucfirst($p->status) }} — Rp {{ number_format($p->amount, 2, ',', '.') }} <span class="text-xs text-slate-400">({{ $p->method }})</span></li>
                    @endforeach
                </ul>

                <form action="{{ route('payments.store') }}" method="POST" class="mt-4 space-y-2">
                    @csrf
                    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                    <div>
                        <label class="block text-sm text-slate-600">Jumlah</label>
                        <input name="amount" type="number" step="0.01" class="mt-1 block w-full rounded border-gray-200" required>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-600">Metode</label>
                        <input name="method" type="text" class="mt-1 block w-full rounded border-gray-200" value="cash">
                    </div>
                    <div>
                        <button class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Catat Pembayaran</button>
                    </div>
                </form>
            </div>

            <div>
                <h4 class="text-sm font-medium">Catatan</h4>
                <p class="text-sm text-slate-600 mt-2">{{ $sale->notes ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
