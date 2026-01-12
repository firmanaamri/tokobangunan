@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF7F2] via-[#F8F4EE] to-[#FAF7F2] p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Penerimaan Barang</h1>
            <p class="text-slate-600 mt-2">Form Inspeksi dan Penerimaan Barang untuk PO {{ $purchase->nomor_po }}</p>
        </div>

        <!-- Alert Messages -->
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @php
            $jumlah = $purchase->jumlah_po ?? $purchase->barangMasuk?->jumlah_barang_masuk;
            $barang = $purchase->barang ?? $purchase->barangMasuk?->barang;
        @endphp

        @if (!$jumlah)
            <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Perhatian:</strong> PO ini belum memiliki jumlah barang yang valid. Silakan hubungi admin untuk memperbarui data PO.
            </div>
            <a href="{{ route('goods-receipts.ready') }}" class="inline-block bg-slate-500 hover:bg-slate-600 text-white font-bold py-3 px-6 rounded-lg">
                ← Kembali ke Daftar
            </a>
        @else
        <!-- PO Info Card -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-8">
            <h3 class="text-xl font-bold text-slate-900 mb-6">Informasi Purchase Order</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Nomor PO</p>
                    <p class="text-lg font-bold text-slate-900 font-mono">{{ $purchase->nomor_po }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Supplier</p>
                    <p class="text-lg font-bold text-slate-900">{{ $purchase->supplier->nama_supplier }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Nama Barang</p>
                    <p class="text-lg font-bold text-slate-900">{{ $barang->nama_barang ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">SKU</p>
                    <p class="text-lg font-bold text-slate-900 font-mono">{{ $barang->sku ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Jumlah PO</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $jumlah }} {{ $purchase->satuan }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Total Harga</p>
                    <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($purchase->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Inspection Form -->
        <form action="{{ route('goods-receipts.store', $purchase->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            @csrf

            <div class="p-8 space-y-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Instruksi:</strong> Periksa barang yang diterima. Masukkan jumlah barang yang diterima dalam kondisi baik dan jumlah yang rusak/tidak sesuai. Total keduanya harus sama dengan jumlah PO.
                    </p>
                </div>

                <!-- Quantities -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Quantity Received (Diterima Fisik)</label>
                        <input type="number" name="quantity_received" value="{{ old('quantity_received', $jumlah) }}" min="0" max="{{ $jumlah }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('quantity_received') border-red-500 @enderror">
                        @error('quantity_received')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        <p class="text-slate-500 text-sm mt-1">Total fisik yang diterima dari pengiriman</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Quantity Accepted (Kondisi Baik) <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity_accepted" value="{{ old('quantity_accepted', $jumlah) }}" min="0" max="{{ $jumlah }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('quantity_accepted') border-red-500 @enderror">
                        @error('quantity_accepted')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        <p class="text-slate-500 text-sm mt-1">Jumlah yang akan dimasukkan ke stok</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Quantity Rejected (Rusak/Ditolak) <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity_rejected" value="{{ old('quantity_rejected', 0) }}" min="0" max="{{ $jumlah }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('quantity_rejected') border-red-500 @enderror">
                        @error('quantity_rejected')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        <p class="text-slate-500 text-sm mt-1">Barang yang rusak, tidak sesuai spesifikasi, atau ditolak</p>
                    </div>
                </div>

                <!-- Catatan Inspeksi -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Catatan Inspeksi</label>
                    <textarea name="catatan_inspection" rows="4" placeholder="Kondisi barang, catatan khusus, atau alasan penolakan jika ada..." class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('catatan_inspection') border-red-500 @enderror">{{ old('catatan_inspection') }}</textarea>
                    @error('catatan_inspection')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Kerusakan -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Foto Kerusakan (Jika Ada)</label>
                    <input type="file" name="foto_kerusakan" accept="image/*" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('foto_kerusakan') border-red-500 @enderror">
                    @error('foto_kerusakan')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    <p class="text-slate-500 text-sm mt-1">Upload foto jika ada barang rusak atau tidak sesuai. Maksimal 2MB.</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="bg-slate-100 px-8 py-4 flex gap-4">
                <button type="submit" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300">
                    <i class="fas fa-check mr-2"></i>Simpan Penerimaan
                </button>
                <a href="{{ route('goods-receipts.ready') }}" class="bg-slate-500 hover:bg-slate-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

    <script>
// Auto-calculate and validate
document.addEventListener('DOMContentLoaded', function() {
    const jumlahPO = {{ $jumlah }};
    const qtyReceived = document.querySelector('input[name="quantity_received"]');
    const qtyAccepted = document.querySelector('input[name="quantity_accepted"]');
    const qtyRejected = document.querySelector('input[name="quantity_rejected"]');

    function validateTotal() {
        const received = parseInt(qtyReceived.value) || 0;
        const accepted = parseInt(qtyAccepted.value) || 0;
        const rejected = parseInt(qtyRejected.value) || 0;
        const total = accepted + rejected;

        // We require accepted + rejected == PO jumlah
        if (total !== jumlahPO) {
            qtyAccepted.setCustomValidity(`Total harus ${jumlahPO}`);
            qtyRejected.setCustomValidity(`Total harus ${jumlahPO}`);
        } else {
            qtyAccepted.setCustomValidity('');
            qtyRejected.setCustomValidity('');
        }
    }

    qtyAccepted.addEventListener('input', validateTotal);
    qtyRejected.addEventListener('input', validateTotal);
});
</script>
@endif
@endsection
