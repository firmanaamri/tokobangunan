@extends('layouts.app')

@section('content')
{{-- Background Halaman: Cream Gradient sesuai permintaan --}}
<div class="min-h-screen bg-white p-6">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Pengajuan Pembelian (PR)</h1>
                <p class="text-slate-600 mt-2">Daftar permintaan pembelian barang dari staff</p>
            </div>
            <a href="{{ route('purchase-requests.create') }}" class="text-center w-fit bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-300">
                <i class="fas fa-plus mr-2"></i>Ajukan PR Baru
            </a>
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end mb-6">
            
            {{-- Kolom 1: Cari Nomor PR --}}
            <div class="w-full">
                <label class="block text-sm text-slate-600 mb-1">Cari Nomor PR:</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: PR000001" 
                       class="w-full p-2.5 rounded-lg border border-gray-300 bg-white focus:border-blue-500 focus:ring-blue-200 outline-none transition-all" />
            </div>

            {{-- Kolom 2: Status --}}
            <div class="w-full">
                <label class="block text-sm text-slate-600 mb-1">Status:</label>
                <select name="status" class="w-full p-2.5 rounded-lg border border-gray-300 bg-white focus:border-blue-500 focus:ring-blue-200 outline-none transition-all cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            {{-- Kolom 3: Tombol Action --}}
            <div class="flex gap-2 w-full">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-sm font-medium">
                    Filter
                </button>
                <a href="{{ route('purchase-requests.index') }}" class="px-4 py-2.5 bg-red-100 hover:bg-red-200 text-red-700 border border-red-200 rounded-lg transition shadow-sm font-medium text-center">
                    Reset
                </a>
            </div>
        </form>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold">Nomor PR</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Barang</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Supplier</th>
                            <th class="px-6 py-4 text-right text-sm font-bold">Jumlah</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Pengajuan</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($purchaseRequests as $pr)
                        <tr class="hover:bg-slate-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-bold text-slate-900 bg-slate-100 px-2 py-1 rounded">{{ $pr->nomor_pr }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $pr->barang->nama_barang }}</p>
                                    <p class="text-sm text-slate-500">{{ $pr->barang->kategori->nama_kategori ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $pr->supplier->nama_supplier }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-slate-900">{{ number_format($pr->jumlah_diminta) }}</span>
                                <span class="text-xs text-slate-500 block">{{ $pr->satuan ?? 'pcs' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $pr->user->name }}<br>
                                <span class="text-xs text-slate-500">{{ $pr->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'pending'   => 'bg-amber-100 text-amber-800 border border-amber-200',
                                        'approved'  => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                                        'rejected'  => 'bg-red-100 text-red-800 border border-red-200',
                                        'completed' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                    ];
                                    $currentClass = $statusClasses[$pr->status] ?? 'bg-slate-100 text-slate-800 border border-slate-200';
                                @endphp
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $currentClass }}">
                                    {{ ucfirst($pr->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('purchase-requests.show', $pr) }}" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors shadow-sm" 
                                    title="Lihat">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                    @if($pr->status === 'pending' && $pr->user_id === auth()->id())
                                        <a href="{{ route('purchase-requests.edit', $pr) }}" 
   class="bg-amber-500 hover:bg-amber-600 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors shadow-sm" 
   title="Edit">
    
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
    </svg>

</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center bg-slate-50">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-4xl text-slate-300 mb-4"></i>
                                    <p class="text-slate-500 font-semibold">Tidak ada data Purchase Request</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $purchaseRequests->links() }}
        </div>
    </div>
</div>
@endsection