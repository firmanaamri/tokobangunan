@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white p-6">
   <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-4xl font-bold text-slate-900">Stok Barang</h1>
        <p class="text-slate-600 mt-2">Pantau kategori, harga, riwayat, dan ketersediaan stok</p>
    </div>
    
    {{-- Gunakan Grid Cols 2 agar lebar pasti sama --}}
        <div class="grid grid-cols-2 gap-3 mt-4">
            <a href="{{ route('kategori.index') }}" class="flex items-center justify-center px-4 py-3 bg-[#FFD93D] text-white rounded-lg font-bold text-sm hover:bg-[#FFC107] transition shadow-sm">  
                Kelola Kategori
            </a>

            <a href="{{ route('barang.create') }}" class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg font-bold text-sm hover:bg-blue-700 transition shadow-md">  
                Tambah Barang
            </a>
        </div>
</div>

            <form method="GET" action="{{ route('stokbarang') }}" class="mb-6">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <input 
                    type="search" 
                    name="search" 
                    value="{{ request('search') }}" 
                    class="block w-full p-3 pl-10 text-sm text-slate-900 border border-slate-200 rounded-full bg-slate-50 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition shadow-sm" 
                    
                    {{-- UPDATE TEKS PLACEHOLDER DI SINI --}}
                    placeholder="Cari nama, SKU, atau satuan..." 
                    
                    autocomplete="off"
                >
            </div>
        </form>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                        <tr>
                            <th class="px-6 py-4 text-center text-sm font-bold w-20">Gambar</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Nama Barang</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Kategori</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Harga</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">Riwayat Masuk</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">Riwayat Keluar</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">Stok Saat Ini</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($products as $product)
                            <tr class="hover:bg-slate-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-center">
                                    @if($product->gambar)
                                        <img src="{{ asset('storage/' . $product->gambar) }}" alt="Img" class="h-12 w-12 object-cover rounded-lg border border-slate-200 mx-auto">
                                    @else
                                        <div class="h-12 w-12 bg-slate-100 rounded-lg flex items-center justify-center mx-auto text-slate-400">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-900">{{ $product->nama_barang }}</p>
                                    <p class="text-xs text-slate-500 mt-1">SKU: {{ $product->sku }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-slate-900">{{ $product->kategori->nama_kategori ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="font-bold text-slate-900">Rp {{ number_format($product->harga ?? 0, 2, ',', '.') }}</p>
                                    <p class="text-xs text-slate-500 mt-1">/ {{ $product->satuan ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-center bg-green-50">
                                    <div class="inline-flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                                        <span class="font-bold text-green-600">{{number_format($product->barang_masuk_sum_jumlah_barang_masuk ?? 0) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center bg-red-50">
                                    <div class="inline-flex items-center gap-2">
                                        <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                                        <span class="font-bold text-red-600">{{ $product->barang_keluar_sum_jumlah_barang_keluar ?? 0 }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center bg-sky-50">
                                    <div class="inline-flex items-center gap-3 justify-center">
                                        <span class="font-semibold text-sky-700 text-lg">{{ number_format($product->stok_saat_ini ?? 0) }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1 text-center">{{ $product->satuan ?? '' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $product->status_color }}">
                                        {{ $product->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <a href="{{ route('barang.show', $product->id) }}" title="Detail" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors duration-200 inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </a>

                                        <a href="{{ route('barang.edit', $product->id) }}" title="Edit" class="bg-amber-500 hover:bg-amber-600 text-white p-2 rounded-lg transition-colors duration-200 inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-4xl text-slate-300 mb-4"></i>
                                    <p class="text-slate-500 font-semibold">Tidak ada data barang</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection