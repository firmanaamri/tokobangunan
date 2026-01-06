@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-slate-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Catat Pembayaran</h1>
            <p class="text-slate-600 mt-2">{{ $purchase->nomor_po }}</p>
        </div>

        <!-- Alert Messages -->
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Summary Card -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <p class="text-sm text-slate-600 uppercase font-bold mb-2">Total Harga</p>
                <p class="text-3xl font-bold text-slate-900">Rp {{ number_format($purchase->total_harga, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <p class="text-sm text-slate-600 uppercase font-bold mb-2">Sudah Dibayar</p>
                <p class="text-3xl font-bold text-emerald-600">Rp {{ number_format(\App\Models\Payment::where('purchase_id', $purchase->id)->sum('amount'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
                <p class="text-sm text-slate-600 uppercase font-bold mb-2">Sisa Pembayaran</p>
                <p class="text-3xl font-bold text-red-600">Rp {{ number_format($purchase->total_harga - \App\Models\Payment::where('purchase_id', $purchase->id)->sum('amount'), 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Form Pembayaran -->
        <form action="{{ route('purchases.storePayment', $purchase->id) }}" method="POST" class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200" enctype="multipart/form-data">
            @csrf

            <div class="p-8 space-y-6">
                <!-- Jumlah Bayar -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Jumlah Pembayaran (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}" step="0.01" min="0.01" placeholder="0" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('jumlah_bayar') border-red-500 @enderror">
                    @error('jumlah_bayar')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Metode Pembayaran -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Metode Pembayaran <span class="text-red-500">*</span></label>
                    <select name="metode_pembayaran" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('metode_pembayaran') border-red-500 @enderror">
                        <option value="">-- Pilih Metode --</option>
                        <option value="Transfer Bank" @selected(old('metode_pembayaran') == 'Transfer Bank')>Transfer Bank</option>
                        <option value="Cek" @selected(old('metode_pembayaran') == 'Cek')>Cek</option>
                        <option value="Tunai" @selected(old('metode_pembayaran') == 'Tunai')>Tunai</option>
                        <option value="Kartu Kredit" @selected(old('metode_pembayaran') == 'Kartu Kredit')>Kartu Kredit</option>
                        <option value="E-Wallet" @selected(old('metode_pembayaran') == 'E-Wallet')>E-Wallet</option>
                        <option value="Lainnya" @selected(old('metode_pembayaran') == 'Lainnya')>Lainnya</option>
                    </select>
                    @error('metode_pembayaran')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Pembayaran -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('tanggal_pembayaran') border-red-500 @enderror">
                    @error('tanggal_pembayaran')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Referensi Pembayaran -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Referensi Pembayaran (No. Rekening/Cek)</label>
                    <input type="text" name="referensi" value="{{ old('referensi') }}" placeholder="Contoh: 1234567890" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('referensi') border-red-500 @enderror">
                    @error('referensi')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="4" placeholder="Catatan tambahan tentang pembayaran" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bukti Pembayaran -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Bukti Pembayaran (Resi/Screenshot)</label>
                    <div class="relative border-2 border-dashed border-slate-300 rounded-lg p-8 text-center hover:border-emerald-500 transition @error('bukti_pembayaran') border-red-500 @enderror">
                        <input type="file" name="bukti_pembayaran" accept="image/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" id="bukti-input">
                        <div class="pointer-events-none">
                            <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-2"></i>
                            <p class="text-slate-600 font-semibold">Klik untuk upload atau drag & drop</p>
                            <p class="text-sm text-slate-500 mt-1">Format: JPG, PNG, PDF (Maksimal 5MB)</p>
                        </div>
                        <div id="file-name" class="text-sm text-emerald-600 font-semibold mt-2"></div>
                    </div>
                    @error('bukti_pembayaran')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="bg-slate-100 px-8 py-4 flex gap-4">
                <button type="submit" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                    <i class="fas fa-check mr-2"></i>Catat Pembayaran
                </button>
                <a href="{{ route('purchases.show', $purchase->id) }}" class="bg-slate-500 hover:bg-slate-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>

        <!-- Payment History -->
        @php
            $payments = \App\Models\Payment::where('purchase_id', $purchase->id)->get();
        @endphp

        @if ($payments->count() > 0)
            <div class="mt-8 bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-900">Riwayat Pembayaran</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Metode</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Referensi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($payments as $payment)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $payment->paid_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $payment->method }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $payment->reference ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Berhasil</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

<script>
    // Handle file upload
    const buktInput = document.getElementById('bukti-input');
    const fileName = document.getElementById('file-name');
    
    buktInput.addEventListener('change', function(e) {
        if (this.files.length > 0) {
            fileName.textContent = '✓ File: ' + this.files[0].name;
        } else {
            fileName.textContent = '';
        }
    });

    // Handle drag and drop
    const dropZone = buktInput.parentElement;
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-emerald-500', 'bg-emerald-50');
    });
    
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-emerald-500', 'bg-emerald-50');
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-emerald-500', 'bg-emerald-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            buktInput.files = files;
            fileName.textContent = '✓ File: ' + files[0].name;
        }
    });
</script>
