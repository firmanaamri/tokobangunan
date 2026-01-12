@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF7F2] via-[#F8F4EE] to-[#FAF7F2] p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Pengajuan Pembelian (PR)</h1>
                <p class="text-slate-600 mt-2">Daftar permintaan pembelian barang dari staff</p>
            </div>
            <a href="{{ route('purchase-requests.create') }}" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-300">
                <i class="fas fa-plus mr-2"></i>Ajukan PR Baru
            </a>
        </div>

        <!-- Alerts -->
        <!-- Alerts handled by SweetAlert -->

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="text-xs font-semibold text-slate-600">Cari Nomor PR</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="PR000001" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 focus:border-emerald-400 focus:ring-emerald-200">
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600">Status</label>
                    <select name="status" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 focus:border-emerald-400 focus:ring-emerald-200">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button class="w-full bg-gradient-to-r from-slate-800 to-slate-700 text-white font-semibold px-4 py-2 rounded-lg shadow hover:from-slate-900 hover:to-slate-800 transition">Filter</button>
                    <a href="{{ route('purchase-requests.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table -->
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
                    <tbody class="divide-y divide-slate-200">
                        @forelse($purchaseRequests as $pr)
                        <tr class="hover:bg-slate-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-bold text-slate-900">{{ $pr->nomor_pr }}</span>
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
                                    $statusColor = [
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'approved' => 'bg-emerald-100 text-emerald-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'completed' => 'bg-blue-100 text-blue-800',
                                    ][$pr->status] ?? 'bg-slate-100 text-slate-800';
                                @endphp
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                    {{ ucfirst($pr->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('purchase-requests.show', $pr) }}" class="bg-blue-500 hover:bg-blue-600 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors duration-200" aria-label="Lihat">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if($pr->status === 'pending' && $pr->user_id === auth()->id())
                                        <a href="{{ route('purchase-requests.edit', $pr) }}" class="bg-slate-500 hover:bg-slate-600 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors duration-200" aria-label="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-4xl text-slate-300 mb-4"></i>
                                    <p class="text-slate-500 font-semibold">Tidak ada data Purchase Request</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $purchaseRequests->links() }}
        </div>
    </div>
</div>
@endsection
