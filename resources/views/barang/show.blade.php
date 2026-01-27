@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold">{{ $barang->nama_barang }}</h1>
            <p class="text-sm text-slate-500 mt-1">SKU: <span class="font-mono">{{ $barang->sku }}</span></p>
        </div>
        <a href="{{ route('barang') }}" class="group flex items-center gap-2 px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-medium text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border p-6 flex justify-center items-center bg-slate-50">
                @if($barang->gambar)
                    <img src="{{ asset('storage/' . $barang->gambar) }}" 
                         alt="{{ $barang->nama_barang }}" 
                         class="max-h-96 w-auto object-contain rounded-lg shadow-sm">
                @else
                    <div class="h-64 w-full flex flex-col items-center justify-center text-slate-400">
                        <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <p>Tidak ada gambar produk</p>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-semibold mb-4">Informasi Barang</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-32">
                            <label class="text-xs font-semibold text-slate-500 uppercase">Kategori</label>
                        </div>
                        <div class="flex-1">
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $barang->kategori?->nama_kategori ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-32">
                            <label class="text-xs font-semibold text-slate-500 uppercase">Satuan</label>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-700">{{ $barang->satuan ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-32">
                            <label class="text-xs font-semibold text-slate-500 uppercase">Deskripsi</label>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-700 leading-relaxed">
                                {{ $barang->deskripsi ?? 'Tidak ada deskripsi.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                    Riwayat Barang Masuk
                </h2>
                @if($barang->barangMasuk->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600">Tanggal</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-slate-600">Jumlah</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($barang->barangMasuk->sortByDesc('tanggal_masuk') as $m)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-2 text-slate-700">{{ $m->tanggal_masuk->format('d M Y') }}</td>
                                    <td class="px-4 py-2 text-center font-semibold text-green-600">+{{ number_format($m->jumlah_barang_masuk) }}</td>
                                    <td class="px-4 py-2 text-slate-600 max-w-xs truncate">{{ $m->keterangan ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-slate-500">Belum ada catatan barang masuk.</p>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                    Riwayat Barang Keluar
                </h2>
                @if($barang->barangKeluar->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600">Tanggal</th>
                                    <th class="px-4 py-2 text-center text-xs font-semibold text-slate-600">Jumlah</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($barang->barangKeluar->sortByDesc('tanggal_keluar') as $k)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-2 text-slate-700">{{ $k->tanggal_keluar->format('d M Y') }}</td>
                                    <td class="px-4 py-2 text-center font-semibold text-red-600">-{{ number_format($k->jumlah_barang_keluar) }}</td>
                                    <td class="px-4 py-2 text-slate-600 max-w-xs truncate">{{ $k->keterangan ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-slate-500">Belum ada catatan barang keluar.</p>
                @endif
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-6">
                <h3 class="text-sm font-semibold text-slate-600 uppercase mb-4">Ringkasan Stok</h3>

                <div class="mb-6 pb-6 border-b">
                    <p class="text-xs text-slate-500 uppercase mb-1">Stok Saat Ini</p>
                    <p class="text-4xl font-bold text-blue-600">{{ number_format($barang->stok_saat_ini ?? 0) }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $barang->satuan ?? 'pcs' }}</p>
                </div>

                <div class="mb-6 pb-6 border-b">
                    <p class="text-xs text-slate-500 uppercase mb-2">Status</p>
                    @php $status = $barang->status; @endphp
                    @if($status === 'Habis')
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
                            Habis
                        </span>
                    @elseif($status === 'Stok Menipis')
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800 animate-pulse">
                            Stok Menipis
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Aman
                        </span>
                    @endif
                </div>

                <div class="mb-6 pb-6 border-b">
                    <p class="text-xs text-slate-500 uppercase mb-1">Harga</p>
                    <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($barang->harga ?? 0, 2, ',', '.') }}</p>
                </div>

                <div class="mb-6 pb-6 border-b">
                    <p class="text-xs text-slate-500 uppercase mb-1">Total Masuk</p>
                    <p class="text-2xl font-semibold text-green-600">
                        {{ number_format($barang->barangMasuk->sum('jumlah_barang_masuk')) }}
                    </p>
                </div>

                <div class="mb-6 pb-6 border-b">
                    <p class="text-xs text-slate-500 uppercase mb-1">Total Keluar</p>
                    <p class="text-2xl font-semibold text-red-600">
                        {{ number_format($barang->barangKeluar->sum('jumlah_barang_keluar')) }}
                    </p>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('barang.edit', $barang) }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm font-medium">
                        <i class="fas fa-edit mr-2"></i>Edit Barang
                    </a>
                    <a href="{{ route('daily-sales.create') }}" class="block w-full text-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition text-sm font-medium">
                        <i class="fas fa-shopping-cart mr-2"></i>Catat Penjualan
                    </a>
                    <form id="deleteBarangForm{{ $barang->id }}" action="{{ route('barang.destroy', $barang) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete('deleteBarangForm{{ $barang->id }}', 'barang {{ $barang->nama_barang }}')" class="block w-full text-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-sm font-medium">
                            <i class="fas fa-trash mr-2"></i>Hapus Barang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection