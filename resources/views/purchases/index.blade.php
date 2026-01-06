@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-slate-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Manajemen Pembelian</h1>
                <p class="text-slate-600 mt-2">Kelola transaksi pembelian dari supplier</p>
            </div>
            <a href="{{ route('purchases.create') }}" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-300">
                <i class="fas fa-plus mr-2"></i>Transaksi Baru
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
                            <th class="px-6 py-4 text-left text-sm font-bold">Nomor PO</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Supplier</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Barang</th>
                            <th class="px-6 py-4 text-left text-sm font-bold">Tanggal</th>
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
                                        <a href="{{ route('purchases.show', $purchase->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-bold transition-colors duration-200">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                        {{-- <a href="{{ route('purchases.edit', $purchase->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-2 rounded-lg text-sm font-bold transition-colors duration-200">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a> --}}
                                        <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus?')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-bold transition-colors duration-200">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-4xl text-slate-300 mb-4"></i>
                                    <p class="text-slate-500 font-semibold">Tidak ada data transaksi pembelian</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $purchases->links() }}
        </div>
    </div>
</div>
@endsection
