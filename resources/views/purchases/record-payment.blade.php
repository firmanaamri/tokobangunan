@extends('layouts.app')

@section('content')
{{-- BACKGROUND: Gradasi krem (Cream) --}}
<div class="min-h-screen bg-white p-6 font-sans">
    <div class="max-w-5xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                    <span>Pembelian</span>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span>{{ $purchase->nomor_po }}</span>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Catat Pembayaran</h1>
            </div>
            <a href="{{ route('purchases.show', $purchase->id) }}" class="group flex items-center gap-2 px-5 py-2.5 bg-white/80 backdrop-blur-sm border border-slate-200 text-slate-600 rounded-xl hover:border-slate-300 hover:text-slate-800 transition-all shadow-sm">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                <span class="font-semibold">Kembali ke Detail</span>
            </a>
        </div>

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg shadow-sm mb-6 flex items-start gap-3">
                <i class="fas fa-exclamation-circle mt-1"></i>
                <div>
                    <p class="font-bold">Terjadi Kesalahan</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-blue-400 to-blue-600"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-tag text-xl"></i>
                    </div>
                </div>
                <p class="text-slate-500 text-sm font-medium uppercase tracking-wider mb-1">Total Tagihan</p>
                <h3 class="text-2xl font-bold text-slate-800">Rp {{ number_format($purchase->total_harga, 0, ',', '.') }}</h3>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-emerald-400 to-emerald-600"></div>
                @php $alreadyPaid = \App\Models\Payment::where('purchase_id', $purchase->id)->sum('amount'); @endphp
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </div>
                <p class="text-slate-500 text-sm font-medium uppercase tracking-wider mb-1">Sudah Dibayar</p>
                <h3 class="text-2xl font-bold text-emerald-600">Rp {{ number_format($alreadyPaid, 0, ',', '.') }}</h3>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-rose-400 to-rose-600"></div>
                @php
                    $remaining = $purchase->total_harga - $alreadyPaid;
                    if($remaining < 0) { $remaining = 0; }
                @endphp
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-rose-50 rounded-xl text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                        <i class="fas fa-exclamation-circle text-xl"></i>
                    </div>
                </div>
                <p class="text-slate-500 text-sm font-medium uppercase tracking-wider mb-1">Sisa Kekurangan</p>
                <h3 class="text-2xl font-bold text-rose-600">Rp {{ number_format($remaining, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                    <div class="bg-[#FAF7F2] px-8 py-4 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-pen-to-square text-emerald-500"></i> Form Pembayaran
                        </h3>
                    </div>
                    
                    <form action="{{ route('purchases.storePayment', $purchase->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Pembayaran (Rp) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-bold">Rp</span>
                                    </div>
                                    <input type="number" name="jumlah_bayar" id="jumlah_bayar" value="{{ old('jumlah_bayar', $remaining) }}" step="0.01" min="0.01" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-semibold text-slate-800 @error('jumlah_bayar') border-red-500 @enderror">
                                </div>
                                @error('jumlah_bayar') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Bayar <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', date('Y-m-d\TH:i')) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all text-slate-800 @error('tanggal_pembayaran') border-red-500 @enderror">
                                @error('tanggal_pembayaran') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Metode Pembayaran <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="metode_pembayaran" value="Transfer Bank" class="peer sr-only" {{ old('metode_pembayaran') == 'Transfer Bank' ? 'checked' : '' }}>
                                        <div class="rounded-xl border-2 border-slate-200 p-4 hover:bg-slate-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 transition-all flex flex-col items-center justify-center gap-2 text-center h-full">
                                            <i class="fas fa-university text-2xl mb-1"></i>
                                            <span class="text-sm font-bold">Transfer Bank</span>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer">
                                        <input type="radio" name="metode_pembayaran" value="Tunai" class="peer sr-only" {{ old('metode_pembayaran') == 'Tunai' ? 'checked' : '' }}>
                                        <div class="rounded-xl border-2 border-slate-200 p-4 hover:bg-slate-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 transition-all flex flex-col items-center justify-center gap-2 text-center h-full">
                                            <i class="fas fa-money-bill-wave text-2xl mb-1"></i>
                                            <span class="text-sm font-bold">Tunai</span>
                                        </div>
                                    </label>
                                </div>
                                @error('metode_pembayaran') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Keterangan / Catatan</label>
                                <textarea name="keterangan" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all text-slate-800 placeholder:text-slate-400 @error('keterangan') border-red-500 @enderror" placeholder="Contoh: Pembayaran termin pertama...">{{ old('keterangan') }}</textarea>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Bukti Pembayaran</label>
                                <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-emerald-500 hover:bg-slate-50/50 transition-all group cursor-pointer @error('bukti_pembayaran') border-red-500 @enderror">
                                    <input type="file" name="bukti_pembayaran" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="bukti-input">
                                    <div class="pointer-events-none transition-transform group-hover:scale-105 duration-200">
                                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-cloud-upload-alt text-xl"></i>
                                        </div>
                                        <p class="text-slate-700 font-bold">Upload Bukti Bayar</p>
                                        <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, PDF (Max 5MB)</p>
                                    </div>
                                    <div id="file-name" class="text-sm font-bold text-emerald-600 mt-3 hidden bg-emerald-50 inline-block px-3 py-1 rounded-full border border-emerald-100"></div>
                                </div>
                                @error('bukti_pembayaran') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                            <i class="fas fa-check-circle mr-2"></i> Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-1">
                @php $payments = \App\Models\Payment::where('purchase_id', $purchase->id)->latest('paid_at')->get(); @endphp
                
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden h-full flex flex-col">
                    <div class="bg-[#FAF7F2] px-6 py-4 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-history text-blue-500"></i> Riwayat
                        </h3>
                    </div>

                    <div class="flex-1 overflow-y-auto max-h-[600px] p-0">
                        @if ($payments->count() > 0)
                            <div class="divide-y divide-slate-100">
                                @foreach ($payments as $payment)
                                    <div class="p-5 hover:bg-slate-50 transition-colors">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-xs font-bold uppercase text-slate-400 tracking-wide">{{ $payment->paid_at->format('d M Y') }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Lunas
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-lg font-bold text-slate-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                                <p class="text-xs text-slate-500 flex items-center gap-1 mt-1">
                                                    <i class="fas fa-wallet"></i> {{ $payment->method }}
                                                </p>
                                            </div>
                                            @if($payment->bukti_pembayaran)
                                                <a href="{{ Storage::url($payment->bukti_pembayaran) }}" target="_blank" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors" title="Lihat Bukti">
                                                    <i class="fas fa-paperclip"></i>
                                                </a>
                                            @endif
                                        </div>
                                        @if($payment->metadata)
                                            <p class="text-xs text-slate-400 mt-2 italic bg-slate-50 p-2 rounded border border-slate-100">
                                                "{{ is_array($payment->metadata) ? ($payment->metadata['keterangan'] ?? '-') : $payment->metadata }}"
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-48 text-center p-6 text-slate-400">
                                <i class="fas fa-receipt text-4xl mb-3 opacity-30"></i>
                                <p class="text-sm">Belum ada riwayat pembayaran</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="bg-slate-50 border-t border-slate-200 p-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Total Terbayar</span>
                            <span class="font-bold text-emerald-600">Rp {{ number_format($alreadyPaid, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Handle file upload (robust)
    (function(){
        const buktInput = document.getElementById('bukti-input');
        const fileName = document.getElementById('file-name');
        
        if (!buktInput || !fileName) return;

        function updateFileName(name) {
            if(name) {
                fileName.innerHTML = '<i class="fas fa-check mr-1"></i> ' + name;
                fileName.classList.remove('hidden');
            } else {
                fileName.classList.add('hidden');
            }
        }

        if (buktInput.files && buktInput.files.length > 0) {
            updateFileName(buktInput.files[0].name);
        }

        buktInput.addEventListener('change', function(e) {
            if (this.files && this.files.length > 0) {
                updateFileName(this.files[0].name);
            } else {
                updateFileName(null);
            }
        });

        const dropZone = buktInput.parentElement;
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) { e.preventDefault(); e.stopPropagation(); dropZone.classList.add('border-emerald-500', 'bg-emerald-50'); }
        function unhighlight(e) { e.preventDefault(); e.stopPropagation(); dropZone.classList.remove('border-emerald-500', 'bg-emerald-50'); }

        dropZone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                buktInput.files = files;
                updateFileName(files[0].name);
            }
        });
    })();
</script>
@endsection