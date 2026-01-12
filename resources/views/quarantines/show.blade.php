@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Detail Quarantine #{{ $quarantine->id }}</h1>
        <button type="button" onclick="location.href='{{ route('admin.quarantines.index') }}'" class="ml-4 inline-flex items-center px-3 py-2 bg-slate-600 text-white rounded-md hover:bg-slate-200 transition">Kembali</button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm text-slate-500">Barang</dt>
                <dd class="font-medium">{{ $quarantine->barang->nama_barang ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Jumlah</dt>
                <dd class="font-medium">{{ $quarantine->quantity }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">PO / Nomor</dt>
                <dd class="font-medium">{{ $quarantine->barangMasuk?->purchase?->nomor_po ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Status</dt>
                <dd class="font-medium">{{ ucfirst($quarantine->status) }}</dd>
            </div>
            <div class="col-span-2">
                <dt class="text-sm text-slate-500">Alasan / Catatan</dt>
                <dd class="mt-1">{{ $quarantine->reason }}</dd>
            </div>
            <div class="col-span-2">
                <dt class="text-sm text-slate-500">Foto Kerusakan</dt>
                <dd class="mt-2">
                    @if($quarantine->photo)
                        <img src="{{ asset('storage/' . $quarantine->photo) }}" class="max-h-80 rounded" alt="Foto Kerusakan">
                    @else
                        <span class="text-slate-500">Tidak ada foto</span>
                    @endif
                </dd>
            </div>
        </dl>

        <div class="mt-6">
            <form action="{{ route('admin.quarantines.update', $quarantine->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="flex items-center gap-3">
                    <select name="status" class="px-3 py-2 border rounded">
                        <option value="pending" {{ $quarantine->status=='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="returned" {{ $quarantine->status=='returned' ? 'selected' : '' }}>Returned</option>
                        <option value="repaired" {{ $quarantine->status=='repaired' ? 'selected' : '' }}>Repaired</option>
                        <option value="disposed" {{ $quarantine->status=='disposed' ? 'selected' : '' }}>Disposed</option>
                    </select>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
