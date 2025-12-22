@extends('layouts.app')

@section('content')
    <!-- Header Halaman -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Stok Barang</h1>
            <p class="text-gray-600 mt-1">Pantau ketersediaan dan riwayat pergerakan barang.</p>
        </div>
        
        <!-- Form Pencarian -->
        <div class="mt-4 md:mt-0 w-full md:w-auto">
            <form action="{{ route('barang') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                    placeholder="Cari nama / SKU...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Info Barang</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-green-50">Riwayat Masuk</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-red-50">Riwayat Keluar</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-blue-50">Stok Saat Ini</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- Info Barang -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded flex items-center justify-center text-gray-500 font-bold text-xs">
                                    {{ substr($product->nama_barang, 0, 2) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->nama_barang }}</div>
                                    <div class="text-xs text-gray-500">SKU: {{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Kategori -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $product->kategori->nama_kategori ?? '-' }}
                            </span>
                        </td>

                        <!-- Harga -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-slate-900">Rp {{ number_format($product->harga ?? 0, 2, ',', '.') }}</div>
                            <div class="text-xs text-gray-500">/ {{ $product->satuan ?? 'pcs' }}</div>
                        </td>

                        <!-- Riwayat Masuk (Dihitung dari Controller) -->
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600 bg-green-50/50">
                            <div class="flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                                <span class="font-semibold">{{ number_format($product->barang_masuk_sum_jumlah_barang_masuk ?? 0) }}</span>
                            </div>
                        </td>

                        <!-- Riwayat Keluar (Dihitung dari Controller) -->
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600 bg-red-50/50">
                            <div class="flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                                <span class="font-semibold">{{ number_format($product->barang_keluar_sum_jumlah_barang_keluar ?? 0) }}</span>
                            </div>
                        </td>

                        <!-- Stok Saat Ini (Real) -->
                        <td class="px-6 py-4 whitespace-nowrap text-center bg-blue-50/50">
                            <span class="text-lg font-bold text-blue-800">
                                {{ number_format($product->stok_saat_ini) }}
                            </span>
                            <span class="text-xs text-gray-500 block">{{ $product->satuan ?? 'pcs' }}</span>
                        </td>

                        <!-- Status Stok -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php $status = $product->status; @endphp
                            @if($status === 'Habis')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
                                    Habis
                                </span>
                            @elseif($status === 'Stok Menipis')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800 animate-pulse">
                                    Stok Menipis
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Aman
                                </span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('barang.show', $product) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Detail</a>
                            <form action="{{ route('cart.add') }}" method="POST" class="inline-flex items-center">
                                @csrf
                                <input type="hidden" name="barang_id" value="{{ $product->id }}">
                                <input type="hidden" name="unit_price" value="{{ $product->harga ?? 0 }}">
                                <input type="number" name="qty" value="1" min="1" class="w-16 rounded border-gray-200 text-right mr-2">
                                <button class="px-3 py-1 bg-emerald-600 text-white rounded text-sm">Tambah</button>
                            </form>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('barang.edit', $product) }}" class="text-blue-600 hover:text-blue-900 ml-2">Edit</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                <p>Data barang tidak ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $products->links() }}
        </div>
    </div>
@endsection