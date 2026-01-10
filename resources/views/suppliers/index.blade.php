@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-slate-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Manajemen Supplier</h1>
                <p class="text-slate-600 mt-2">Kelola data supplier untuk pembelian barang</p>
            </div>
            <a href="{{ route('suppliers.create') }}" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-300">
                <i class="fas fa-plus mr-2"></i>Supplier Baru
            </a>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <strong>Terjadi kesalahan:</strong>
                <ul class="list-disc ml-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.style.display='none';" class="text-green-700 font-bold text-xl">&times;</button>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold">Nama Supplier</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Kontak Person</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Telepon</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Kota</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($suppliers as $supplier)
                            <tr class="hover:bg-slate-50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-900">{{ $supplier->nama_supplier }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-slate-900">{{ $supplier->kontak_person ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-slate-900">{{ $supplier->nomor_telepon ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-slate-900">{{ $supplier->email ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-slate-900">{{ $supplier->kota ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($supplier->status == 'aktif')
                                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Aktif</span>
                                    @else
                                        <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <a href="{{ route('suppliers.show', $supplier->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors duration-200" aria-label="Lihat">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors duration-200" aria-label="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus?')" class="bg-red-500 hover:bg-red-600 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors duration-200" aria-label="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-4xl text-slate-300 mb-4"></i>
                                    <p class="text-slate-500 font-semibold">Tidak ada data supplier</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>
@endsection
