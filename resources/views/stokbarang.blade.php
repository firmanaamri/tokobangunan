@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Stok Barang</h1>
                <p class="text-slate-600 mt-2">Pantau kategori, harga, riwayat, dan ketersediaan stok</p>
            </div>
            <a href="{{ route('barang.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-300">
                <i class="fas fa-plus mr-2"></i>Tambah Barang
            </a>
        </div>

        <!-- Search -->
        <form method="GET" action="{{ route('stokbarang') }}" class="mb-6">
            <div class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau SKU..." class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-3 rounded-lg font-bold">Cari</button>
            </div>
        </form>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                        <tr>
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
                                    @php $status = $product->status; @endphp
                                    @if ($status == 'Habis')
                                        <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold animate-pulse">Habis</span>
                                    @elseif ($status == 'Stok Menipis')
                                        <span class="inline-block bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-bold animate-bounce">Menipis</span>
                                    @else
                                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Aman</span>
                                    @endif
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
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-4xl text-slate-300 mb-4"></i>
                                    <p class="text-slate-500 font-semibold">Tidak ada data barang</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
