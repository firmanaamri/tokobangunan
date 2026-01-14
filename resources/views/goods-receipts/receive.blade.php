@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF7F2] via-[#F8F4EE] to-[#FAF7F2] p-6 font-sans">
    <div class="max-w-5xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                    <span>Gudang</span>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span>Penerimaan</span>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span>{{ $purchase->nomor_po }}</span>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Penerimaan Barang</h1>
            </div>
            
            <div class="hidden md:block">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white border border-slate-200 text-slate-600 shadow-sm">
                    <i class="far fa-clock mr-2"></i> {{ date('d M Y') }}
                </span>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm mb-6 flex items-start gap-3">
                <i class="fas fa-exclamation-circle mt-1 text-lg"></i>
                <div>
                    <p class="font-bold">Terjadi Kesalahan</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @php
            $jumlah = $purchase->jumlah_po ?? $purchase->barangMasuk?->jumlah_barang_masuk;
            $barang = $purchase->barang ?? $purchase->barangMasuk?->barang;
        @endphp

        @if (!$jumlah)
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8 text-center max-w-2xl mx-auto">
                <div class="w-20 h-20 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Data PO Tidak Valid</h3>
                <p class="text-slate-500 mb-8">PO ini belum memiliki jumlah barang yang valid. Silakan hubungi admin pembelian untuk memperbarui data PO sebelum melanjutkan inspeksi.</p>
                <a href="{{ route('goods-receipts.ready') }}" class="inline-flex items-center bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-lg shadow-slate-800/20">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>
            </div>
        @else

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden relative group">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-cyan-500"></div>
                    
                    <div class="p-6">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Detail Pesanan</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Nomor PO</p>
                                <p class="text-xl font-bold text-slate-800 font-mono">{{ $purchase->nomor_po }}</p>
                            </div>
                            
                            <div class="pt-4 border-t border-slate-100">
                                <p class="text-xs text-slate-500 mb-1">Barang</p>
                                <p class="text-lg font-bold text-emerald-700">{{ $barang->nama_barang ?? '-' }}</p>
                                <p class="text-sm text-slate-500 font-mono mt-1 bg-slate-100 inline-block px-2 py-0.5 rounded">{{ $barang->sku ?? '-' }}</p>
                            </div>

                            <div class="pt-4 border-t border-slate-100">
                                <p class="text-xs text-slate-500 mb-1">Supplier</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">
                                        {{ substr($purchase->supplier->nama_supplier, 0, 1) }}
                                    </div>
                                    <p class="font-semibold text-slate-700">{{ $purchase->supplier->nama_supplier }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 p-6 border-t border-slate-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-600">Target Penerimaan</span>
                            <span class="text-2xl font-black text-slate-800">{{ $jumlah }} <span class="text-sm font-normal text-slate-500">{{ $purchase->satuan }}</span></span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-2xl border border-blue-100 p-6">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-clipboard-check text-blue-600 text-xl mt-0.5"></i>
                        <div>
                            <h4 class="font-bold text-blue-800 mb-1">Instruksi Inspeksi</h4>
                            <p class="text-sm text-blue-700 leading-relaxed">
                                Pastikan jumlah fisik sesuai dengan surat jalan. Pisahkan barang rusak (rejected) dari barang bagus (accepted). 
                                <br><br>
                                <strong>Total = Accepted + Rejected</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <form action="{{ route('goods-receipts.store', $purchase->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                    @csrf
                    
                    <div class="bg-slate-50/50 px-8 py-5 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-boxes text-emerald-500"></i> Form Hasil Inspeksi
                        </h3>
                    </div>

                    <div class="p-8 space-y-8">
                        
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Input Kuantitas</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div class="bg-emerald-50/30 rounded-xl p-4 border border-emerald-100">
                                    <label class="block text-sm font-bold text-slate-700 mb-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block mr-1"></span>
                                        Diterima Baik (Accepted) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="quantity_accepted" value="{{ old('quantity_accepted', $jumlah) }}" min="0" max="{{ $jumlah }}" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-300 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition-all font-bold text-lg text-slate-800 @error('quantity_accepted') border-red-500 @enderror">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-check text-emerald-500"></i>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-2">Jumlah barang kondisi bagus masuk stok.</p>
                                </div>

                                <div class="bg-rose-50/30 rounded-xl p-4 border border-rose-100">
                                    <label class="block text-sm font-bold text-slate-700 mb-2">
                                        <span class="w-2 h-2 rounded-full bg-rose-500 inline-block mr-1"></span>
                                        Ditolak/Rusak (Rejected) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="quantity_rejected" value="{{ old('quantity_rejected', 0) }}" min="0" max="{{ $jumlah }}" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-300 rounded-xl focus:border-rose-500 focus:ring-4 focus:ring-rose-500/20 transition-all font-bold text-lg text-slate-800 @error('quantity_rejected') border-red-500 @enderror">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-times text-rose-500"></i>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-2">Barang rusak atau tidak sesuai.</p>
                                </div>

                                <div class="md:col-span-2 bg-slate-50 rounded-xl p-4 flex justify-between items-center border border-slate-200">
                                    <span class="text-sm font-medium text-slate-600">Total Fisik Diterima (Received)</span>
                                    <div class="flex items-center gap-3">
                                        <input type="hidden" name="quantity_received" id="quantity_received_hidden" value="{{ $jumlah }}">
                                        <span id="display_total" class="text-xl font-black text-slate-800">{{ $jumlah }}</span>
                                        <span class="text-xs font-bold px-2 py-1 bg-slate-200 text-slate-600 rounded">TARGET: {{ $jumlah }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Bukti & Catatan</h4>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Inspeksi</label>
                                    <textarea name="catatan_inspection" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all text-slate-800 placeholder:text-slate-400 @error('catatan_inspection') border-red-500 @enderror" placeholder="Tuliskan detail kondisi barang, nomor batch (jika ada), atau alasan penolakan...">{{ old('catatan_inspection') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Foto Dokumentasi</label>
                                    <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-emerald-500 hover:bg-emerald-50/10 transition-all group cursor-pointer @error('foto_kerusakan') border-red-500 @enderror">
                                        <input type="file" name="foto_kerusakan" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="foto-input">
                                        
                                        <div class="pointer-events-none">
                                            <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-emerald-100 group-hover:text-emerald-500 transition-colors">
                                                <i class="fas fa-camera text-xl"></i>
                                            </div>
                                            <p class="text-slate-700 font-bold group-hover:text-emerald-700">Klik untuk upload foto</p>
                                            <p class="text-xs text-slate-400 mt-1">Wajib jika ada barang rejected. Max 2MB.</p>
                                        </div>
                                        <div id="file-name" class="text-sm font-bold text-emerald-600 mt-3 hidden bg-emerald-50 inline-block px-3 py-1 rounded-full border border-emerald-100"></div>
                                    </div>
                                    @error('foto_kerusakan')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-8 py-5 flex justify-between items-center border-t border-slate-100">
                        <a href="{{ route('goods-receipts.ready') }}" class="text-slate-500 hover:text-slate-800 font-semibold text-sm transition-colors">
                            Batal
                        </a>
                        <button type="button" onclick="confirmCreate(this.closest('form'), 'Penerimaan Barang')" class="bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-check-circle mr-2"></i> Konfirmasi Penerimaan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        // Logic for auto-calculation and validation
        document.addEventListener('DOMContentLoaded', function() {
            const jumlahPO = {{ $jumlah }};
            // Note: quantity_received is now calculated, not manual
            const qtyReceivedHidden = document.getElementById('quantity_received_hidden');
            const qtyAccepted = document.querySelector('input[name="quantity_accepted"]');
            const qtyRejected = document.querySelector('input[name="quantity_rejected"]');
            const displayTotal = document.getElementById('display_total');

            function updateTotal() {
                const accepted = parseInt(qtyAccepted.value) || 0;
                const rejected = parseInt(qtyRejected.value) || 0;
                const total = accepted + rejected;

                // Update Visual Display
                displayTotal.innerText = total;
                qtyReceivedHidden.value = total;

                // Visual Validation Feedback
                if (total === jumlahPO) {
                    displayTotal.classList.remove('text-red-600', 'text-amber-500');
                    displayTotal.classList.add('text-emerald-600');
                    qtyAccepted.setCustomValidity('');
                    qtyRejected.setCustomValidity('');
                } else if (total > jumlahPO) {
                    displayTotal.classList.remove('text-emerald-600', 'text-amber-500');
                    displayTotal.classList.add('text-red-600');
                    qtyAccepted.setCustomValidity(`Total melebihi PO (${jumlahPO})`);
                } else {
                    displayTotal.classList.remove('text-emerald-600', 'text-red-600');
                    displayTotal.classList.add('text-amber-500');
                    qtyAccepted.setCustomValidity(''); // Allow partial receipt? Usually logic depends. Assuming must match for now based on your old code
                }
            }

            // File Upload Styling Logic
            const fileInput = document.getElementById('foto-input');
            const fileNameDisplay = document.getElementById('file-name');
            
            fileInput.addEventListener('change', function(){
                if(this.files && this.files.length > 0){
                    fileNameDisplay.textContent = '✓ ' + this.files[0].name;
                    fileNameDisplay.classList.remove('hidden');
                } else {
                    fileNameDisplay.classList.add('hidden');
                }
            });

            qtyAccepted.addEventListener('input', updateTotal);
            qtyRejected.addEventListener('input', updateTotal);
            
            // Init check
            updateTotal();
        });
        </script>
        @endif
    </div>
</div>
@endsection