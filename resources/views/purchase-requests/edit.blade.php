@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF7F2] via-[#F8F4EE] to-[#FAF7F2] p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Edit Pengajuan Pembelian</h1>
            <p class="text-slate-600 mt-2 font-mono">{{ $purchaseRequest->nomor_pr }}</p>
        </div>

        <!-- Alerts -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <strong>Terjadi kesalahan:</strong>
                <ul class="list-disc ml-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Status Warning -->
        @if($purchaseRequest->status !== 'pending')
            <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Perhatian:</strong> PR ini sudah {{ ucfirst($purchaseRequest->status) }} dan tidak dapat diubah.
            </div>
        @endif

        <!-- Form Card -->
        <form method="POST" action="{{ route('purchase-requests.update', $purchaseRequest) }}" class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Barang -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-box mr-2 text-emerald-500"></i>Barang *
                    </label>
                    <select name="barang_id" required {{ $purchaseRequest->status !== 'pending' ? 'disabled' : '' }} class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('barang_id') border-red-500 @enderror {{ $purchaseRequest->status !== 'pending' ? 'bg-slate-100' : '' }}">
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ $purchaseRequest->barang_id == $barang->id ? 'selected' : '' }}>
                                {{ $barang->nama_barang }} ({{ $barang->kategori->nama_kategori ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <div class="bg-slate-50 p-3 rounded-lg mt-2 text-sm text-slate-600">
                        <p><strong>Barang saat ini:</strong> {{ $purchaseRequest->barang->nama_barang }}</p>
                    </div>
                </div>

                <!-- Supplier -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-truck mr-2 text-emerald-500"></i>Supplier *
                    </label>
                    <select name="supplier_id" required {{ $purchaseRequest->status !== 'pending' ? 'disabled' : '' }} class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('supplier_id') border-red-500 @enderror {{ $purchaseRequest->status !== 'pending' ? 'bg-slate-100' : '' }}">
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $purchaseRequest->supplier_id == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <div class="bg-slate-50 p-3 rounded-lg mt-2 text-sm text-slate-600">
                        <p><strong>Supplier saat ini:</strong> {{ $purchaseRequest->supplier->nama_supplier }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Jumlah Diminta -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-calculator mr-2 text-emerald-500"></i>Jumlah Diminta *
                    </label>
                    <input type="number" name="jumlah_diminta" min="1" required value="{{ old('jumlah_diminta', $purchaseRequest->jumlah_diminta) }}" {{ $purchaseRequest->status !== 'pending' ? 'disabled' : '' }} class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('jumlah_diminta') border-red-500 @enderror {{ $purchaseRequest->status !== 'pending' ? 'bg-slate-100' : '' }}">
                    @error('jumlah_diminta')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Satuan -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-ruler mr-2 text-emerald-500"></i>Satuan *
                    </label>
                    <input type="text" name="satuan" required value="{{ old('satuan', $purchaseRequest->satuan) }}" {{ $purchaseRequest->status !== 'pending' ? 'disabled' : '' }} class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('satuan') border-red-500 @enderror {{ $purchaseRequest->status !== 'pending' ? 'bg-slate-100' : '' }}">
                    @error('satuan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Catatan Request -->
            <div class="mt-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    <i class="fas fa-sticky-note mr-2 text-emerald-500"></i>Catatan / Keterangan (Opsional)
                </label>
                <textarea name="catatan_request" rows="4" {{ $purchaseRequest->status !== 'pending' ? 'disabled' : '' }} placeholder="Masukkan keterangan atau spesifikasi tambahan..." class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('catatan_request') border-red-500 @enderror {{ $purchaseRequest->status !== 'pending' ? 'bg-slate-100' : '' }}">{{ old('catatan_request', $purchaseRequest->catatan_request) }}</textarea>
                @error('catatan_request')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="mt-8 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Informasi:</strong> Anda hanya dapat mengedit PR yang masih berstatus <strong>Pending</strong>.
            </div>

            <!-- Action Buttons -->
            @if($purchaseRequest->status === 'pending')
                <div class="flex gap-4 mt-8">
                <button type="button" onclick="confirmSave(this.closest('form'), 'purchase request')" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('purchase-requests.show', $purchaseRequest) }}" class="flex-1 bg-slate-300 hover:bg-slate-400 text-slate-900 font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-300 text-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                </div>
            @else
                <div class="flex gap-4 mt-8">
                    <a href="{{ route('purchase-requests.show', $purchaseRequest) }}" class="flex-1 bg-slate-500 hover:bg-slate-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-300 text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Detail
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
