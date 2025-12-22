@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold">{{ $barang->nama_barang }}</h1>
            <p class="text-sm text-slate-500 mt-1">SKU: <span class="font-mono">{{ $barang->sku }}</span></p>
        </div>
        <a href="{{ route('barang') }}" class="text-sm text-slate-500 hover:text-slate-700 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali ke Stok
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Utama -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-semibold mb-4">Informasi Barang</h2>
                <div class="space-y-4">
                    <!-- Kategori -->
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

                    <!-- Satuan -->
                    <div class="flex items-start">
                        <div class="w-32">
                            <label class="text-xs font-semibold text-slate-500 uppercase">Satuan</label>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-700">{{ $barang->satuan ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Deskripsi -->
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

            <!-- Riwayat Masuk -->
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

            <!-- Riwayat Keluar -->
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

        <!-- Right Column: Summary -->
        <div class="lg:col-span-1">
            <!-- Stock Card -->
            <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-6">
                <h3 class="text-sm font-semibold text-slate-600 uppercase mb-4">Ringkasan Stok</h3>

                <!-- Current Stock -->
                <div class="mb-6 pb-6 border-b">
                    <p class="text-xs text-slate-500 uppercase mb-1">Stok Saat Ini</p>
                    <p class="text-4xl font-bold text-blue-600">{{ number_format($barang->stok_saat_ini ?? 0) }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $barang->satuan ?? 'pcs' }}</p>
                </div>

                <!-- Status -->
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

                <!-- Harga & Tambah ke Keranjang -->
                <div class="mb-6 pb-6 border-b">
                    <p class="text-xs text-slate-500 uppercase mb-1">Harga</p>
                    <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($barang->harga ?? 0, 2, ',', '.') }}</p>
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-4 grid grid-cols-1 gap-2">
                        @csrf
                        <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                        <input type="hidden" name="unit_price" value="{{ $barang->harga ?? 0 }}">
                        <div class="flex items-center space-x-2">
                            <input type="number" name="qty" min="1" value="1" class="w-20 rounded border-gray-200 text-right">
                            <button class="px-4 py-2 bg-emerald-600 text-white rounded-md">Tambah ke Keranjang</button>
                        </div>
                    </form>
                </div>

                <!-- Total Masuk -->
                <div class="mb-6 pb-6 border-b">
                    <p class="text-xs text-slate-500 uppercase mb-1">Total Masuk</p>
                    <p class="text-2xl font-semibold text-green-600">
                        {{ number_format($barang->barangMasuk->sum('jumlah_barang_masuk')) }}
                    </p>
                </div>

                <!-- Total Keluar -->
                <div class="mb-6 pb-6 border-b">
                    <p class="text-xs text-slate-500 uppercase mb-1">Total Keluar</p>
                    <p class="text-2xl font-semibold text-red-600">
                        {{ number_format($barang->barangKeluar->sum('jumlah_barang_keluar')) }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2">
                    <a href="{{ route('barang.edit', $barang) }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm font-medium">
                        Edit Barang
                    </a>
                    <a href="{{ route('barang-masuk.create') }}" class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition text-sm font-medium">
                        Barang Masuk
                    </a>
                    <a href="{{ route('barang-keluar.create') }}" class="block w-full text-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-sm font-medium">
                        Barang Keluar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
