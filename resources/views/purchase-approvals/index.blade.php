@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-slate-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Persetujuan Pengajuan Pembelian</h1>
            <p class="text-slate-600 mt-2">Daftar PR yang menunggu persetujuan admin</p>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex justify-between items-center">
                <span><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
                <button onclick="this.parentElement.style.display='none';" class="text-green-700 font-bold text-xl">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex justify-between items-center">
                <span><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</span>
                <button onclick="this.parentElement.style.display='none';" class="text-red-700 font-bold text-xl">&times;</button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-md border-l-4 border-amber-500 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase">Pending</p>
                        <p class="text-3xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
                    </div>
                    <i class="fas fa-hourglass-half text-4xl text-amber-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md border-l-4 border-emerald-500 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase">Approved</p>
                        <p class="text-3xl font-bold text-emerald-600">{{ $stats['approved'] }}</p>
                    </div>
                    <i class="fas fa-check-circle text-4xl text-emerald-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase">Rejected</p>
                        <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                    </div>
                    <i class="fas fa-times-circle text-4xl text-red-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase">Completed</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['completed'] }}</p>
                    </div>
                    <i class="fas fa-flag-checkered text-4xl text-blue-200"></i>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            @if($pendingPRs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-bold">Nomor PR</th>
                                <th class="px-6 py-4 text-left text-sm font-bold">Barang</th>
                                <th class="px-6 py-4 text-left text-sm font-bold">Supplier</th>
                                <th class="px-6 py-4 text-right text-sm font-bold">Qty</th>
                                <th class="px-6 py-4 text-left text-sm font-bold">Pengajuan Oleh</th>
                                <th class="px-6 py-4 text-left text-sm font-bold">Tanggal</th>
                                <th class="px-6 py-4 text-center text-sm font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($pendingPRs as $pr)
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
                                    <span class="text-xs text-slate-500 block">{{ $pr->satuan }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <p class="font-semibold">{{ $pr->user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $pr->user->email }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ $pr->created_at->format('d M Y') }}<br>
                                    <span class="text-xs text-slate-500">{{ $pr->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('purchase-approvals.show', $pr) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-bold transition-colors duration-200 inline-block">
                                        <i class="fas fa-eye mr-1"></i>Review
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                    {{ $pendingPRs->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-inbox text-4xl text-slate-300 mb-4"></i>
                    <p class="text-slate-500 font-semibold">Tidak ada PR yang menunggu persetujuan</p>
                    <p class="text-slate-400 text-sm mt-2">Semua PR sudah diproses!</p>
                </div>
            @endif
        </div>

        <!-- Info Box -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-blue-800 text-sm"><i class="fas fa-info-circle mr-2"></i><strong>Info:</strong> Klik tombol Review untuk melihat detail PR dan melakukan approval atau rejection. Saat approve, sistem otomatis akan membuat PO.</p>
        </div>
    </div>
</div>
@endsection
