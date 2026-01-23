@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white p-6">
    <div class="max-w-xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Edit Kategori</h1>
            <p class="text-slate-600 mt-1">Perbarui informasi kategori barang</p>
        </div>

        <form action="{{ route('kategori.update', $kategori->id) }}" method="POST" class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
            @csrf
            @method('PUT') 
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="nama_kategori" 
                           value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                           class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition" 
                           placeholder="Contoh: Cat Tembok" 
                           required>
                    
                    @error('nama_kategori')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" 
                              rows="3" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition" 
                              placeholder="Keterangan singkat kategori (opsional)">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                    
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('kategori.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-medium">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection