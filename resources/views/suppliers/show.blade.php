@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-slate-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Detail Supplier</h1>
                <p class="text-slate-600 mt-2">{{ $supplier->nama_supplier }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('suppliers.index') }}" class="bg-slate-500 hover:bg-slate-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Card Info Dasar -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wide mb-4">Informasi Dasar</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-1">Nama Supplier</p>
                        <p class="text-lg font-bold text-slate-900">{{ $supplier->nama_supplier }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-1">Kontak Person</p>
                        <p class="text-slate-900">{{ $supplier->kontak_person ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-1">Nomor Telepon</p>
                        <p class="text-slate-900">{{ $supplier->nomor_telepon ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-1">Email</p>
                        <p class="text-slate-900">{{ $supplier->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Card Lokasi -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wide mb-4">Lokasi</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-1">Alamat</p>
                        <p class="text-slate-900">{{ $supplier->alamat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-1">Kota</p>
                        <p class="text-slate-900">{{ $supplier->kota ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-1">Provinsi</p>
                        <p class="text-slate-900">{{ $supplier->provinsi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold mb-1">Kode Pos</p>
                        <p class="text-slate-900">{{ $supplier->kode_pos ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-8">
            <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wide mb-4">Status dan Keterangan</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Status</p>
                    @if ($supplier->status == 'aktif')
                        <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg font-bold">Aktif</span>
                    @else
                        <span class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-lg font-bold">Nonaktif</span>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Terdaftar Pada</p>
                    <p class="text-slate-900">{{ $supplier->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
            @if ($supplier->keterangan)
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Keterangan</p>
                    <p class="text-slate-900">{{ $supplier->keterangan }}</p>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Yakin ingin menghapus supplier ini?')" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>Hapus Supplier
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
