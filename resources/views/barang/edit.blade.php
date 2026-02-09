@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-8">
        <div>
            <h1 class="text-2xl font-bold">Edit Barang</h1>
            <p class="text-sm text-slate-400">Perbarui informasi produk</p>
        </div>
        <a href="{{ route('barang') }}" class=" w-fit group flex items-center gap-2 px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-medium text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>


    {{-- Tambahkan enctype="multipart/form-data" --}}
    <form action="{{ route('barang.update', $barang) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white rounded-xl shadow-sm border p-6">
        @csrf
        @method('PUT')

        <div>
    <label class="block text-xs font-semibold text-slate-600 mb-2">Foto Produk</label>
    
    <div class="flex items-start gap-4">
        @if($barang->gambar)
            <div class="shrink-0 relative group">
                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="Current Image" class="h-24 w-24 object-cover rounded-lg border border-slate-200 shadow-sm">
                <p class="text-xs text-center text-slate-400 mt-1">Saat ini</p>
                
                {{-- Checkbox Hapus Gambar --}}
                <div class="mt-2 flex items-center justify-center gap-1.5 p-1.5 bg-red-50 rounded-md border border-red-100">
                    <input type="checkbox" name="hapus_gambar" id="hapus_gambar" value="1" class="w-3.5 h-3.5 text-red-600 border-red-300 rounded focus:ring-red-500">
                    <label for="hapus_gambar" class="text-[10px] font-bold text-red-600 uppercase cursor-pointer">Hapus Foto</label>
                </div>
            </div>
        @endif
        
        <div class="w-full">
            <input type="file" name="gambar" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer border border-slate-300 rounded-lg p-1">
            <p class="text-xs text-slate-400 mt-2">
                @if($barang->gambar)
                    Pilih file baru untuk mengganti, atau centang "Hapus Foto" untuk menghapus.
                @else
                    Unggah foto produk (Format: JPG, PNG. Max: 2MB).
                @endif
            </p>
        </div>
    </div>
</div>

        <div class="border-t border-slate-100 my-4"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="mt-1 block w-full border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300" required>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">SKU</label>
                <input type="text" name="sku" value="{{ old('sku', $barang->sku) }}" class="mt-1 block w-full border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                <select name="kategori_id" class="mt-1 block w-full border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ (old('kategori_id', $barang->kategori_id) == $k->id) ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Satuan</label>
                <input type="text" name="satuan" value="{{ old('satuan', $barang->satuan) }}" class="mt-1 block w-full border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Harga Jual (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga', $barang->harga) }}" min="0" step="0.01" class="mt-1 block w-full border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300" placeholder="0">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Stok Saat Ini</label>
                <input type="number" name="stok_saat_ini" value="{{ old('stok_saat_ini', $barang->stok_saat_ini) }}" min="0" readonly class="mt-1 block w-full border border-slate-200 rounded px-3 py-2 text-sm bg-slate-50 cursor-not-allowed" aria-readonly="true">
                <p class="text-xs text-slate-400 mt-1">Stok dikelola otomatis dari transaksi.</p>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="mt-1 block w-full border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
        </div>

        <div class="flex items-center justify-between space-x-3 pt-4">
            <button type="button" onclick="confirmSave(this.closest('form'), 'barang')" class="px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
            <button type="button" class="px-4 py-2 bg-slate-500 rounded-xl hover:bg-slate-600">
                <a href="{{ route('barang.show', $barang) }}" class="text-sm text-white">Batal</a>
            </button>
            
        </div>
    </form>

    <div class="mt-6">
        <form id="deleteBarangFormEdit{{ $barang->id }}" action="{{ route('barang.destroy', $barang) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" onclick="confirmDelete('deleteBarangFormEdit{{ $barang->id }}', 'barang {{ $barang->nama_barang }}')" class="w-full px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition flex items-center justify-center font-semibold">
                <i class="fas fa-trash mr-2"></i>Hapus Barang Permanen
            </button>
        </form>
    </div>
</div>
@endsection