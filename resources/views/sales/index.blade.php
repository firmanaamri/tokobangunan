@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Penjualan</h1>
            <p class="text-sm text-slate-400">Daftar transaksi penjualan.</p>
        </div>
        <a href="{{ route('sales.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition">
            Buat Penjualan
        </a>
    </div>

    <div class="mb-4 flex items-center justify-between">
        <form method="GET" class="flex items-center space-x-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor..." class="rounded border-gray-200 px-3 py-2">
            <select name="status" class="rounded border-gray-200 px-2 py-2">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status')=='draft' ? 'selected' : '' }}>Draft</option>
                <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('status')=='paid' ? 'selected' : '' }}>Paid</option>
                <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <select name="payment_status" class="rounded border-gray-200 px-2 py-2">
                <option value="">Semua Pembayaran</option>
                <option value="pending" {{ request('payment_status')=='pending' ? 'selected' : '' }}>Pending</option>
                <option value="partial" {{ request('payment_status')=='partial' ? 'selected' : '' }}>Partial</option>
                <option value="paid" {{ request('payment_status')=='paid' ? 'selected' : '' }}>Paid</option>
            </select>
            <button class="px-3 py-2 bg-slate-100 rounded">Filter</button>
        </form>
        <div>
            <a href="{{ route('sales.create') }}" class="text-sm text-slate-600 hover:underline">Buat baru</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Nomor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Pembayaran</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $sale->nomor }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $sale->customer?->name ?? 'Umum' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-slate-900">Rp {{ number_format($sale->total, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded {{ $sale->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">{{ ucfirst($sale->status) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ ucfirst($sale->payment_status) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $sale->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                            <a href="{{ route('sales.show', $sale) }}" class="text-sm text-blue-600 hover:text-blue-900">Lihat</a>
                            @if($sale->payment_status !== 'paid')
                                <a href="{{ route('sales.show', $sale) }}#pembayaran" class="text-sm text-emerald-600 hover:text-emerald-800 ml-2">Bayar</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                            Belum ada transaksi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection
