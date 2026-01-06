@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-slate-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Buat Transaksi Pembelian Baru</h1>
            <p class="text-slate-600 mt-2">Catat pembelian barang dari supplier</p>
        </div>

        <!-- Form -->
        <form action="{{ route('purchases.store') }}" method="POST" class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            @csrf

            <div class="p-8 space-y-6">
                <!-- Barang -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Barang <span class="text-red-500">*</span></label>
                    <select name="barang_id" id="barang_id" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('barang_id') border-red-500 @enderror">
                        <option value="">-- Pilih Barang --</option>
                        @foreach (\App\Models\Barang::with('kategori')->get() as $barang)
                            <option value="{{ $barang->id }}" data-satuan="{{ $barang->satuan }}" data-harga="{{ $barang->harga }}" @selected(old('barang_id') == $barang->id)>
                                {{ $barang->nama_barang }} ({{ $barang->kategori->nama_kategori }}) - Rp {{ number_format($barang->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jumlah -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Jumlah <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah') }}" min="1" placeholder="0" class="flex-1 border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('jumlah') border-red-500 @enderror">
                        <input type="text" id="satuan" readonly placeholder="Satuan" class="w-32 border-2 border-slate-300 rounded-lg px-4 py-2 bg-slate-100 text-slate-600">
                    </div>
                    @error('jumlah')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Per Unit -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Harga Per Unit (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_per_unit" id="harga_per_unit" value="{{ old('harga_per_unit') }}" step="0.01" min="0" placeholder="0" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('harga_per_unit') border-red-500 @enderror">
                    @error('harga_per_unit')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Harga (Auto Calculate) -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Total Harga (Rp)</label>
                    <input type="number" name="total_harga" id="total_harga" value="{{ old('total_harga') }}" readonly step="0.01" min="0" placeholder="0" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 bg-slate-100 text-slate-900 font-bold text-xl @error('total_harga') border-red-500 @enderror">
                    @error('total_harga')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Supplier -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('supplier_id') border-red-500 @enderror">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->nama_supplier }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Pembelian -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Tanggal Pembelian <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('tanggal_pembelian') border-red-500 @enderror">
                    @error('tanggal_pembelian')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Harga -->
                {{-- <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Total Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="total_harga" value="{{ old('total_harga') }}" step="0.01" min="0" placeholder="0" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('total_harga') border-red-500 @enderror">
                    @error('total_harga')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div> --}}

                <!-- Tanggal Jatuh Tempo -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Tanggal Jatuh Tempo (Opsional)</label>
                    <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('tanggal_jatuh_tempo') border-red-500 @enderror">
                    @error('tanggal_jatuh_tempo')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="4" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="bg-slate-100 px-8 py-4 flex gap-4">
                <button type="submit" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
                <a href="{{ route('purchases.index') }}" class="bg-slate-500 hover:bg-slate-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-calculate total harga
    const barangSelect = document.getElementById('barang_id');
    const jumlahInput = document.getElementById('jumlah');
    const hargaPerUnitInput = document.getElementById('harga_per_unit');
    const totalHargaInput = document.getElementById('total_harga');
    const satuanInput = document.getElementById('satuan');

    // Update satuan dan harga saat pilih barang
    barangSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const satuan = selectedOption.getAttribute('data-satuan');
        const harga = selectedOption.getAttribute('data-harga');
        
        satuanInput.value = satuan || '';
        hargaPerUnitInput.value = harga || '';
        
        calculateTotal();
    });

    // Calculate total saat input jumlah atau harga berubah
    jumlahInput.addEventListener('input', calculateTotal);
    hargaPerUnitInput.addEventListener('input', calculateTotal);

    function calculateTotal() {
        const jumlah = parseFloat(jumlahInput.value) || 0;
        const hargaPerUnit = parseFloat(hargaPerUnitInput.value) || 0;
        const total = jumlah * hargaPerUnit;
        
        totalHargaInput.value = total.toFixed(2);
    }
</script>
@endsection
