@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Penerimaan Barang</h1>
                <p class="text-slate-600 mt-2">Daftar Goods Receipt Notes dan Inspeksi Barang</p>
            </div>
            <a href="{{ route('goods-receipts.ready') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 shadow-lg">
                <i class="fas fa-truck mr-2"></i>PO Siap Terima
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg border border-amber-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-amber-100 mr-4">
                        <i class="fas fa-clock text-amber-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase font-bold">Pending Inspection</p>
                        <p class="text-3xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 mr-4">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase font-bold">Approved</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 mr-4">
                        <i class="fas fa-adjust text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase font-bold">Partial</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['partial'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-red-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 mr-4">
                        <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 uppercase font-bold">Rejected</p>
                        <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipts Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-900">Daftar Penerimaan Barang</h3>
            </div>

            @if ($receipts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full min-w-full">
                        <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold  uppercase">No. GRN</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold  uppercase">No. PO</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold  uppercase">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold  uppercase">Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold  uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold  uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold  uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold  uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($receipts as $receipt)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-slate-900 font-mono">{{ $receipt->nomor_grn }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-slate-600 font-mono">{{ $receipt->purchase->nomor_po }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-slate-900">
                                            {{ optional($receipt->purchase->barang)->nama_barang ?? optional(optional($receipt->purchase->barangMasuk)->barang)->nama_barang ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-slate-600">{{ $receipt->purchase->supplier->nama_supplier }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <div class="font-bold text-green-600">✓ {{ $receipt->jumlah_diterima }}</div>
                                            @if ($receipt->jumlah_rusak > 0)
                                                <div class="text-red-600">✗ {{ $receipt->jumlah_rusak }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($receipt->status == 'pending_inspection')
                                            <span class="inline-block bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-bold">Pending</span>
                                        @elseif ($receipt->status == 'approved')
                                            <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Approved</span>
                                        @elseif ($receipt->status == 'rejected')
                                            <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">Rejected</span>
                                        @else
                                            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">Partial</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-slate-600">{{ $receipt->tanggal_inspection?->format('d M Y') ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('goods-receipts.show', $receipt->id) }}" title="Detail" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors duration-200 inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-6 border-t border-slate-200">
                    {{ $receipts->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-inbox text-slate-300 text-6xl mb-4"></i>
                    <p class="text-slate-500 text-lg">Belum ada data penerimaan barang</p>
                    <a href="{{ route('goods-receipts.ready') }}" class="inline-block mt-4 text-emerald-600 hover:text-emerald-700 font-semibold">
                        Lihat PO yang siap diterima →
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
