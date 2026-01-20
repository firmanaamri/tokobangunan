@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white p-4 md:p-8 font-sans">
    <div class="max-w-5xl mx-auto">
        
        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                    <span>Gudang</span>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span>Detail GRN</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Penerimaan Barang</h1>
                <p class="text-slate-500 mt-1 font-mono font-bold text-lg">{{ $goodsReceipt->nomor_grn }}</p>
            </div>
            
            <a href="{{ route('goods-receipts.index') }}" class="w-full md:w-auto flex justify-center items-center gap-2 px-5 py-2.5 bg-white text-slate-600 border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-bold text-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- ================= STATUS CARD ================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8 relative overflow-hidden">
            {{-- Aksen warna di kiri --}}
            <div class="absolute top-0 left-0 w-1.5 h-full 
                @if($goodsReceipt->status == 'approved') bg-emerald-500 
                @elseif($goodsReceipt->status == 'rejected') bg-red-500 
                @else bg-amber-500 @endif">
            </div>

            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Status Saat Ini</h3>
                    @if ($goodsReceipt->status == 'pending_inspection')
                        <span class="inline-flex items-center gap-2 bg-amber-100 text-amber-800 px-4 py-2 rounded-full text-sm font-bold border border-amber-200">
                            <i class="fas fa-clock"></i> Pending Inspection
                        </span>
                    @elseif ($goodsReceipt->status == 'approved')
                        <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 px-4 py-2 rounded-full text-sm font-bold border border-emerald-200">
                            <i class="fas fa-check-circle"></i> Approved / Selesai
                        </span>
                    @elseif ($goodsReceipt->status == 'rejected')
                        <span class="inline-flex items-center gap-2 bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-bold border border-red-200">
                            <i class="fas fa-times-circle"></i> Rejected
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-bold border border-blue-200">
                            <i class="fas fa-adjust"></i> Partial
                        </span>
                    @endif
                </div>
                
                <div class="text-left md:text-right bg-slate-50 p-3 rounded-lg w-full md:w-auto">
                    <p class="text-xs text-slate-500 uppercase font-bold mb-1">Tanggal Inspeksi</p>
                    <p class="text-base font-bold text-slate-900">
                        {{ $goodsReceipt->tanggal_inspection ? $goodsReceipt->tanggal_inspection->format('d M Y H:i') : '-' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ================= INFO GRN & SUPPLIER (Grid Responsif) ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- GRN INFO --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fas fa-file-alt"></i> Informasi Dokumen
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-sm text-slate-500">Nomor GRN</span>
                        <span class="text-sm font-bold text-slate-900 font-mono">{{ $goodsReceipt->nomor_grn }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-sm text-slate-500">Nomor PO</span>
                        <span class="text-sm font-bold text-slate-900 font-mono">{{ $goodsReceipt->purchase->nomor_po }}</span>
                    </div>
                    <div class="flex justify-between pb-2">
                        <span class="text-sm text-slate-500">Inspector</span>
                        <span class="text-sm font-bold text-slate-900">{{ $goodsReceipt->inspector->name ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- SUPPLIER --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fas fa-truck"></i> Supplier
                </h3>
                <div class="space-y-3">
                    <p class="font-bold text-slate-900 text-lg">{{ $goodsReceipt->purchase->supplier->nama_supplier }}</p>
                    <div class="space-y-1 text-sm text-slate-600">
                        <p><i class="fas fa-user w-5 text-slate-400"></i> {{ $goodsReceipt->purchase->supplier->kontak_person }}</p>
                        <p><i class="fas fa-phone w-5 text-slate-400"></i> {{ $goodsReceipt->purchase->supplier->nomor_telepon }}</p>
                        <p><i class="fas fa-envelope w-5 text-slate-400"></i> {{ $goodsReceipt->purchase->supplier->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= DETAIL BARANG ================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
            <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                <i class="fas fa-box text-slate-400"></i> Detail Barang
            </h3>
            @php
                $barang = $goodsReceipt->purchase->barang ?? $goodsReceipt->purchase->barangMasuk?->barang;
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-1">Nama Barang</p>
                    <p class="text-lg font-bold text-slate-900">{{ $barang->nama_barang ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-1">SKU / Kode</p>
                    <p class="text-lg font-bold text-slate-900 font-mono">{{ $barang->sku ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- ================= HASIL INSPEKSI (Grid Diperbaiki) ================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8 overflow-hidden">
            <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                <i class="fas fa-clipboard-check text-slate-400"></i> Hasil Inspeksi
            </h3>
            
            {{-- Grid Stat Cards: 1 kolom di HP, 3 kolom di Desktop --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="text-center p-5 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="text-xs text-slate-600 uppercase font-bold mb-2">Total PO</p>
                    <p class="text-3xl font-black text-blue-600">{{ $goodsReceipt->jumlah_po }}</p>
                </div>
                <div class="text-center p-5 bg-emerald-50 rounded-xl border border-emerald-100">
                    <p class="text-xs text-slate-600 uppercase font-bold mb-2">Diterima Baik</p>
                    <p class="text-3xl font-black text-emerald-600">{{ $goodsReceipt->jumlah_diterima }}</p>
                </div>
                <div class="text-center p-5 bg-rose-50 rounded-xl border border-rose-100">
                    <p class="text-xs text-slate-600 uppercase font-bold mb-2">Rusak / Ditolak</p>
                    <p class="text-3xl font-black text-rose-600">{{ $goodsReceipt->jumlah_rusak }}</p>
                </div>
            </div>

            @if ($goodsReceipt->catatan_inspection)
                <div class="mb-6">
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Catatan Inspeksi</p>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-slate-800 italic">
                        "{{ $goodsReceipt->catatan_inspection }}"
                    </div>
                </div>
            @endif

            @if ($goodsReceipt->foto_kerusakan)
                <div class="border-t border-slate-100 pt-6">
                    <p class="text-xs text-slate-500 uppercase font-bold mb-3">Foto Dokumentasi / Kerusakan</p>
                    @php
                        $fotoPath = $goodsReceipt->foto_kerusakan;
                        $imageSrc = str_starts_with($fotoPath, 'http') ? $fotoPath : asset('storage/' . $fotoPath);
                    @endphp
                    {{-- FIX IMAGE: max-w-full agar tidak melebar keluar layar di HP --}}
                    <div class="bg-slate-100 rounded-xl p-2 inline-block max-w-full">
                        <img src="{{ $imageSrc }}" alt="Foto Kerusakan" class="w-full md:max-w-md rounded-lg shadow-sm border border-slate-200 object-cover" onerror="this.style.display='none'">
                    </div>
                </div>
            @endif
        </div>

        {{-- ================= BARANG MASUK TERKAIT ================= --}}
        @if ($goodsReceipt->barangMasuk)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-10">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-warehouse text-slate-400"></i> Integrasi Stok
                </h3>
                <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-green-700 uppercase font-bold mb-1">ID Barang Masuk</p>
                            <p class="font-bold text-green-900">#{{ $goodsReceipt->barangMasuk->id }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-green-700 uppercase font-bold mb-1">Status Stok</p>
                            <span class="inline-block bg-green-200 text-green-800 px-3 py-1 rounded-full text-xs font-bold">
                                <i class="fas fa-check mr-1"></i> Stok Telah Bertambah
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection