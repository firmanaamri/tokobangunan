@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF7F2] via-[#F8F4EE] to-[#FAF7F2] p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('stokbarang') }}" class="text-slate-600 hover:text-slate-900 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-4xl font-bold text-slate-900">Tambah Barang Baru</h1>
                    <p class="text-slate-600 mt-2">Masukkan informasi barang ke dalam sistem</p>
                </div>
            </div>
        </div>

        <!-- Alerts handled by SweetAlert -->

        <!-- Form -->
        <form action="{{ route('barang.store') }}" method="POST" class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Info Dasar -->
                <div class="border-b border-slate-200 pb-4">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Informasi Dasar</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Barang -->
                        <div>
                            <label for="nama_barang" class="block text-sm font-semibold text-slate-700 mb-2">
                                Nama Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_barang" id="nama_barang" 
                                   value="{{ old('nama_barang') }}"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
                                   placeholder="Contoh: Semen Gresik 50kg"
                                   required>
                            @error('nama_barang')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SKU -->
                        <div>
                            <label for="sku" class="block text-sm font-semibold text-slate-700 mb-2">
                                SKU (Kode Barang) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="sku" id="sku" 
                                   value="{{ old('sku') }}"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
                                   placeholder="Contoh: SMN-GRS-50"
                                   required>
                            @error('sku')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="kategori_id" class="block text-sm font-semibold text-slate-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="kategori_id" id="kategori_id" 
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
                                    required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Satuan -->
                        <div>
                            <label for="satuan" class="block text-sm font-semibold text-slate-700 mb-2">
                                Satuan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="satuan" id="satuan" 
                                   value="{{ old('satuan') }}"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
                                   placeholder="Contoh: pcs, kg, box, meter"
                                   required>
                            @error('satuan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Harga & Stok -->
                <div class="border-b border-slate-200 pb-4">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Harga & Stok</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Harga -->
                        <div>
                            <label for="harga" class="block text-sm font-semibold text-slate-700 mb-2">
                                Harga Jual (Rp)
                            </label>
                            <input type="number" name="harga" id="harga" 
                                   value="{{ old('harga') }}"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
                                   placeholder="0"
                                   min="0"
                                   step="0.01">
                            @error('harga')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stok Awal -->
                        <div>
                            <label for="stok_saat_ini" class="block text-sm font-semibold text-slate-700 mb-2">
                                Stok Awal <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stok_saat_ini" id="stok_saat_ini" 
                                   value="{{ old('stok_saat_ini', 0) }}"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
                                   placeholder="0"
                                   min="0"
                                   required>
                            <p class="text-xs text-slate-500 mt-1">Masukkan jumlah stok awal saat menambah barang</p>
                            @error('stok_saat_ini')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                              class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
                              placeholder="Keterangan tambahan tentang barang (opsional)">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-slate-50 px-6 py-4 flex justify-between items-center border-t border-slate-200">
                <a href="{{ route('stokbarang') }}" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-100 transition font-semibold">
                    Batal
                </a>
                <button type="button" onclick="confirmCreate(this.closest('form'), 'Barang')" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                    <i class="fas fa-save mr-2"></i>Simpan Barang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
