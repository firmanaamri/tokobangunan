@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF7F2] via-[#F8F4EE] to-[#FAF7F2] p-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Edit Transaksi Pembelian</h1>
            <p class="text-slate-600 mt-2">{{ $purchase->nomor_po }}</p>
        </div>

        <form action="{{ route('purchases.update', $purchase->id) }}" method="POST" class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            @csrf
            @method('PUT')

            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('supplier_id') border-red-500 @enderror">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id', $purchase->supplier_id) == $supplier->id)>{{ $supplier->nama_supplier }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Tanggal Pembelian <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', $purchase->tanggal_pembelian->format('Y-m-d')) }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('tanggal_pembelian') border-red-500 @enderror">
                    @error('tanggal_pembelian')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Total Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="total_harga" value="{{ old('total_harga', $purchase->total_harga) }}" step="0.01" min="0" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('total_harga') border-red-500 @enderror">
                    @error('total_harga')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Tanggal Jatuh Tempo (Opsional)</label>
                    {{-- Perhatikan: name="due_date" dan value mengambil dari $purchase->due_date --}}
                    <input type="date" name="due_date" value="{{ old('due_date', $purchase->due_date?->format('Y-m-d')) }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="4" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $purchase->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-slate-100 px-8 py-4 flex gap-4">
                <button type="button" onclick="confirmSave(this.closest('form'), 'transaksi pembelian')" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('purchases.show', $purchase->id) }}" class="bg-slate-500 hover:bg-slate-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection