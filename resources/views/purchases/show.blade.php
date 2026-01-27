@extends('layouts.app')

@section('content')
{{-- UPDATE: Background diganti ke tema Cream (System Theme) --}}
<div class="min-h-screen bg-white p-4 md:p-8 font-sans">
    <div class="max-w-5xl mx-auto">
        
        {{-- ================= HEADER SECTION ================= --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Detail Pembelian</h1>
                <div class="flex items-center gap-3 mt-2">
                    <span class="px-3 py-1 bg-white border border-slate-200 text-slate-700 rounded-md font-mono text-sm font-bold tracking-wide shadow-sm">
                        {{ $purchase->nomor_po }}
                    </span>
                    
                    {{-- Badge Status --}}
                    @if ($purchase->status_pembayaran == 'lunas')
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase tracking-wide border border-emerald-200">Lunas</span>
                    @elseif ($purchase->status_pembayaran == 'sebagian')
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase tracking-wide border border-blue-200">Sebagian</span>
                    @else
                        <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-bold uppercase tracking-wide border border-rose-200">Belum Bayar</span>
                    @endif
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                {{-- Tombol Catat Pembayaran --}}
                @if ($purchase->status_pembayaran != 'lunas')
                    <a href="{{ route('purchases.recordPayment', $purchase->id) }}" class="flex-1 md:flex-none text-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-wallet"></i> Catat Bayar
                    </a>
                @endif
                
                {{-- Tombol Kembali --}}
                <a href="{{ route('purchases.index') }}" class="group flex items-center gap-2 px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-medium text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
            </div>
        </div>

        {{-- ================= SUMMARY CARDS (GRID 3 KOLOM) ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            {{-- CARD 1: INFO DASAR --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500 group-hover:w-1.5 transition-all"></div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> Info Dasar
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 mb-0.5">Tanggal Pembelian</p>
                        <p class="font-bold text-slate-800">{{ $purchase->tanggal_pembelian->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-0.5">Jatuh Tempo</p>
                        @php
                            $dueDate = $purchase->due_date 
                                ? \Carbon\Carbon::parse($purchase->due_date) 
                                : ($purchase->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($purchase->tanggal_jatuh_tempo) : null);
                        @endphp
                        
                        @if($dueDate)
                            <p class="font-bold {{ $dueDate->isPast() && $purchase->status_pembayaran != 'lunas' ? 'text-rose-600' : 'text-slate-800' }}">
                                {{ $dueDate->format('d M Y') }}
                                @if($dueDate->isPast() && $purchase->status_pembayaran != 'lunas')
                                    <i class="fas fa-exclamation-circle ml-1 text-rose-500" title="Terlewat"></i>
                                @endif
                            </p>
                        @else
                            <p class="text-slate-400 font-bold">-</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- CARD 2: SUPPLIER --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-full bg-purple-500 group-hover:w-1.5 transition-all"></div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fas fa-truck"></i> Supplier
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="font-bold text-slate-900 text-lg">{{ $purchase->supplier->nama_supplier }}</p>
                        @if($purchase->supplier->contact_person)
                            <p class="text-xs text-slate-500">CP: {{ $purchase->supplier->contact_person }}</p>
                        @endif
                    </div>
                    <div class="pt-2 border-t border-slate-50 space-y-1">
                        @if($purchase->supplier->no_telp)
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <i class="fas fa-phone text-xs w-4 text-slate-400"></i> {{ $purchase->supplier->no_telp }}
                            </div>
                        @endif
                        @if($purchase->supplier->alamat)
                            <div class="flex items-start gap-2 text-sm text-slate-600">
                                <i class="fas fa-map-marker-alt text-xs w-4 text-slate-400 mt-1"></i> 
                                <span class="line-clamp-2">{{ $purchase->supplier->alamat }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- CARD 3: TOTAL HARGA --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group flex flex-col justify-between">
                <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500 group-hover:w-1.5 transition-all"></div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Tagihan</h3>
                    <p class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        <span class="text-sm font-medium text-slate-400 align-top mr-1">Rp</span>{{ number_format($purchase->total_harga, 0, ',', '.') }}
                    </p>
                </div>
                
                <div class="mt-4 pt-4 border-t border-slate-50">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Sudah Bayar</span>
                        <span class="font-bold text-emerald-600">
                            Rp {{ number_format($purchase->payments->sum('amount'), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= DETAIL BARANG ================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            <div class="bg-[#FAF7F2] px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-boxes text-slate-400"></i> Detail Barang
                </h3>
            </div>
            
            <div class="p-6">
                {{-- Data Barang --}}
                @php
                    $barang = $purchase->barang;
                    if(!$barang && $purchase->purchaseRequest) {
                        $barang = $purchase->purchaseRequest->barang;
                    }
                @endphp

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-bold text-slate-500 uppercase border-b border-slate-100">
                                <th class="py-3 pr-4">Nama Barang</th>
                                <th class="py-3 px-4">SKU</th>
                                <th class="py-3 px-4 text-right">Harga Satuan</th>
                                <th class="py-3 px-4 text-center">Qty</th>
                                <th class="py-3 pl-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr>
                                <td class="py-4 pr-4">
                                    <div class="font-bold text-slate-900">{{ $barang->nama_barang ?? 'Item Tidak Ditemukan' }}</div>
                                    @if(isset($barang->kategori))
                                        <div class="text-xs text-slate-500">{{ $barang->kategori->nama_kategori ?? '' }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-4 font-mono text-slate-600">{{ $barang->sku ?? '-' }}</td>
                                <td class="py-4 px-4 text-right text-slate-700">
                                    Rp {{ number_format($purchase->harga_unit, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-block px-2 py-1 bg-slate-100 rounded font-bold text-slate-700">
                                        {{ $purchase->jumlah_po }} {{ $purchase->satuan }}
                                    </span>
                                </td>
                                <td class="py-4 pl-4 text-right font-bold text-slate-900">
                                    Rp {{ number_format($purchase->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ================= INFORMASI ADMINISTRATIF ================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8 p-6">
            <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                Informasi Administratif
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Admin Penerbit --}}
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Admin Penerbit</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg">
                            {{ substr($purchase->user->name ?? 'A', 0, 1) }}
                        </div>
                        <p class="text-lg font-bold text-slate-900">{{ $purchase->user->name ?? 'Admin' }}</p>
                    </div>
                </div>

                {{-- Dibuat Pada --}}
                <div>
                    <p class="text-xs text-slate-500 uppercase font-bold mb-2">Dibuat Pada</p>
                    <p class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <i class="far fa-calendar-alt text-slate-400"></i>
                        {{ $purchase->created_at->format('d M Y H:i') }}
                    </p>
                </div>

                {{-- Keterangan --}}
                @if ($purchase->keterangan)
                    <div class="md:col-span-2 pt-4 border-t border-slate-50">
                        <p class="text-xs text-slate-500 uppercase font-bold mb-2">Keterangan</p>
                        <div class="bg-[#FAF7F2] p-4 rounded-xl border border-slate-100">
                            <p class="text-slate-800 italic">"{{ $purchase->keterangan }}"</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ================= RIWAYAT PEMBAYARAN ================= --}}
        @if($purchase->payments->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
                <div class="bg-[#FAF7F2] px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-history text-slate-400"></i> Riwayat Pembayaran
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Metode</th>
                                <th class="px-6 py-3 text-right">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($purchase->payments as $payment)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 text-slate-600">{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}</td>
                                    <td class="px-6 py-4 font-bold text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-slate-100 rounded text-xs font-bold text-slate-600">{{ $payment->method }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($payment->bukti_pembayaran)
                                            <a href="{{ Storage::url($payment->bukti_pembayaran) }}" target="_blank" class="text-blue-600 hover:underline font-medium text-xs">
                                                <i class="fas fa-paperclip mr-1"></i> Lihat Bukti
                                            </a>
                                        @else
                                            <span class="text-slate-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- ================= DELETE BUTTON ================= --}}
        <div class="mt-8 pt-8 border-t border-slate-200/50">
            <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDeletePurchase(this.form)" class="bg-rose-500 text-white hover:bg-rose-700 font-medium text-sm flex items-center gap-2 transition-colors rounded-md px-3 py-2">
                    <i class="fas fa-trash-alt"></i> Hapus Transaksi Ini
                </button>
            </form>
        </div>

    </div>
</div>

{{-- SCRIPT CONFIRM DELETE --}}
<script>
    function confirmDeletePurchase(form) {
        Swal.fire({
            title: 'Hapus Transaksi?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    }
</script>
@endsection