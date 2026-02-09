@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white p-3 md:p-6"> {{-- Background diubah sedikit agar card lebih kontras --}}
    <div class="max-w-4xl mx-auto">
        {{-- Header Section: Stacked on Mobile, Row on Desktop --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Kelola Kategori</h1>
                <p class="text-slate-600 text-sm md:text-base">Daftar kategori barang yang tersedia</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('stokbarang') }}" class="flex-1 md:flex-none px-4 py-2 bg-white text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50 font-medium flex items-center justify-center text-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('kategori.create') }}" class="flex-1 md:flex-none px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold shadow-md flex items-center justify-center text-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </a>
            </div>
        </div>

        {{-- Table Container with Horizontal Scroll --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto"> {{-- Wrapper untuk scroll horizontal di mobile --}}
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                        <tr>
                            <th class="px-4 md:px-6 py-4 text-left text-xs font-bold  uppercase tracking-wider">Nama Kategori</th>
                            <th class="px-4 md:px-6 py-4 text-center text-xs font-bold  uppercase tracking-wider">Jumlah</th>
                            <th class="px-4 md:px-6 py-4 text-center text-xs font-bold  uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($kategoris as $k)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 md:px-6 py-4">
                                <span class="font-medium text-slate-900 text-sm md:text-base break-words">{{ $k->nama_kategori }}</span>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-center">
                                <span class="inline-flex px-2.5 py-0.5 bg-blue-50 text-blue-700 rounded-full text-[10px] md:text-xs font-bold border border-blue-100 whitespace-nowrap">
                                    {{ $k->barang_count }} Item
                                </span>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('kategori.edit', $k->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white p-2 rounded-lg shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                        </svg>
                                    </a>

                                    <form class="delete-user-form" action="{{ route('kategori.destroy', $k->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center  text-sm">
                                Belum ada kategori tersedia.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert Scripts Tetap Sama --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- ... script JS Anda di bawah ... --}}
@endsection