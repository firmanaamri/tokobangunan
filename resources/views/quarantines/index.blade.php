@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Daftar Quarantine (Barang Ditolak/Rusak)</h1>
    </div>

    <form method="GET" class="flex gap-6 items-end mb-4">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari barang atau PO..." class="border px-3 py-2 rounded w-1/3" />
            <select name="status" class="border px-5 py-2 rounded">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                <option value="returned" {{ request('status')=='returned' ? 'selected' : '' }}>Returned</option>
                <option value="repaired" {{ request('status')=='repaired' ? 'selected' : '' }}>Repaired</option>
                <option value="disposed" {{ request('status')=='disposed' ? 'selected' : '' }}>Disposed</option>
            </select>
            <select name="supplier_id" class="border px-4 py-2 rounded">
                <option value="">Semua Supplier</option>
                @if(isset($suppliers))
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_supplier ?? $s->nama ?? $s->name ?? 'Supplier' }}</option>
                    @endforeach
                @endif
            </select>
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white px-3 py-2 rounded-lg">Filter</button>
                <button type="button" onclick="window.location='{{ route('admin.quarantines.index') }}'" class="bg-red-700 text-white px-3 py-2 rounded-lg hover:bg-red-800">Reset</button>
            </div>
        </form>

    <div class="bg-white rounded-xl shadow-lg border overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Barang</th>
                    <th class="px-4 py-3 text-left">Supplier / PO</th>
                    <th class="px-4 py-3 text-center">Qty</th>
                    <th class="px-4 py-3 text-left">Alasan</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($quarantines as $q)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">{{ $q->id }}</td>
                    <td class="px-4 py-3">{{ $q->barang->nama_barang ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $q->barangMasuk?->purchase?->nomor_po ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">{{ $q->quantity }}</td>
                    <td class="px-4 py-3">{{ Str::limit($q->reason, 80) }}</td>
                    <td class="px-4 py-3">{{ ucfirst($q->status) }}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex gap-2 justify-center">
                            <a href="{{ route('admin.quarantines.show', $q->id) }}" aria-label="Lihat" class="bg-blue-500 hover:bg-blue-600 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>

                            <a href="#" onclick="document.getElementById('form-status-{{ $q->id }}').classList.toggle('hidden')" title="Ubah Status" class="bg-amber-500 hover:bg-amber-600 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" /><path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>
                            </a>
                        </div>

                        <div id="form-status-{{ $q->id }}" class="hidden mt-2">
                            <form action="{{ route('admin.quarantines.update', $q->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="flex gap-2">
                                    <select name="status" class="px-3 py-2 border rounded">
                                        <option value="pending" {{ $q->status=='pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="returned" {{ $q->status=='returned' ? 'selected' : '' }}>Returned</option>
                                        <option value="repaired" {{ $q->status=='repaired' ? 'selected' : '' }}>Repaired</option>
                                        <option value="disposed" {{ $q->status=='disposed' ? 'selected' : '' }}>Disposed</option>
                                    </select>
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-slate-500">Belum ada item karantina</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $quarantines->links() }}
    </div>
</div>
@endsection
