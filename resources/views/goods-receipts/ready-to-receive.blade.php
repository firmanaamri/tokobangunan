@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF7F2] via-[#F8F4EE] to-[#FAF7F2] p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">PO Siap Terima</h1>
                <p class="text-slate-600 mt-2">Daftar Purchase Order yang sudah lunas dan siap diterima barangnya</p>
            </div>
            <a href="{{ route('goods-receipts.index') }}" class="bg-slate-500 hover:bg-slate-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
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
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">No. PO</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Tanggal PO</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Aksi</th>
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
                                        <span class="text-sm text-slate-600">{{ $purchase->supplier->nama_supplier }}</span>
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
                                        <span class="text-sm text-slate-600">{{ $purchase->tanggal_pembelian->format('d M Y') }}</span>
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
