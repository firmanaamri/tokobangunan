@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF7F2] via-[#F8F4EE] to-[#FAF7F2] p-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
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
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- PR Details -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4"><i class="fas fa-box mr-2 text-emerald-500"></i>Detail Pengajuan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border-l-4 border-emerald-500 pl-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Barang</p>
                            <p class="text-lg font-bold text-slate-900 mt-1">{{ $purchaseRequest->barang->nama_barang }}</p>
                            <p class="text-sm text-slate-600">{{ $purchaseRequest->barang->kategori->nama_kategori ?? '-' }}</p>
                        </div>

                        <div class="border-l-4 border-cyan-500 pl-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Supplier</p>
                            <p class="text-lg font-bold text-slate-900 mt-1">{{ $purchaseRequest->supplier->nama_supplier }}</p>
                        </div>

                        <div class="border-l-4 border-amber-500 pl-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Jumlah Diminta</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($purchaseRequest->jumlah_diminta) }}<span class="text-sm text-slate-500 ml-2">{{ $purchaseRequest->satuan }}</span></p>
                        </div>

                        <div class="border-l-4 border-purple-500 pl-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Status</p>
                            <p class="mt-1"><span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">{{ ucfirst($purchaseRequest->status) }}</span></p>
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

                <!-- Requestor Info -->
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

                <!-- Approval Form -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4"><i class="fas fa-check-double mr-2 text-emerald-500"></i>Keputusan Persetujuan</h2>

                    <!-- Tabs for Approve/Reject -->
                    <div x-data="{ tab: 'approve' }" class="space-y-4">
                        <div class="flex space-x-2 border-b border-slate-200">
                            <button @click="tab = 'approve'" :class="tab === 'approve' ? 'border-b-2 border-emerald-500 text-emerald-600' : 'text-slate-600'"
                                class="px-4 py-2 font-semibold transition-colors">
                                <i class="fas fa-check-circle mr-2"></i>Setujui PR
                            </button>
                            <button @click="tab = 'reject'" :class="tab === 'reject' ? 'border-b-2 border-red-500 text-red-600' : 'text-slate-600'"
                                class="px-4 py-2 font-semibold transition-colors">
                                <i class="fas fa-times-circle mr-2"></i>Tolak PR
                            </button>
                        </div>

                        <!-- Approve Form -->
                        <div x-show="tab === 'approve'" class="space-y-4 pt-4">
                            <form method="POST" action="{{ route('purchase-approvals.approve', $purchaseRequest) }}">
                                @csrf

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">
                                        <i class="fas fa-sticky-note mr-2 text-emerald-500"></i>Catatan Approval (Optional)
                                    </label>
                                    <textarea name="catatan_approval" rows="3" placeholder="Tambahkan catatan atau kondisi khusus..." 
                                        class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200"></textarea>
                                </div>

                                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm mb-4">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Info:</strong> Saat Anda klik "Setujui", sistem otomatis akan membuat PO (Purchase Order) dengan nomor yang dihasilkan sistem.
                                </div>

                                <button type="button" onclick="confirmApprovePR(this.closest('form'))" class="w-full bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300">
                                    <i class="fas fa-check-circle mr-2"></i>Setujui PR & Buat PO
                                </button>
                            </form>
                        </div>

                        <!-- Reject Form -->
                        <div x-show="tab === 'reject'" class="space-y-4 pt-4">
                            <form method="POST" action="{{ route('purchase-approvals.reject', $purchaseRequest) }}">
                                @csrf

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">
                                        <i class="fas fa-comment mr-2 text-red-500"></i>Alasan Penolakan *
                                    </label>
                                    <textarea name="catatan_approval" rows="3" required placeholder="Jelaskan alasan penolakan PR ini..." 
                                        class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-red-400 focus:ring-red-200"></textarea>
                                    <p class="text-xs text-slate-500 mt-1">Alasan akan dikirim ke staff yang membuat PR</p>
                                </div>

                                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm mb-4">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Perhatian:</strong> Penolakan bersifat permanent. Staff harus membuat PR baru jika ingin resubmit.
                                </div>

                                <button type="button" class="w-full bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300"
                                    onclick="confirmRejectPR(this.closest('form'))">
                                    <i class="fas fa-times-circle mr-2"></i>Tolak PR
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Timeline -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-900 mb-4"><i class="fas fa-timeline mr-2 text-emerald-500"></i>Riwayat</h3>
                    
                    <div class="flex flex-col space-y-4">
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                                <div class="w-1 bg-slate-200 h-12"></div>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm">Dibuat</p>
                                <p class="text-xs text-slate-600">{{ $purchaseRequest->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 bg-slate-400 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-hourglass-half text-xs"></i>
                                </div>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm">Menunggu Persetujuan</p>
                                <p class="text-xs text-slate-600">Anda sebagai approver</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-900 mb-4"><i class="fas fa-info-circle mr-2 text-emerald-500"></i>Summary</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between pb-2 border-b border-slate-200">
                            <span class="text-slate-600">Nomor PR:</span>
                            <span class="font-mono font-bold text-slate-900">{{ $purchaseRequest->nomor_pr }}</span>
                        </div>
                        <div class="flex justify-between pb-2 border-b border-slate-200">
                            <span class="text-slate-600">Kategori:</span>
                            <span class="font-bold text-slate-900">{{ $purchaseRequest->barang->kategori->nama_kategori ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between pb-2 border-b border-slate-200">
                            <span class="text-slate-600">Total Qty:</span>
                            <span class="font-bold text-slate-900">{{ number_format($purchaseRequest->jumlah_diminta) }} {{ $purchaseRequest->satuan }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Priority:</span>
                            <span class="inline-block px-2 py-1 rounded text-xs font-bold bg-amber-100 text-amber-800">Normal</span>
                        </div>
                    </div>
                </div>

                <!-- Documentation Link -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-blue-800 text-xs"><i class="fas fa-lightbulb mr-2"></i><strong>Tips:</strong> Selalu review catatan pengajuan sebelum approve untuk memastikan spesifikasi sesuai kebutuhan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
