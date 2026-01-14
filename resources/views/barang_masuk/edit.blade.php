@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Edit Barang Masuk</h1>

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('barang-masuk.update', $item) }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Barang</label>
            <select name="barang_id" class="mt-1 block w-full border-gray-300 rounded">
                @foreach($barangs as $b)
                    <option value="{{ $b->id }}" {{ $item->barang_id == $b->id ? 'selected' : '' }}>{{ $b->nama_barang }} (SKU: {{ $b->sku }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
            <input type="number" name="jumlah_barang_masuk" value="{{ $item->jumlah_barang_masuk }}" class="mt-1 block w-full border-gray-300 rounded" min="1" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Goods Receipt (GRN)</label>
            <select name="goods_receipt_id" class="mt-1 block w-full border-gray-300 rounded">
                <option value="">-- Pilih Goods Receipt --</option>
                @foreach($goodsReceipts as $gr)
                    <option value="{{ $gr->id }}" {{ $item->goods_receipt_id == $gr->id ? 'selected' : '' }}>{{ $gr->nomor_grn }} @if($gr->tanggal_inspection) ({{ \Carbon\Carbon::parse($gr->tanggal_inspection)->format('d M Y') }}) @endif</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
            <input type="date" name="tanggal_masuk" value="{{ $item->tanggal_masuk->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
            <textarea name="keterangan" class="mt-1 block w-full border-gray-300 rounded">{{ $item->keterangan }}</textarea>
        </div>

        <div class="flex items-center space-x-3">
            <button type="button" onclick="confirmSave(this.closest('form'), 'Barang Masuk')" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            <a href="{{ route('barang-masuk.index') }}" class="text-gray-600">Batal</a>
        </div>
    </form>
</div>

@endsection
