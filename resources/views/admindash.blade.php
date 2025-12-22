{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-blue-900 text-center">
                    {{ __("Selamat Datang Admin!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}

<x-app-layout>

    <x-slot:title>
        Dashboard Utama
    </x-slot:title>

    <x-slot:header>
        Dashboard
    </x-slot:header>

    <div class="space-y-8">
    
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                <h4 class="text-sm font-medium text-slate-500">Total Produk</h4>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($totalProduk) }}</p>
                <span class="text-xs text-emerald-500 block mt-2">+{{ $produkBaru }} item baru</span>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                <h4 class="text-sm font-medium text-slate-500">Stok Akan Habis</h4>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ $stokHabis }}</p>
                <span class="text-xs text-slate-500 block mt-2">Item dibawah batas minimum</span>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                <h4 class="text-sm font-medium text-slate-500">Total Supplier</h4>
                <h4 class="text-sm font-medium text-slate-500">Penjualan (Total)</h4>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($totalSales) }}</p>
                <span class="text-xs text-slate-500 block mt-2">Transaksi bulan ini: {{ $salesThisMonth }}</span>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                <h4 class="text-sm font-medium text-slate-500">Stok Keluar (Bulan Ini)</h4>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stokKeluarBulanIni) }}</p>
                <span class="text-xs text-slate-500 block mt-2">Total item keluar</span>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                <h4 class="text-sm font-medium text-slate-500">Pendapatan (Bulan Ini)</h4>
                <p class="text-3xl font-bold text-emerald-600 mt-2">Rp {{ number_format($revenueThisMonth, 2, ',', '.') }}</p>
                <a href="{{ route('sales.index') }}" class="inline-flex items-center text-sm mt-3 text-emerald-600 hover:underline">Lihat Semua Penjualan</a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                <h4 class="text-sm font-medium text-slate-500">Keranjang Aktif</h4>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ session('cart') ? count(session('cart')) : 0 }}</p>
                <a href="{{ route('cart.index') }}" class="inline-flex items-center text-sm mt-3 text-slate-700 hover:underline">Buka Keranjang</a>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-slate-900">Aktivitas Stok Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Nama Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($aktivitas as $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $item['tanggal']->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $item['nama_barang'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item['tipe'] === 'Stok Masuk')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded bg-green-100 text-green-800">
                                        {{ $item['tipe'] }}
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded bg-red-100 text-red-800">
                                        {{ $item['tipe'] }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $item['jumlah'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $item['oleh'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada aktivitas stok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
    
</x-app-layout>
