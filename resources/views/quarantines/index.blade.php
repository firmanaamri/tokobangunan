@extends('layouts.app')

@section('content')
<div class="p-4 md:p-6">
    
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Daftar Quarantine</h1>
            <p class="text-slate-500 text-sm">Barang ditolak, rusak, atau menunggu tindak lanjut.</p>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <div class="mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div class="w-full">
                <label class="block text-sm font-semibold  mb-1">Pencarian</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari barang / PO..." class="w-full p-2.5 rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-blue-500 outline-none transition" />
            </div>
            <div class="w-full">
                <label class="block text-sm font-semibold  mb-1">Status</label>
                <select name="status" class="w-full p-2.5 rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="returned" {{ request('status')=='returned' ? 'selected' : '' }}>Returned</option>
                    <option value="repaired" {{ request('status')=='repaired' ? 'selected' : '' }}>Repaired</option>
                    <option value="disposed" {{ request('status')=='disposed' ? 'selected' : '' }}>Disposed</option>
                </select>
            </div>
            <div class="w-full">
                <label class="block text-sm font-semibold  mb-1">Supplier</label>
                <select name="supplier_id" class="w-full p-2.5 rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="">Semua Supplier</option>
                    @if(isset($suppliers))
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_supplier ?? $s->nama ?? 'Supplier' }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="flex gap-2 w-full">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 px-4 rounded-lg transition shadow-sm font-medium">Filter</button>
                <button type="button" onclick="window.location='{{ route('admin.quarantines.index') }}'" class="px-4 py-2.5 bg-red-100 hover:bg-red-200 text-red-700 border border-red-200 rounded-lg transition shadow-sm font-medium">Reset</button>
            </div>
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold  uppercase whitespace-nowrap">ID</th>
                        <th class="px-4 py-3 text-left font-bold  uppercase whitespace-nowrap">Barang</th>
                        <th class="px-4 py-3 text-left font-bold  uppercase whitespace-nowrap">Supplier / PO</th>
                        <th class="px-4 py-3 text-center font-bold  uppercase whitespace-nowrap">Qty</th>
                        <th class="px-4 py-3 text-left font-bold  uppercase whitespace-nowrap">Alasan</th>
                        <th class="px-4 py-3 text-left font-bold  uppercase whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 text-center font-bold  uppercase whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($quarantines as $q)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-4 whitespace-nowrap ">#{{ $q->id }}</td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800">{{ $q->barang->nama_barang ?? '-' }}</span>
                                <span class="text-xs text-slate-500">{{ $q->barang->sku ?? '' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap ">
                            {{ $q->barangMasuk?->purchase?->nomor_po ?? '-' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center font-bold text-amber-600">
                            {{ $q->quantity }}
                        </td>
                        <td class="px-4 py-4 min-w-[200px] ">
                            {{ Str::limit($q->reason, 60) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @php
                                $colors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'returned' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'repaired' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'disposed' => 'bg-red-100 text-red-800 border-red-200',
                                ];
                                $cls = $colors[$q->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $cls }}">
                                {{ ucfirst($q->status) }}
                            </span>
                        </td>
                        
                        {{-- BAGIAN AKSI YANG DIPERBAIKI --}}
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="flex gap-2 justify-center">
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('admin.quarantines.show', $q->id) }}" title="Detail" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors duration-200 inline-flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>
                                </div>

                                {{-- Hidden Form --}}
                                <div id="status-box-{{ $q->id }}" class="hidden mt-2 p-2 bg-slate-100 rounded border border-slate-300 w-40">
                                    <form action="{{ route('admin.quarantines.update', $q->id) }}" method="POST" class="flex flex-col gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="text-xs p-1 border rounded w-full">
                                            <option value="pending" {{ $q->status=='pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="returned" {{ $q->status=='returned' ? 'selected' : '' }}>Returned</option>
                                            <option value="repaired" {{ $q->status=='repaired' ? 'selected' : '' }}>Repaired</option>
                                            <option value="disposed" {{ $q->status=='disposed' ? 'selected' : '' }}>Disposed</option>
                                        </select>
                                        <button type="submit" class="bg-green-600 text-white text-xs py-1 rounded hover:bg-green-700 w-full font-bold">Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                            Tidak ada data quarantine ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $quarantines->links() }}
    </div>
</div>

<script>
    function toggleStatus(id) {
        document.getElementById('status-box-' + id).classList.toggle('hidden');
    }
</script>
@endsection