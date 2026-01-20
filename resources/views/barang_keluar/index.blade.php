@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white p-6">
    <div class="max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Laporan Barang Keluar</h1>
            <p class="text-sm text-slate-500 mt-1">Riwayat barang yang keluar dari transaksi penjualan.</p>
        </div>
        
        <div class="flex flex-col gap-3 w-full">
            <form method="GET" action="{{ route('barang-keluar.index') }}" class="flex flex-col md:flex-row gap-2 items-end w-full">
                <div class="flex-1">
                    <label class="text-xs font-semibold text-slate-600 mb-1 block">Filter</label>
                    <select name="filter" id="filter" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-400 w-full">
                        <option value="all">Semua Data</option>
                        <option value="today">Hari Ini</option>
                        <option value="week">Minggu Ini</option>
                        <option value="month">Bulan Ini</option>
                        <option value="year">Tahun Ini</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                
                <div id="customDateRange" class="hidden flex gap-2 flex-1">
                    <div class="flex-1">
                        <label class="text-xs font-semibold text-slate-600 mb-1 block">Dari</label>
                        <input type="date" name="start_date" class="px-3 py-2 border border-slate-300 rounded-lg w-full">
                    </div>
                    <div class="flex-1">
                        <label class="text-xs font-semibold text-slate-600 mb-1 block">Sampai</label>
                        <input type="date" name="end_date" class="px-3 py-2 border border-slate-300 rounded-lg w-full">
                    </div>
                </div>
                
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-none bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <span>Cari</span>
                    </button>
                    
                    <button type="button" onclick="togglePdfForm()" class="flex-1 md:flex-none bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        <span>PDF</span>
                    </button>
                </div>
            </form>

            <form id="pdfForm" method="GET" action="{{ route('barang-keluar.export-pdf') }}" class="hidden flex flex-col md:flex-row gap-2 items-end bg-red-50 p-3 rounded-lg">
                <div class="flex-1">
                    <label class="text-xs font-semibold text-slate-600 mb-1 block">Filter untuk PDF</label>
                    <select name="filter" id="filterPdf" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-400 w-full">
                        <option value="all">Semua Data</option>
                        <option value="today">Hari Ini</option>
                        <option value="week">Minggu Ini</option>
                        <option value="month">Bulan Ini</option>
                        <option value="year">Tahun Ini</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                
                <div id="customDateRangePdf" class="hidden flex gap-2 flex-1">
                    <div class="flex-1">
                        <label class="text-xs font-semibold text-slate-600 mb-1 block">Dari</label>
                        <input type="date" name="start_date" class="px-3 py-2 border border-slate-300 rounded-lg w-full">
                    </div>
                    <div class="flex-1">
                        <label class="text-xs font-semibold text-slate-600 mb-1 block">Sampai</label>
                        <input type="date" name="end_date" class="px-3 py-2 border border-slate-300 rounded-lg w-full">
                    </div>
                </div>
                
                <button type="submit" class="bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 text-white font-semibold py-2 px-6 rounded-lg shadow-lg transition-all duration-300 flex items-center justify-center gap-2 w-full md:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                    <span>Download PDF</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleCustomRange(filterId, rangeId) {
            const filterSelect = document.getElementById(filterId);
            const customRange = document.getElementById(rangeId);
            if (filterSelect.value === 'custom') {
                customRange.classList.remove('hidden');
            } else {
                customRange.classList.add('hidden');
            }
        }

        function togglePdfForm() {
            const pdfForm = document.getElementById('pdfForm');
            pdfForm.classList.toggle('hidden');
        }

        // Event listeners
        document.getElementById('filter').addEventListener('change', function() {
            toggleCustomRange('filter', 'customDateRange');
        });

        document.getElementById('filterPdf').addEventListener('change', function() {
            toggleCustomRange('filterPdf', 'customDateRangePdf');
        });
    </script>

    {{-- Info Notice --}}
    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg flex items-start">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 mr-3 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <div class="text-sm text-amber-700">
            <strong class="font-semibold">Informasi:</strong>
            <p class="mt-1">Data ini otomatis dibuat dari transaksi <a href="{{ route('daily-sales.index') }}" class="underline hover:text-amber-900">Penjualan</a>. Untuk menambah barang keluar, silakan buat transaksi penjualan baru.</p>
        </div>
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

    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Kategori</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase">Jumlah Keluar</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Tanggal Keluar</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase">User</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($items as $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-red-50 rounded flex items-center justify-center text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 16l4-4-4-4" />
                                        <path d="M7 20h8a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H7" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-slate-900">{{ $item->barang->nama_barang }}</p>
                                    <p class="text-xs text-slate-500">SKU: {{ $item->barang->sku }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded bg-blue-100 text-blue-800">
                                {{ $item->barang->kategori?->nama_kategori ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-semibold text-red-600">{{ number_format($item->jumlah_barang_keluar) }}</span>
                            <span class="text-xs text-slate-500 block">{{ $item->barang->satuan ?? 'pcs' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $item->tanggal_keluar->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            <div class="max-w-xs">
                                {{ $item->keterangan ?? '-' }}
                                @if($item->sale_id)
                                    <a href="{{ route('sales.show', $item->sale_id) }}" class="text-amber-600 hover:text-amber-800 text-xs block mt-1">
                                        Lihat Transaksi Penjualan →
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $item->user?->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                            <svg class="h-10 w-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                            Belum ada data barang keluar. Silakan buat <a href="{{ route('daily-sales.index') }}" class="text-amber-600 hover:text-amber-800 underline">transaksi penjualan</a> untuk mengeluarkan barang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
