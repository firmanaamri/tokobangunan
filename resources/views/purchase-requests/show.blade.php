@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Detail Pengajuan Pembelian</h1>
                <p class="text-slate-600 mt-2 font-mono text-lg">{{ $purchaseRequest->nomor_pr }}</p>
            </div>
           <a href="{{ route('purchase-requests.index') }}" class="group flex items-center gap-2 px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-medium text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Card -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4"><i class="fas fa-package mr-2 text-emerald-500"></i>Informasi Barang</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border-l-4 border-emerald-500 pl-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Nama Barang</p>
                            <p class="text-lg font-bold text-slate-900">{{ $purchaseRequest->barang->nama_barang }}</p>
                            <p class="text-sm text-slate-600">Kategori: {{ $purchaseRequest->barang->kategori->nama_kategori ?? '-' }}</p>
                        </div>
                        <div class="border-l-4 border-cyan-500 pl-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Supplier</p>
                            <p class="text-lg font-bold text-slate-900">{{ $purchaseRequest->supplier->nama_supplier }}</p>
                            <p class="text-sm text-slate-600">{{ $purchaseRequest->supplier->alamat ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t border-slate-200">
                        <div class="border-l-4 border-amber-500 pl-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Jumlah Diminta</p>
                            <p class="text-2xl font-bold text-slate-900">{{ number_format($purchaseRequest->jumlah_diminta) }}<span class="text-sm text-slate-500 ml-2">{{ $purchaseRequest->satuan }}</span></p>
                        </div>
                        <div class="border-l-4 border-purple-500 pl-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Status PR</p>
                            @php
                                $statusColor = [
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'approved' => 'bg-emerald-100 text-emerald-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'completed' => 'bg-blue-100 text-blue-800',
                                ][$purchaseRequest->status] ?? 'bg-slate-100 text-slate-800';
                            @endphp
                            <span class="inline-block mt-1 px-4 py-2 rounded-full text-sm font-bold {{ $statusColor }}">
                                {{ ucfirst($purchaseRequest->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Catatan Request -->
                @if($purchaseRequest->catatan_request)
                    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-slate-900 mb-4"><i class="fas fa-sticky-note mr-2 text-emerald-500"></i>Catatan Pengajuan</h2>
                        <p class="text-slate-700 leading-relaxed">{{ $purchaseRequest->catatan_request }}</p>
                    </div>
                @endif

                <!-- Timeline -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-4"><i class="fas fa-timeline mr-2 text-emerald-500"></i>Riwayat</h2>
                    
                    <div class="space-y-4">
                        <!-- Created -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-check text-sm"></i>
                                </div>
                                <div class="w-1 bg-slate-300 h-12"></div>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">Pengajuan Dibuat</p>
                                <p class="text-sm text-slate-600">{{ $purchaseRequest->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $purchaseRequest->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Approved -->
                        @if($purchaseRequest->status !== 'pending')
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 bg-{{ $purchaseRequest->status === 'approved' ? 'emerald' : 'red' }}-500 rounded-full flex items-center justify-center text-white">
                                        <i class="fas fa-{{ $purchaseRequest->status === 'approved' ? 'check' : 'times' }} text-sm"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $purchaseRequest->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</p>
                                    <p class="text-sm text-slate-600">{{ $purchaseRequest->approver->name ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">{{ $purchaseRequest->tanggal_approval?->format('d M Y H:i') ?? '-' }}</p>
                                    @if($purchaseRequest->catatan_approval)
                                        <p class="text-sm text-slate-700 mt-1"><strong>Catatan:</strong> {{ $purchaseRequest->catatan_approval }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Side Info -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-900 mb-4 text-center">Status Approval</h3>
                    
                    @php
                        $stepColor = [
                            'pending' => ['text-amber-600', 'bg-amber-100'],
                            'approved' => ['text-emerald-600', 'bg-emerald-100'],
                            'rejected' => ['text-red-600', 'bg-red-100'],
                            'completed' => ['text-blue-600', 'bg-blue-100'],
                        ][$purchaseRequest->status];
                    @endphp
                    
                    <div class="text-center py-6 rounded-lg {{ $stepColor[1] }}">
                        <p class="text-sm font-semibold text-slate-600">Status Saat Ini</p>
                        <p class="text-3xl font-bold {{ $stepColor[0] }} mt-2">{{ ucfirst($purchaseRequest->status) }}</p>
                    </div>
                </div>

                <!-- Dates -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-900 mb-4"><i class="fas fa-calendar mr-2 text-emerald-500"></i>Tanggal</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="border-b border-slate-200 pb-3">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Dibuat</p>
                            <p class="font-semibold text-slate-900">{{ $purchaseRequest->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-slate-500">{{ $purchaseRequest->created_at->format('H:i') }}</p>
                        </div>
                        @if($purchaseRequest->tanggal_approval)
                            <div class="border-b border-slate-200 pb-3">
                                <p class="text-xs font-semibold text-slate-500 uppercase">{{ $purchaseRequest->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</p>
                                <p class="font-semibold text-slate-900">{{ $purchaseRequest->tanggal_approval->format('d M Y') }}</p>
                                <p class="text-xs text-slate-500">{{ $purchaseRequest->tanggal_approval->format('H:i') }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase">Terakhir diubah</p>
                            <p class="font-semibold text-slate-900">{{ $purchaseRequest->updated_at->format('d M Y') }}</p>
                            <p class="text-xs text-slate-500">{{ $purchaseRequest->updated_at->format('H:i') }}</p>
                        </div>
                        
                        <!-- Payment Terms -->
                        <div class="border-t border-slate-200 pt-3">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Payment Term</p>
                            <p class="font-semibold text-slate-900">{{ $purchaseRequest->payment_term ? $purchaseRequest->payment_term . ' hari' : '-' }}</p>
                        </div>

                        <div class="border-t border-slate-200 pt-3">
                            <p class="text-xs font-semibold text-slate-500 uppercase">Tanggal Jatuh Tempo (PR)</p>
                            <p class="font-semibold text-slate-900">{{ $purchaseRequest->due_date ? $purchaseRequest->due_date->format('d M Y') : '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- PIC -->
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-900 mb-4"><i class="fas fa-user mr-2 text-emerald-500"></i>PIC</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Pengaju</p>
                            <p class="font-semibold text-slate-900">{{ $purchaseRequest->user->name }}</p>
                            <p class="text-xs text-slate-600">{{ $purchaseRequest->user->email }}</p>
                        </div>
                        @if($purchaseRequest->approver)
                            <div class="border-t border-slate-200 pt-3">
                                <p class="text-xs font-semibold text-slate-500 uppercase mb-1">Approver</p>
                                <p class="font-semibold text-slate-900">{{ $purchaseRequest->approver->name }}</p>
                                <p class="text-xs text-slate-600">{{ $purchaseRequest->approver->email }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if($purchaseRequest->status === 'pending' && $purchaseRequest->user_id === auth()->id())
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('purchase-requests.edit', $purchaseRequest) }}" class="w-full bg-slate-500 hover:bg-slate-600 text-white font-bold py-2 px-4 rounded-lg text-center transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <form id="deletePRForm{{ $purchaseRequest->id }}" method="POST" action="{{ route('purchase-requests.destroy', $purchaseRequest) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDeletePR('deletePRForm{{ $purchaseRequest->id }}')" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
