@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF7F2] via-[#F8F4EE] to-[#FAF7F2] p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">PO Siap Terima</h1>
                <p class=" mt-2">Daftar Purchase Order yang sudah lunas dan siap diterima barangnya</p>
            </div>
            <a href="{{ route('goods-receipts.index') }}" class="group flex items-center gap-2 px-4 py-2 bg-white  border border-slate-300 rounded-lg hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-medium text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
        </div>

        <!-- Purchases Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-900">Purchase Orders</h3>
            </div>

            @if ($purchases->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full min-w-full">
                        <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold  uppercase">No. PO</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold  uppercase">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold  uppercase">Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold  uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold  uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold  uppercase">Tanggal PO</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold  uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($purchases as $purchase)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-slate-900 font-mono">{{ $purchase->nomor_po }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            @php
                                                $barang = $purchase->barang ?? $purchase->barangMasuk?->barang;
                                            @endphp
                                            <div class="font-semibold text-slate-900">{{ $barang->nama_barang ?? '-' }}</div>
                                            <div class="text-slate-500">{{ $barang->sku ?? '-' }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm ">{{ $purchase->supplier->nama_supplier }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $jumlah = $purchase->jumlah_po ?? null;
                                            $satuan = $purchase->satuan ?? 'pcs';
                                            
                                            // Jika jumlah_po kosong, coba dari barangMasuk
                                            if (!$jumlah && $purchase->barangMasuk) {
                                                $jumlah = $purchase->barangMasuk->jumlah_barang_masuk;
                                            }
                                        @endphp
                                        <span class="text-sm font-semibold text-slate-900">
                                            @if($jumlah)
                                                {{ number_format($jumlah) }} {{ $satuan }}
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-emerald-600">Rp {{ number_format($purchase->total_harga, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm ">{{ $purchase->tanggal_pembelian->format('d M Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('goods-receipts.receive', $purchase->id) }}" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 text-sm">
                                            <i class="fas fa-box mr-1"></i>Terima Barang
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-6 border-t border-slate-200">
                    {{ $purchases->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-check-circle text-slate-300 text-6xl mb-4"></i>
                    <p class="text-slate-500 text-lg">Tidak ada PO yang siap diterima saat ini</p>
                    <p class="text-slate-400 text-sm mt-2">PO harus lunas dulu sebelum bisa diterima barangnya</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
