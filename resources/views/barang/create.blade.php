@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white p-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4 justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Tambah Barang Baru</h1>
                    <p class="text-slate-600 mt-1">Masukkan informasi barang ke dalam sistem</p>
                </div>
                <a href="{{ route('barang') }}" class="group flex items-center gap-2 px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-medium text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>

        <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
            @csrf
            
            <div class="p-6 space-y-6">
                <div class="border-b border-slate-200 pb-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Informasi Dasar</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Foto Produk</label>
                                    
                                    <div class="flex items-start gap-4">
                                        <div class="w-full">
                                            <input type="file" name="gambar" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer border border-slate-300 rounded-lg p-1">
                                            <p class="text-xs text-slate-400 mt-2">Format: JPG, PNG, GIF. Maks: 2MB.</p>
                                        </div>
                                    </div>
                                    @error('gambar')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="nama_barang" class="block text-sm font-semibold text-slate-700 mb-2">
                                Nama Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_barang" id="nama_barang" 
                                   value="{{ old('nama_barang') }}"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
                                   placeholder="Contoh: Semen Gresik 50kg"
                                   required>
                            @error('nama_barang')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sku" class="block text-sm font-semibold text-slate-700 mb-2">
                                SKU / Kode <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="sku" id="sku" 
                                   value="{{ old('sku') }}"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
                                   placeholder="Contoh: SMN-GRS-50"
                                   required>
                            @error('sku')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kategori_id" class="block text-sm font-semibold text-slate-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="kategori_id" id="kategori_id" 
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
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

                        <div>
                            <label for="satuan" class="block text-sm font-semibold text-slate-700 mb-2">
                                Satuan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="satuan" id="satuan" 
                                   value="{{ old('satuan') }}"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
                                   placeholder="Pcs, Kg, Box, dll"
                                   required>
                            @error('satuan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border-b border-slate-200 pb-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Harga & Stok</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="harga" class="block text-sm font-semibold text-slate-700 mb-2">
                                Harga Jual
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-slate-500 sm:text-sm font-bold">Rp</span>
                                </div>
                                <input type="number" name="harga" id="harga" 
                                       value="{{ old('harga') }}"
                                       class="block w-full rounded-lg border-slate-300 pl-10 px-3 py-2 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm border"
                                       placeholder="0"
                                       min="0"
                                       step="0.01">
                            </div>
                            @error('harga')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="stok_saat_ini" class="block text-sm font-semibold text-slate-700 mb-2">Stok Awal</label>
                            <input type="number" name="stok_saat_ini" id="stok_saat_ini"
                                   value="{{ old('stok_saat_ini', 0) }}"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-100 text-slate-500 cursor-not-allowed"
                                   placeholder="0"
                                   min="0"
                                   readonly aria-readonly="true">
                            <p class="text-xs text-slate-400 mt-1">Stok dikelola lewat menu Barang Masuk.</p>
                            @error('stok_saat_ini')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                              class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
                              placeholder="Keterangan tambahan (opsional)">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-slate-50 px-6 py-4 flex justify-end items-center gap-3 border-t border-slate-200">
                <a href="{{ route('stokbarang') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition font-medium text-sm">
                    Batal
                </a>
                <button type="button" onclick="confirmCreate(this.closest('form'), 'Barang')" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all shadow-sm text-sm flex items-center">
                    <i class="fas fa-save mr-2"></i>Simpan Barang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection