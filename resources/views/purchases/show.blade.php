@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-slate-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Detail Transaksi Pembelian</h1>
                <p class="text-slate-600 mt-2">{{ $purchase->nomor_po }}</p>
            </div>
            <div class="flex gap-3">
                @if ($purchase->status_pembayaran != 'lunas')
                    <a href="{{ route('purchases.recordPayment', $purchase->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                        <i class="fas fa-money-bill mr-2"></i>Catat Pembayaran
                    </a>
                @endif
                {{-- <a href="{{ route('purchases.edit', $purchase->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a> --}}
                <a href="{{ route('purchases.index') }}" class="bg-slate-500 hover:bg-slate-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Main Content -->
        <div class="grid grid-cols-3 gap-6 mb-8">
            <!-- Card Info Dasar -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wide mb-4">Informasi Dasar</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold">Nomor PO</p>
                        <p class="text-lg font-bold text-slate-900">{{ $purchase->nomor_po }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold">Tanggal Pembelian</p>
                        <p class="text-lg font-bold text-slate-900">{{ $purchase->tanggal_pembelian->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold">Jatuh Tempo</p>
                        <p class="text-lg font-bold text-slate-900">{{ $purchase->tanggal_jatuh_tempo ? $purchase->tanggal_jatuh_tempo->format('d M Y') : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Card Supplier -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wide mb-4">Supplier</h3>
                <div class="space-y-2">
                    <p class="font-bold text-slate-900">{{ $purchase->supplier->nama_supplier }}</p>
                    <p class="text-sm text-slate-600">{{ $purchase->supplier->kontak_person }}</p>
                    <p class="text-sm text-slate-600">📞 {{ $purchase->supplier->nomor_telepon }}</p>
                    <p class="text-sm text-slate-600">📧 {{ $purchase->supplier->email }}</p>
                    <p class="text-sm text-slate-600">📍 {{ $purchase->supplier->alamat }}, {{ $purchase->supplier->kota }}</p>
                </div>
            </div>

            <!-- Card Total -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wide mb-4">Total Pembelian</h3>
                <p class="text-4xl font-bold text-emerald-600">Rp {{ number_format($purchase->total_harga, 0, ',', '.') }}</p>
                <div class="mt-4">
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Status Pembayaran</p>
                    <div class="flex gap-2">
                        @if ($purchase->status_pembayaran == 'belum_bayar')
                            <span class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-lg text-sm font-bold">Belum Bayar</span>
                        @elseif ($purchase->status_pembayaran == 'sebagian')
                            <span class="inline-block bg-amber-100 text-amber-800 px-4 py-2 rounded-lg text-sm font-bold">Sebagian</span>
                        @else
                            <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg text-sm font-bold">Lunas</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang Detail -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-8">
            <h3 class="text-xl font-bold text-slate-900 mb-6">Detail Barang</h3>
            @php
                $barangMasuk = $purchase->barangMasuk;
                $barang = $barangMasuk?->barang ?? $purchase->barang ?? null;
                $jumlah = $barangMasuk?->jumlah_barang_masuk ?? $purchase->jumlah_po;
                $satuan = $barangMasuk?->barang?->satuan ?? $purchase->satuan ?? '-';
                $hargaBarang = $barang?->harga ?? $purchase->harga_unit ?? 0;
            @endphp
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Nama Barang</p>
                    <p class="text-lg font-bold text-slate-900">{{ $barang->nama_barang ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">SKU</p>
                    <p class="text-lg font-bold text-slate-900 font-mono">{{ $barang->sku ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Jumlah</p>
                    <p class="text-lg font-bold text-slate-900">{{ $jumlah ?? '-' }} {{ $satuan }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Harga Barang</p>
                    <p class="text-lg font-bold text-slate-900">Rp {{ number_format($hargaBarang, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Admin Info -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-8">
            <h3 class="text-xl font-bold text-slate-900 mb-6">Informasi Administratif</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Admin Penerbit</p>
                    <p class="text-lg font-bold text-slate-900">{{ $purchase->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Dibuat Pada</p>
                    <p class="text-lg font-bold text-slate-900">{{ $purchase->created_at->format('d M Y H:i') }}</p>
                </div>
                @if ($purchase->keterangan)
                    <div class="col-span-2">
                        <p class="text-xs text-slate-500 uppercase font-bold mb-2">Keterangan</p>
                        <p class="text-slate-900">{{ $purchase->keterangan }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if($purchase->payment)
            @php
                $payment = $purchase->payment;
                $bukti = $payment->bukti_pembayaran ?? null;
                $buktiUrl = $bukti ? asset('storage/' . $bukti) : null;
                $ext = $bukti ? strtolower(pathinfo($bukti, PATHINFO_EXTENSION)) : null;
                $isImage = in_array($ext, ['jpg','jpeg','png','gif']);
            @endphp

            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-8">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Pembayaran</h3>
                <div class="grid grid-cols-2 gap-6 items-start">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-2">Jumlah Dibayar</p>
                        <p class="text-lg font-bold text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>

                        <p class="text-xs text-slate-500 uppercase font-bold mt-4 mb-2">Metode</p>
                        <p class="text-lg font-bold text-slate-900">{{ $payment->method }}</p>

                        <p class="text-xs text-slate-500 uppercase font-bold mt-4 mb-2">Tanggal Pembayaran</p>
                        <p class="text-lg font-bold text-slate-900">{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-2">Bukti Pembayaran</p>
                        @if($buktiUrl)
                            @if($isImage)
                                <a href="{{ $buktiUrl }}" target="_blank" class="inline-block">
                                    <img src="{{ $buktiUrl }}" alt="Bukti Pembayaran" class="max-w-full h-40 object-contain rounded-md border border-slate-200">
                                </a>
                            @else
                                <a href="{{ $buktiUrl }}" target="_blank" class="inline-block text-indigo-600 hover:underline">Lihat/Unduh bukti pembayar(an)</a>
                            @endif
                        @else
                            <p class="text-slate-500">Tidak ada bukti pembayaran terlampir.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Yakin ingin menghapus transaksi ini?')" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>Hapus Transaksi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
