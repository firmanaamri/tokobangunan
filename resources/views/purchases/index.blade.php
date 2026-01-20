@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Manajemen Pembelian</h1>
                <p class="text-slate-600 mt-2">Kelola transaksi pembelian dari supplier</p>
            </div>
            <a href="{{ route('purchase-requests.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-300">
                <i class="fas fa-plus mr-2"></i> Buat PR
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold">Nomor PO</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Supplier</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Barang</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Tanggal</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Jatuh Tempo</th>
                            <th class="px-6 py-4 text-right text-sm font-bold">Total Harga</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Status Bayar</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($purchases as $purchase)
                            <tr class="hover:bg-slate-50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-bold text-slate-900">{{ $purchase->nomor_po }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $purchase->supplier->nama_supplier }}</p>
                                        <p class="text-sm text-slate-500">{{ $purchase->supplier->nomor_telepon }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        @php
                                            $barangMasuk = $purchase->barangMasuk;
                                            $barang = $barangMasuk?->barang ?? ($purchase->barang ?? null);
                                        @endphp
                                        <p class="font-semibold text-slate-900">{{ $barang?->nama_barang ?? 'Belum ada barang masuk' }}</p>
                                        <p class="text-sm text-slate-500">
                                            {{ $barangMasuk?->jumlah_barang_masuk ?? $purchase->jumlah_po ?? '-' }}
                                            {{ $barang?->satuan ?? $purchase->satuan ?? '' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-slate-900">{{ $purchase->tanggal_pembelian->format('d M Y') }}</span>
                                </td>
                                
                                {{-- KOLOM JATUH TEMPO (SUDAH DIPERBAIKI) --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $dueDate = null;

                                        // 1. Cek langsung dari kolom 'due_date' (Prioritas Utama)
                                        // Karena sudah dicasting 'date' di Model, ini otomatis jadi objek Carbon
                                        if (!empty($purchase->due_date)) {
                                            $dueDate = $purchase->due_date;
                                        } 
                                        // 2. Fallback: Cek dari Purchase Request (jika ada)
                                        elseif (!empty($purchase->purchaseRequest) && !empty($purchase->purchaseRequest->due_date)) {
                                            $dueDate = $purchase->purchaseRequest->due_date;
                                        } 
                                        // 3. Fallback: Hitung dari Payment Term PR
                                        elseif (!empty($purchase->purchaseRequest) && !empty($purchase->purchaseRequest->payment_term)) {
                                            $dueDate = \Carbon\Carbon::parse($purchase->tanggal_pembelian)->addDays(intval($purchase->purchaseRequest->payment_term));
                                        }

                                        // Cek apakah telat bayar (Overdue)
                                        $isOverdue = $dueDate ? ($dueDate->isPast() && $purchase->status_pembayaran != 'lunas') : false;
                                    @endphp

                                    @if($dueDate)
                                        @if($isOverdue)
                                            {{-- Tampilan Merah jika Telat --}}
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-red-600">
                                                    {{ $dueDate->format('d M Y') }}
                                                </span>
                                                <span class="text-[10px] uppercase font-bold text-red-500 tracking-wide mt-0.5">
                                                    Overdue
                                                </span>
                                            </div>
                                        @else
                                            {{-- Tampilan Normal --}}
                                            <span class="text-sm text-slate-700 font-medium">
                                                {{ $dueDate->format('d M Y') }}
                                            </span>
                                        @endif
                                    @else
                                        {{-- Tampilan Kosong --}}
                                        <span class="text-sm text-slate-400">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-slate-900">Rp {{ number_format($purchase->total_harga, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($purchase->status_pembayaran == 'belum_bayar')
                                        <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">Belum Bayar</span>
                                    @elseif ($purchase->status_pembayaran == 'sebagian')
                                        <span class="inline-block bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-bold">Sebagian</span>
                                    @else
                                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Lunas</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <a href="{{ route('purchases.show', $purchase->id) }}" aria-label="Lihat" class="bg-blue-500 hover:bg-blue-600 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        {{-- Jika butuh edit nanti, uncomment dan gunakan ikon ini --}}
                                        {{-- <a href="{{ route('purchases.edit', $purchase->id) }}" aria-label="Edit" class="bg-amber-500 hover:bg-amber-600 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a> --}}

                                        <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeletePurchase(this.form)" aria-label="Hapus" class="bg-red-500 hover:bg-red-600 text-white w-9 h-9 inline-flex items-center justify-center rounded-lg transition-colors duration-200">
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
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-4xl text-slate-300 mb-4"></i>
                                    <p class="text-slate-500 font-semibold">Tidak ada data transaksi pembelian</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $purchases->links() }}
        </div>
    </div>
</div>
@endsection