@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Edit Barang</h1>
            <p class="text-sm text-slate-400">Perbarui informasi produk</p>
        </div>
        <a href="{{ route('barang') }}" class="text-sm text-slate-500 hover:text-slate-700">Kembali</a>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('barang.update', $barang) }}" method="POST" class="space-y-6 bg-white rounded-xl shadow-sm border p-6">
        @csrf
        @method('PUT')

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
                <input type="number" name="stok_saat_ini" value="{{ old('stok_saat_ini', $barang->stok_saat_ini) }}" min="0" class="mt-1 block w-full border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="mt-1 block w-full border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
        </div>

        <div class="flex items-center space-x-3">
            <button type="button" onclick="confirmSave(this.closest('form'), 'barang')" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
            <a href="{{ route('barang') }}" class="text-sm text-slate-600 hover:text-slate-900">Batal</a>
        </div>
    </form>

    <!-- Delete Form (Separate) -->
    <div class="mt-6">
        <form id="deleteBarangFormEdit{{ $barang->id }}" action="{{ route('barang.destroy', $barang) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" onclick="confirmDelete('deleteBarangFormEdit{{ $barang->id }}', 'barang {{ $barang->nama_barang }}')" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center justify-center font-semibold">
                <i class="fas fa-trash mr-2"></i>Hapus Barang Permanen
            </button>
        </form>
    </div>
</div>

@endsection
