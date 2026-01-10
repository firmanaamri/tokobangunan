@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Detail Barang Keluar</h2>

    <div class="bg-white/5 p-4 rounded">
        <p><strong>Barang:</strong> {{ $item->barang->nama_barang ?? '—' }}</p>
        <p><strong>Jumlah:</strong> {{ $item->jumlah_barang_keluar }}</p>
        <p><strong>Tanggal:</strong> {{ optional($item->tanggal_keluar)->format('Y-m-d') }}</p>
        <p><strong>Petugas:</strong> {{ $item->user->name ?? '—' }}</p>
        <p><strong>Keterangan:</strong> {{ $item->keterangan }}</p>
    </div>
</div>
@endsection
