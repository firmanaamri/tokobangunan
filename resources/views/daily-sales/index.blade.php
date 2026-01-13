@extends('layouts.app')

@section('content')
{{-- Ubah p-6 jadi p-4 di mobile (p-4 md:p-6) agar space lebih lega --}}
<div class="p-4 md:p-6">
    
    {{-- HEADER: Gunakan flex-col untuk mobile, md:flex-row untuk desktop --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Pencatatan Penjualan</h2>
            <p class="text-slate-500 text-sm">{{ \Carbon\Carbon::parse($date ?? now())->format('d/m/Y') }}</p>
        </div>
        
        {{-- Tombol dibuat full width di mobile (w-full), auto di desktop --}}
        <a href="{{ route('daily-sales.create') }}" class="w-full md:w-auto text-center bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 shadow-lg">
            + Buat Pencatatan
        </a>
    </div>

    {{-- TABEL PENCATATAN --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-8">
        {{-- Wrapper overflow-x-auto wajib ada agar tabel bisa di-scroll ke samping di HP --}}
        <div class="overflow-x-auto w-full">
            @if($items->isEmpty())
                <div class="p-6 text-sm text-slate-500 text-center">Belum ada barang keluar untuk tanggal ini.</div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase whitespace-nowrap">Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase whitespace-nowrap">Barang</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase whitespace-nowrap">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase whitespace-nowrap">Petugas</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase whitespace-nowrap">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($items as $it)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600">{{ optional($it->created_at)->format('H:i') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">{{ $it->barang->nama_barang ?? '—' }}</p>
                                            <p class="text-xs text-slate-500">SKU: {{ $it->barang->sku ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-semibold text-amber-600">{{ number_format($it->jumlah_barang_keluar) }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600">{{ $it->user->name ?? '—' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600">{{Str::limit($it->keterangan, 20) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- REKAP SECTION --}}
    <div class="mt-8">
        <h3 class="text-lg font-bold mb-4 text-slate-800">Rekap Penjualan</h3>

        {{-- FORM FILTER: Ubah grid-cols-3 menjadi grid-cols-1 md:grid-cols-3 --}}
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end mb-6">
            <div class="w-full">
                <label class="block text-sm text-slate-600 mb-1">Tampilkan Tanggal:</label>
                <input type="date" name="date" value="{{ $date ?? date('Y-m-d') }}" class="w-full p-2.5 rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-blue-500 outline-none transition" />
            </div>
            <div class="w-full">
                <label class="block text-sm text-slate-600 mb-1">Cari Barang:</label>
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Nama barang..." class="w-full p-2.5 rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-blue-500 outline-none transition" />
            </div>
            {{-- Tombol Action: Flex row agar tombol berdampingan --}}
            <div class="flex gap-2 w-full">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-sm font-medium">Filter</button>
                <button type="button" onclick="window.location='{{ route('daily-sales.index') }}'" class="px-4 py-2.5 bg-red-100 hover:bg-red-200 text-red-700 border border-red-200 rounded-lg transition shadow-sm font-medium">Reset</button>
            </div>
        </form>

        {{-- TABEL REKAP --}}
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="overflow-x-auto w-full">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap">Nama Barang</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap">Total Barang</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap">Terakhir Dicatat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($records as $r)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">{{ $r->barang?->nama_barang ?? '-' }}</p>
                                            <p class="text-xs text-slate-500">SKU: {{ $r->barang?->sku ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-900 font-bold">
                                    {{ number_format($r->total_jumlah) }} <span class="text-slate-500 font-normal">{{ $r->barang?->satuan ?? 'pcs' }}</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-600">{{ optional($r->last_recorded)->format('H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="text-gray-400 mb-2 text-2xl">📂</span>
                                        <p>Tidak ada data rekap untuk tanggal ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SUMMARY BOX --}}
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-slate-700 text-sm md:text-base">
            <p>Total penjualan hari ini: <strong class="text-blue-700">{{ number_format($totalPortions ?? 0) }} barang</strong> dari <strong class="text-blue-700">{{ $typesCount ?? 0 }}</strong> kategori.</p>
        </div>
    </div>
</div>
@endsection