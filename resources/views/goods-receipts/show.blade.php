@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-slate-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Detail Penerimaan Barang</h1>
                <p class="text-slate-600 mt-2">{{ $goodsReceipt->nomor_grn }}</p>
            </div>
            <a href="{{ route('goods-receipts.index') }}" class="bg-slate-500 hover:bg-slate-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-600 uppercase mb-2">Status Penerimaan</h3>
                    @if ($goodsReceipt->status == 'pending_inspection')
                        <span class="inline-block bg-amber-100 text-amber-800 px-6 py-2 rounded-lg text-lg font-bold">
                            <i class="fas fa-clock mr-2"></i>Pending Inspection
                        </span>
                    @elseif ($goodsReceipt->status == 'approved')
                        <span class="inline-block bg-green-100 text-green-800 px-6 py-2 rounded-lg text-lg font-bold">
                            <i class="fas fa-check-circle mr-2"></i>Approved
                        </span>
                    @elseif ($goodsReceipt->status == 'rejected')
                        <span class="inline-block bg-red-100 text-red-800 px-6 py-2 rounded-lg text-lg font-bold">
                            <i class="fas fa-times-circle mr-2"></i>Rejected
                        </span>
                    @else
                        <span class="inline-block bg-blue-100 text-blue-800 px-6 py-2 rounded-lg text-lg font-bold">
                            <i class="fas fa-adjust mr-2"></i>Partial
                        </span>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Tanggal Inspeksi</p>
                    <p class="text-lg font-bold text-slate-900">{{ $goodsReceipt->tanggal_inspection?->format('d M Y H:i') ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- GRN Info -->
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wide mb-4">Info GRN</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold">Nomor GRN</p>
                        <p class="text-lg font-bold text-slate-900 font-mono">{{ $goodsReceipt->nomor_grn }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold">Nomor PO</p>
                        <p class="text-lg font-bold text-slate-900 font-mono">{{ $goodsReceipt->purchase->nomor_po }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold">Inspector</p>
                        <p class="text-lg font-bold text-slate-900">{{ $goodsReceipt->inspector->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wide mb-4">Supplier</h3>
                <div class="space-y-2">
                    <p class="font-bold text-slate-900">{{ $goodsReceipt->purchase->supplier->nama_supplier }}</p>
                    <p class="text-sm text-slate-600">{{ $goodsReceipt->purchase->supplier->kontak_person }}</p>
                    <p class="text-sm text-slate-600">📞 {{ $goodsReceipt->purchase->supplier->nomor_telepon }}</p>
                    <p class="text-sm text-slate-600">📧 {{ $goodsReceipt->purchase->supplier->email }}</p>
                </div>
            </div>
        </div>

        <!-- Barang Info -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-8">
            <h3 class="text-xl font-bold text-slate-900 mb-6">Detail Barang</h3>
            @php
                $barang = $goodsReceipt->purchase->barang ?? $goodsReceipt->purchase->barangMasuk?->barang;
            @endphp
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Nama Barang</p>
                    <p class="text-lg font-bold text-slate-900">{{ $barang->nama_barang ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">SKU</p>
                    <p class="text-lg font-bold text-slate-900 font-mono">{{ $barang->sku ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Inspection Results -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-8">
            <h3 class="text-xl font-bold text-slate-900 mb-6">Hasil Inspeksi</h3>
            <div class="grid grid-cols-3 gap-6 mb-6">
                <div class="text-center p-6 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-slate-600 uppercase font-bold mb-2">Jumlah PO</p>
                    <p class="text-4xl font-bold text-blue-600">{{ $goodsReceipt->jumlah_po }}</p>
                </div>
                <div class="text-center p-6 bg-green-50 rounded-lg border border-green-200">
                    <p class="text-sm text-slate-600 uppercase font-bold mb-2">Diterima</p>
                    <p class="text-4xl font-bold text-green-600">{{ $goodsReceipt->jumlah_diterima }}</p>
                </div>
                <div class="text-center p-6 bg-red-50 rounded-lg border border-red-200">
                    <p class="text-sm text-slate-600 uppercase font-bold mb-2">Rusak/Ditolak</p>
                    <p class="text-4xl font-bold text-red-600">{{ $goodsReceipt->jumlah_rusak }}</p>
                </div>
            </div>

            @if ($goodsReceipt->catatan_inspection)
                <div class="mb-4">
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Catatan Inspeksi</p>
                    <p class="text-slate-900 bg-slate-50 p-4 rounded-lg">{{ $goodsReceipt->catatan_inspection }}</p>
                </div>
            @endif

            @if ($goodsReceipt->foto_kerusakan)
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Foto Kerusakan</p>
                    @php
                        $fotoPath = $goodsReceipt->foto_kerusakan;
                        // Check if it's a full path or just filename
                        $imageSrc = str_starts_with($fotoPath, 'http') ? $fotoPath : asset('storage/' . $fotoPath);
                    @endphp
                    <img src="{{ $imageSrc }}" alt="Foto Kerusakan" class="max-w-md rounded-lg shadow-lg border border-slate-200" onerror="this.style.display='none'">
                </div>
            @endif
        </div>

        <!-- Related Barang Masuk -->
        @if ($goodsReceipt->barangMasuk)
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Barang Masuk Terkait</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-2">ID Barang Masuk</p>
                        <p class="text-lg font-bold text-slate-900">#{{ $goodsReceipt->barangMasuk->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-2">Tanggal Masuk</p>
                        <p class="text-lg font-bold text-slate-900">{{ $goodsReceipt->barangMasuk->tanggal_masuk->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-2">Jumlah</p>
                        <p class="text-lg font-bold text-slate-900">{{ $goodsReceipt->barangMasuk->jumlah_barang_masuk }} {{ $goodsReceipt->purchase->satuan }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-2">Status</p>
                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Stok Bertambah</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
