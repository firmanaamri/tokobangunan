@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white p-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Buat Transaksi Pembelian Baru</h1>
            <p class="text-slate-600 mt-2">Catat pembelian barang dari supplier</p>
        </div>

        <form action="{{ route('purchases.store') }}" method="POST" class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            @csrf
            {{-- Input Hidden untuk Purchase Request ID --}}
            @if(isset($prefill) && !empty($prefill['purchase_request_id']))
                <input type="hidden" name="purchase_request_id" value="{{ $prefill['purchase_request_id'] }}">
            @endif

            <div class="p-8 space-y-6">
                {{-- PILIH BARANG --}}
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Barang <span class="text-red-500">*</span></label>
                    <select name="barang_id" id="barang_id" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none">
                        <option value="">-- Pilih Barang --</option>
                        @foreach (\App\Models\Barang::with('kategori')->get() as $barang)
                            {{-- MODIFIKASI: Tambahkan data-harga untuk menampung harga_beli_terakhir --}}
                            <option value="{{ $barang->id }}" 
                                    data-satuan="{{ $barang->satuan }}" 
                                    data-harga="{{ $barang->harga_beli_terakhir ?? 0 }}"
                                    @selected((old('barang_id') == $barang->id) || (isset($prefill['barang']) && $prefill['barang']->id == $barang->id))>
                                {{ $barang->nama_barang }} ({{ $barang->kategori->nama_kategori }}) - Stok: {{ $barang->stok_saat_ini }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- JUMLAH DAN SATUAN --}}
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Jumlah <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', $prefill['jumlah'] ?? '') }}" min="1" class="flex-1 border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none">
                        <input type="text" id="satuan" readonly class="w-32 border-2 border-slate-300 rounded-lg px-4 py-2 bg-slate-100 text-slate-600">
                    </div>
                </div>

                {{-- HARGA DEAL (NEGO) --}}
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">
                        Harga Beli Satuan (Deal Supplier) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-2 text-slate-500">Rp</span>
                        {{-- Admin bisa mengetik/mengubah harga di sini --}}
                        <input type="number" name="harga_per_unit" id="harga_per_unit" value="{{ old('harga_per_unit') }}" step="0.01" class="w-full pl-10 border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none" placeholder="0">
                    </div>
                    <small class="text-slate-500">Harga ini akan otomatis tersimpan sebagai harga pasar terbaru.</small>
                </div>

                {{-- TOTAL HARGA --}}
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Total Harga (Rp)</label>
                    <input type="number" name="total_harga" id="total_harga" value="{{ old('total_harga') }}" readonly class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 bg-slate-100 font-bold text-xl">
                </div>

                {{-- SUPPLIER --}}
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected((old('supplier_id') == $supplier->id) || (isset($prefill['supplier_id']) && $prefill['supplier_id'] == $supplier->id))>{{ $supplier->nama_supplier }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- TANGGAL PEMBELIAN --}}
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Tanggal Pembelian <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none">
                </div>

                {{-- JATUH TEMPO --}}
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Tanggal Jatuh Tempo (Opsional)</label>
                    <input type="date" 
                           name="due_date" 
                           value="{{ old('due_date', $prefill['due_date'] ?? '') }}" 
                           class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- KETERANGAN --}}
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="4" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="bg-slate-100 px-8 py-4 flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">Simpan PO</button>
                <a href="{{ route('purchases.index') }}" class="bg-slate-500 hover:bg-slate-600 text-white font-bold py-2 px-6 rounded-lg">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
    const barangSelect = document.getElementById('barang_id');
    const jumlahInput = document.getElementById('jumlah');
    const hargaPerUnitInput = document.getElementById('harga_per_unit');
    const totalHargaInput = document.getElementById('total_harga');
    const satuanInput = document.getElementById('satuan');

    // Fungsi Update Form saat Barang Dipilih
    barangSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        // 1. Ambil Satuan
        satuanInput.value = selectedOption.getAttribute('data-satuan') || '';
        
        // 2. MODIFIKASI: Ambil Harga Beli Terakhir (Saran Harga)
        // Jika data-harga ada isinya (tidak 0), masukkan ke input. Jika 0, biarkan kosong atau 0.
        let hargaTerakhir = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
        
        // Set nilai input, user bisa langsung edit kalau harga berubah
        hargaPerUnitInput.value = hargaTerakhir > 0 ? hargaTerakhir : '';
        
        calculateTotal();
    });

    jumlahInput.addEventListener('input', calculateTotal);
    hargaPerUnitInput.addEventListener('input', calculateTotal);

    function calculateTotal() {
        const jumlah = parseFloat(jumlahInput.value) || 0;
        const hargaPerUnit = parseFloat(hargaPerUnitInput.value) || 0;
        totalHargaInput.value = (jumlah * hargaPerUnit).toFixed(2);
    }
    
    // Trigger change event on load if value is selected (untuk kasus edit/error validation kembali)
    if(barangSelect.value) {
        barangSelect.dispatchEvent(new Event('change'));
    }
</script>
@endsection