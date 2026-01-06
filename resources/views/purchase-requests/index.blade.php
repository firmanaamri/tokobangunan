@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-slate-50 p-6">
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
                                    <a href="{{ route('purchase-requests.show', $pr) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-bold transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>Lihat
                                    </a>
                                    @if($pr->status === 'pending' && $pr->user_id === auth()->id())
                                        <a href="{{ route('purchase-requests.edit', $pr) }}" class="bg-slate-500 hover:bg-slate-600 text-white px-3 py-2 rounded-lg text-sm font-bold transition-colors duration-200">
                                            <i class="fas fa-edit mr-1"></i>Edit
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
