@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Pencatatan Penjualan - {{ \Carbon\Carbon::parse($date ?? now())->format('d/m/Y') }}</h2>
        <a href="{{ route('daily-sales.create') }}" class="px-4 py-2 bg-amber-400 text-white rounded">Buat Pencatatan</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            @if($items->isEmpty())
                <div class="p-6 text-sm text-slate-500">Belum ada barang keluar untuk tanggal ini.</div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Barang</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Petugas</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($items as $it)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ optional($it->created_at)->format('H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-slate-900">{{ $it->barang->nama_barang ?? '—' }}</p>
                                            <p class="text-xs text-slate-500">SKU: {{ $it->barang->sku ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-amber-600">{{ number_format($it->jumlah_barang_keluar) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $it->user->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $it->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

        {{-- REKAP DI BAWAH TABEL PENCATATAN --}}
        <div class="mt-6">
            <h3 class="text-lg font-bold mb-3">Rekap Penjualan</h3>

            <form method="GET" class="grid grid-cols-3 gap-4 items-end mb-4">
                <div>
                    <label class="block text-sm text-slate-600">Tampilkan Tanggal:</label>
                    <input type="date" name="date" value="{{ $date ?? date('Y-m-d') }}" class="mt-1 p-2 rounded border bg-white/50" />
                </div>
                <div>
                    <label class="block text-sm text-slate-600">Cari Barang:</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Nama barang..." class="mt-1 p-2 rounded border bg-white/50" />
                </div>
                <div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
                    <a href="{{ route('daily-sales.index') }}" class="ml-2 px-4 py-2 bg-gray-200 text-slate-700 rounded">Reset</a>
                </div>
            </form>

            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Total Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Terakhir Dicatat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($records as $r)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-slate-900">{{ $r->barang?->nama_barang ?? '-' }}</p>
                                                <p class="text-xs text-slate-500">SKU: {{ $r->barang?->sku ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ number_format($r->total_jumlah) }} {{ $r->barang?->satuan ?? 'pcs' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ optional($r->last_recorded)->format('H:i') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-6 text-center text-slate-500">Tidak ada data untuk tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded text-slate-700">
                Total penjualan hari ini: <strong>{{ number_format($totalPortions ?? 0) }} barang</strong> dari <strong>{{ $typesCount ?? 0 }}</strong> kategori barang.
            </div>
        </div>
</div>
@endsection
