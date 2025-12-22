@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Tambah Barang Keluar</h1>
            <p class="text-sm text-slate-400">Masukkan data barang yang keluar dari gudang.</p>
        </div>
        <a href="{{ route('barang-keluar.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Kembali ke daftar</a>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('barang-keluar.store') }}" method="POST" class="bg-white p-6 rounded-xl shadow-sm border">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="col-span-1">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                <select id="kategori_filter" class="mt-1 block w-full border border-slate-200 rounded-md px-3 py-2 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-1">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Barang</label>
                <select id="barang_select" name="barang_id" class="mt-1 block w-full border border-slate-200 rounded-md px-3 py-2 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barangs as $b)
                        <option value="{{ $b->id }}" data-kategori="{{ $b->kategori?->id }}">{{ $b->nama_barang }} <span class="text-xs">(SKU: {{ $b->sku }})</span></option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jumlah</label>
                <input type="number" name="jumlah_barang_keluar" class="mt-1 block w-full border border-slate-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300" min="1" required>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                <input type="date" name="tanggal_keluar" class="mt-1 block w-full border border-slate-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3" class="mt-1 block w-full border border-slate-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300"></textarea>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('barang-keluar.index') }}" class="inline-block px-4 py-2 bg-slate-50 text-slate-600 border border-slate-200 rounded-md hover:bg-slate-100">Batal</a>
            <button class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md shadow hover:bg-emerald-700 transition">Simpan</button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    (function(){
        const kategori = document.getElementById('kategori_filter');
        const barangSelect = document.getElementById('barang_select');

        if (!kategori || !barangSelect) return;

        function filterOptions(){
            const val = kategori.value;
            for (const opt of barangSelect.options) {
                const k = opt.getAttribute('data-kategori');
                if (!k || k === '' ) {
                    opt.hidden = false;
                    opt.disabled = false;
                    continue;
                }
                if (val === '' || k === val) {
                    opt.hidden = false;
                    opt.disabled = false;
                } else {
                    opt.hidden = true;
                    opt.disabled = true;
                }
            }

            if (barangSelect.value) {
                const selected = barangSelect.selectedOptions[0];
                if (selected && selected.hidden) {
                    barangSelect.value = '';
                }
            }
        }

        kategori.addEventListener('change', filterOptions);
        filterOptions();
    })();
</script>
@endpush
