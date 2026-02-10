@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="min-h-screen bg-white p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Review Pengajuan Pembelian</h1>
                <p class="text-slate-600 mt-2 font-mono text-lg">{{ $purchaseRequest->nomor_pr }}</p>
            </div>
            <a href="{{ route('purchase-approvals.index') }}" class="text-slate-600 hover:text-slate-900">
                <i class="fas fa-arrow-left text-2xl"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4"><i class="fas fa-box mr-2 text-emerald-500"></i>Detail Pengajuan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border-l-4 border-emerald-500 pl-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Barang</p>
                            <p class="text-lg font-bold text-slate-900 mt-1">{{ $purchaseRequest->barang->nama_barang }}</p>
                            <p class="text-sm text-slate-600">{{ $purchaseRequest->barang->kategori->nama_kategori ?? '-' }}</p>
                        </div>

                        <div class="border-l-4 border-cyan-500 pl-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Supplier (Pengajuan)</p>
                            <p class="text-lg font-bold text-slate-900 mt-1">{{ $purchaseRequest->supplier->nama_supplier }}</p>
                        </div>

                        <div class="border-l-4 border-amber-500 pl-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Jumlah Diminta</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($purchaseRequest->jumlah_diminta) }}<span class="text-sm text-slate-500 ml-2">{{ $purchaseRequest->satuan }}</span></p>
                        </div>

                        <div class="border-l-4 border-purple-500 pl-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Status</p>
                            <p class="mt-1">
                                @php
                                    $statusClass = match($purchaseRequest->status) {
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'approved' => 'bg-emerald-100 text-emerald-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        default => 'bg-slate-100 text-slate-800'
                                    };
                                @endphp
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $statusClass }}">
                                    {{ ucfirst($purchaseRequest->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    @if($purchaseRequest->catatan_request)
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <p class="text-xs font-semibold text-slate-500 uppercase mb-2">Catatan dari Pengajuan</p>
                            <div class="bg-slate-50 p-3 rounded-lg">
                                <p class="text-slate-700">{{ $purchaseRequest->catatan_request }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-4"><i class="fas fa-user mr-2 text-emerald-500"></i>Informasi Pengaju</h2>
                    
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($purchaseRequest->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $purchaseRequest->user->name }}</p>
                            <p class="text-sm text-slate-600">{{ $purchaseRequest->user->email }}</p>
                            <p class="text-xs text-slate-500">Diajukan: {{ $purchaseRequest->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4"><i class="fas fa-gavel mr-2 text-emerald-500"></i>Keputusan Persetujuan</h2>

                    <div x-data="{ tab: 'approve' }" class="space-y-4">
                        <div class="flex space-x-2 border-b border-slate-200">
                            <button @click="tab = 'approve'" :class="tab === 'approve' ? 'border-b-2 border-emerald-500 text-emerald-600' : 'text-slate-600'"
                                class="px-4 py-2 font-semibold transition-colors focus:outline-none">
                                <i class="fas fa-check-circle mr-2"></i>Setujui PR
                            </button>
                            <button @click="tab = 'reject'" :class="tab === 'reject' ? 'border-b-2 border-red-500 text-red-600' : 'text-slate-600'"
                                class="px-4 py-2 font-semibold transition-colors focus:outline-none">
                                <i class="fas fa-times-circle mr-2"></i>Tolak PR
                            </button>
                        </div>

                        <div x-show="tab === 'approve'" class="space-y-4 pt-4" x-transition>
                            <form id="form-approve" method="POST" action="{{ route('purchase-approvals.approve', $purchaseRequest) }}">
                                @csrf
                                @method('POST')

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">
                                        <i class="fas fa-tag mr-2 text-emerald-500"></i>Harga Deal (Satuan) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-slate-500 font-bold">Rp</span>
                                        <input type="number" name="harga_deal" required
                                            value="{{ old('harga_deal', $purchaseRequest->barang->harga_beli_terakhir ?? 0) }}"
                                            class="w-full rounded-lg border border-slate-300 pl-10 pr-4 py-2 focus:border-emerald-500 focus:ring-emerald-200 font-bold text-slate-800"
                                            placeholder="Masukkan harga deal...">
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">Harga ini akan menjadi patokan harga pasar terbaru.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">
                                        <i class="fas fa-truck mr-2 text-emerald-500"></i>Supplier Final <span class="text-red-500">*</span>
                                    </label>
                                    <select name="supplier_id" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-emerald-500 focus:ring-emerald-200 bg-white">
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" @selected($supplier->id == $purchaseRequest->supplier_id)>
                                                {{ $supplier->nama_supplier }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-slate-500 mt-1">Anda bisa mengganti supplier jika stok kosong atau harga lebih murah.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">
                                        <i class="fas fa-sticky-note mr-2 text-emerald-500"></i>Catatan Approval (Opsional)
                                    </label>
                                    <textarea name="catatan_approval" rows="3" placeholder="Tambahkan catatan khusus untuk bagian pembelian..." 
                                        class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-emerald-500 focus:ring-emerald-200"></textarea>
                                </div>

                                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm mb-4">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Info:</strong> Setelah diklik, sistem akan otomatis membuat <strong>Purchase Order (PO)</strong>.
                                </div>

                                <button type="button" onclick="confirmApprove(this)" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 shadow-md">
                                    <i class="fas fa-check-circle mr-2"></i>Setujui PR & Buat PO
                                </button>
                            </form>
                        </div>

                        <div x-show="tab === 'reject'" class="space-y-4 pt-4" x-transition style="display: none;">
                            <form id="form-reject" method="POST" action="{{ route('purchase-approvals.reject', $purchaseRequest) }}">
                                @csrf
                                @method('POST')

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">
                                        <i class="fas fa-comment mr-2 text-red-500"></i>Alasan Penolakan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="catatan_approval" rows="3" required placeholder="Jelaskan alasan penolakan PR ini agar staff paham..." 
                                        class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-red-500 focus:ring-red-200"></textarea>
                                </div>

                                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm mb-4">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Perhatian:</strong> Penolakan bersifat permanen. Staff harus membuat PR baru jika ingin mengajukan ulang.
                                </div>

                                <button type="button" class="w-full bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 shadow-md"
                                    onclick="confirmReject(this)">
                                    <i class="fas fa-times-circle mr-2"></i>Tolak PR
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-900 mb-4"><i class="fas fa-history mr-2 text-emerald-500"></i>Riwayat</h3>
                    
                    <div class="flex flex-col space-y-4">
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white shadow-sm">
                                    <i class="fas fa-file-alt text-xs"></i>
                                </div>
                                <div class="w-1 bg-slate-200 h-10"></div>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm">PR Dibuat</p>
                                <p class="text-xs text-slate-500">{{ $purchaseRequest->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 bg-amber-400 rounded-full flex items-center justify-center text-white shadow-sm">
                                    <i class="fas fa-hourglass-half text-xs"></i>
                                </div>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm">Menunggu Persetujuan</p>
                                <p class="text-xs text-slate-500">Saat ini</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-900 mb-4"><i class="fas fa-info-circle mr-2 text-emerald-500"></i>Ringkasan</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between pb-2 border-b border-slate-100">
                            <span class="text-slate-600">Nomor PR:</span>
                            <span class="font-mono font-bold text-slate-900">{{ $purchaseRequest->nomor_pr }}</span>
                        </div>
                        <div class="flex justify-between pb-2 border-b border-slate-100">
                            <span class="text-slate-600">Kategori:</span>
                            <span class="font-bold text-slate-900">{{ $purchaseRequest->barang->kategori->nama_kategori ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Est. Total:</span>
                            <span class="font-bold text-slate-900">
                                Rp {{ number_format(($purchaseRequest->barang->harga_beli_terakhir ?? 0) * $purchaseRequest->jumlah_diminta) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 shadow-sm">
                    <p class="text-blue-800 text-xs leading-relaxed">
                        <i class="fas fa-lightbulb mr-2 text-blue-600"></i>
                        <strong>Tips:</strong> Pastikan Anda sudah menghubungi supplier untuk mendapatkan harga terbaik sebelum menyetujui permintaan ini.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmApprove(button) {
        // 1. Cek Validasi HTML5 Dulu (Required fields)
        const form = button.closest('form');
        if (!form.reportValidity()) {
            return; // Stop kalau ada field required yang kosong
        }

        // 2. Tampilkan SweetAlert
        Swal.fire({
            title: 'Konfirmasi Approval',
            text: "Apakah harga deal dan supplier sudah benar? PO akan otomatis dibuat.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6', // Warna Biru
            cancelButtonColor: '#d33',    // Warna Merah
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Submit form manual
            }
        });
    }

    function confirmReject(button) {
        // 1. Cek Validasi HTML5 Dulu (Wajib isi alasan)
        const form = button.closest('form');
        if (!form.reportValidity()) {
            return;
        }

        // 2. Tampilkan SweetAlert
        Swal.fire({
            title: 'Tolak Permintaan?',
            text: "Tindakan ini tidak bisa dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',    // Warna Merah
            cancelButtonColor: '#3085d6', // Warna Biru
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endsection