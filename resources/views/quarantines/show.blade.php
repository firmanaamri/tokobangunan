@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-4 md:p-6">
    
    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <span>Quarantine</span>
                <span>/</span>
                <span>Detail</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                Item #{{ $quarantine->id }}
            </h1>
        </div>

        <a href="{{ route('admin.quarantines.index') }}" class="group flex items-center gap-2 px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-medium text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: DETAIL INFORMASI (Lebar 2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- KARTU UTAMA --}}
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-slate-800 mb-4">Informasi Barang</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Barang --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nama Barang</label>
                            <div class="text-slate-900 font-medium text-lg">{{ $quarantine->barang->nama_barang ?? '-' }}</div>
                            <div class="text-slate-500 text-sm">SKU: {{ $quarantine->barang->sku ?? '-' }}</div>
                        </div>

                        {{-- Asal Barang (PO) --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Asal (Nomor PO)</label>
                            <div class="flex items-center gap-2">
                                <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-sm font-semibold border border-blue-100">
                                    {{ $quarantine->barangMasuk?->purchase?->nomor_po ?? 'Tanpa PO' }}
                                </span>
                            </div>
                        </div>

                        {{-- Jumlah --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Jumlah Bermasalah</label>
                            <div class="text-2xl font-bold text-amber-600">{{ number_format($quarantine->quantity) }} <span class="text-sm font-normal text-slate-500">{{ $quarantine->barang->satuan ?? 'pcs' }}</span></div>
                        </div>

                        {{-- Status Saat Ini --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Status Terkini</label>
                            @php
                                $statusStyles = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'returned' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'repaired' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'disposed' => 'bg-red-100 text-red-800 border-red-200',
                                ];
                                $currentStyle = $statusStyles[$quarantine->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold border {{ $currentStyle }}">
                                {{ ucfirst($quarantine->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Bagian Alasan --}}
                <div class="p-6 bg-slate-50/50">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Alasan / Keterangan Kerusakan</label>
                    <div class="p-4 bg-white border border-slate-200 rounded-lg text-slate-700 leading-relaxed shadow-sm">
                        {{ $quarantine->reason ?: 'Tidak ada keterangan detail.' }}
                    </div>
                </div>
            </div>

            {{-- FOTO BUKTI --}}
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Dokumentasi Foto</h3>
                @if($quarantine->photo)
                    <div class="rounded-lg overflow-hidden border border-slate-200 bg-slate-50">
                        <img src="{{ asset('storage/' . $quarantine->photo) }}" class="w-full h-auto max-h-[500px] object-contain mx-auto" alt="Foto Kerusakan">
                    </div>
                    <div class="mt-2 text-right">
                        <a href="{{ asset('storage/' . $quarantine->photo) }}" target="_blank" class="text-sm text-blue-600 hover:underline">Lihat Ukuran Penuh &nearr;</a>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 bg-slate-50 border-2 border-dashed border-slate-300 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-slate-500 text-sm">Tidak ada foto dokumentasi yang dilampirkan.</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- KOLOM KANAN: PANEL AKSI (Lebar 1/3) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg border overflow-hidden sticky top-6">
                <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-4">
                    <h3 class="text-white font-bold text-lg">Tindak Lanjut</h3>
                    <p class="text-slate-300 text-xs">Perbarui status penanganan barang.</p>
                </div>
                
                <div class="p-6">
                    @if($quarantine->status === 'pending')
                        <form action="{{ route('admin.quarantines.update', $quarantine->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Status Baru</label>
                                <div class="space-y-2">
                                    {{-- PENDING --}}
                                    <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer transition {{ $quarantine->status == 'pending' ? 'ring-2 ring-yellow-400 border-yellow-400 bg-yellow-50' : 'border-slate-200' }}">
                                        <input type="radio" name="status" value="pending" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ $quarantine->status == 'pending' ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <span class="block text-sm font-medium text-slate-900">Pending</span>
                                            <span class="block text-xs text-slate-500">Menunggu keputusan</span>
                                        </div>
                                    </label>

                                    {{-- RETURNED --}}
                                    <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer transition {{ $quarantine->status == 'returned' ? 'ring-2 ring-blue-500 border-blue-500 bg-blue-50' : 'border-slate-200' }}">
                                        <input type="radio" name="status" value="returned" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ $quarantine->status == 'returned' ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <span class="block text-sm font-medium text-slate-900">Returned</span>
                                            <span class="block text-xs text-slate-500">Dikembalikan ke Supplier</span>
                                        </div>
                                    </label>

                                    {{-- REPAIRED --}}
                                    <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer transition {{ $quarantine->status == 'repaired' ? 'ring-2 ring-emerald-500 border-emerald-500 bg-emerald-50' : 'border-slate-200' }}">
                                        <input type="radio" name="status" value="repaired" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ $quarantine->status == 'repaired' ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <span class="block text-sm font-medium text-slate-900">Repaired</span>
                                            <span class="block text-xs text-slate-500">Sudah diperbaiki & kembali ke stok</span>
                                        </div>
                                    </label>

                                    {{-- DISPOSED --}}
                                    <label class="flex items-center p-3 border rounded-lg hover:bg-slate-50 cursor-pointer transition {{ $quarantine->status == 'disposed' ? 'ring-2 ring-red-500 border-red-500 bg-red-50' : 'border-slate-200' }}">
                                        <input type="radio" name="status" value="disposed" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ $quarantine->status == 'disposed' ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <span class="block text-sm font-medium text-slate-900">Disposed</span>
                                            <span class="block text-xs text-slate-500">Dibuang / Dimusnahkan</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Perubahan
                            </button>
                        </form>
                    @else
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Status Tindak Lanjut</label>
                            <div class="p-4 border rounded-lg bg-slate-50 text-slate-700">
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold border {{ $statusStyles[$quarantine->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($quarantine->status) }}
                                </span>
                                <p class="text-sm text-slate-500 mt-2">Status telah ditetapkan dan tidak dapat diubah kembali.</p>
                            </div>
                        </div>

                        <button disabled class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-slate-300 cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    @endif
                </div>
            </div>
            
            {{-- Info Tambahan --}}
            <div class="mt-4 text-center">
                <p class="text-xs text-slate-400">Dibuat pada: {{ $quarantine->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

    </div>
</div>
@endsection