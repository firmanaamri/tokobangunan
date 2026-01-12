@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF7F2] via-[#F8F4EE] to-[#FAF7F2] p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Ajukan Pengajuan Pembelian</h1>
            <p class="text-slate-600 mt-2">Buat permintaan pembelian barang baru</p>
        </div>

        <!-- Alerts handled by SweetAlert -->

        <!-- Form Card -->
        <form method="POST" action="{{ route('purchase-requests.store') }}" class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Barang -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-box mr-2 text-emerald-500"></i>Barang *
                    </label>
                    <select name="barang_id" required class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('barang_id') border-red-500 @enderror">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->nama_barang }} ({{ $barang->kategori->nama_kategori ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Supplier -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-truck mr-2 text-emerald-500"></i>Supplier *
                    </label>
                    <select id="supplier-select" name="supplier_id" required class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('supplier_id') border-red-500 @enderror">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                        <option value="new_supplier">+ Tambah supplier baru</option>
                    </select>
                    <script>
                        (function(){
                            var sel = document.getElementById('supplier-select');
                            if (!sel) return;
                            sel.addEventListener('change', function(e){
                                if (this.value === 'new_supplier') {
                                    window.location.href = '{{ route('suppliers.create') }}';
                                }
                            });
                        })();
                    </script>
                    @error('supplier_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Jumlah Diminta -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-calculator mr-2 text-emerald-500"></i>Jumlah Diminta *
                    </label>
                    <input type="number" name="jumlah_diminta" min="1" required value="{{ old('jumlah_diminta') }}" placeholder="100" class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('jumlah_diminta') border-red-500 @enderror">
                    @error('jumlah_diminta')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Satuan -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-ruler mr-2 text-emerald-500"></i>Satuan *
                    </label>
                    <input type="text" name="satuan" required value="{{ old('satuan') }}" placeholder="pcs, dus, meter, etc" class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('satuan') border-red-500 @enderror">
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
                <textarea name="catatan_request" rows="4" placeholder="Masukkan keterangan atau spesifikasi tambahan..." class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('catatan_request') border-red-500 @enderror">{{ old('catatan_request') }}</textarea>
                @error('catatan_request')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="mt-8 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Informasi:</strong> PR ini akan diajukan dengan status <strong>Pending</strong> menunggu persetujuan dari admin. Anda bisa mengedit atau membatalkan selama masih pending.
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 mt-8">
                <button type="submit" class="flex-1 bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-300">
                    <i class="fas fa-paper-plane mr-2"></i>Ajukan Pengajuan
                </button>
                <a href="{{ route('purchase-requests.index') }}" class="flex-1 bg-slate-300 hover:bg-slate-400 text-slate-900 font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-300 text-center">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
