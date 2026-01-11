@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 p-4 sm:p-6">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900">Pencatatan Penjualan</h1>
            <p class="text-slate-600 mt-1">Catat barang keluar untuk hari ini</p>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex gap-3">
                    <div class="text-red-600 text-xl flex-shrink-0">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-red-900">Terjadi kesalahan</h4>
                        <ul class="list-disc pl-5 text-red-700 text-sm space-y-1 mt-2">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <form action="{{ route('daily-sales.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6 sm:p-8">
            @csrf

            <!-- Section: Daftar Barang -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-slate-900">Daftar Barang</h2>
                </div>

                <div id="items" class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                        <div class="sm:col-span-6">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Barang</label>
                            <select name="barang_id[]" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none" required>
                                <option value="">Pilih barang</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama_barang }} — Stok: {{ $b->stok_saat_ini }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah</label>
                            <input type="number" name="jumlah[]" min="1" value="1" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none" required />
                        </div>
                        <div class="sm:col-span-3">
                            <button type="button" onclick="addRow()" class="w-full px-3 py-2 bg-emerald-100 text-emerald-700 font-semibold rounded-lg hover:bg-emerald-200 transition">
                                <i class="fas fa-plus mr-1"></i>Tambah Baris
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="my-8 border-t border-slate-200"></div>

            <!-- Section: Informasi Tambahan -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Informasi Tambahan</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Keluar</label>
                        <input type="date" name="tanggal_keluar" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan</label>
                        <input type="text" name="keterangan" placeholder="Catatan tambahan (opsional)" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 focus:outline-none" />
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="confirmCreate(this.closest('form'), 'Pencatatan Penjualan')" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold rounded-lg shadow-md transition">
                    <i class="fas fa-save mr-2"></i>Simpan Pencatatan
                </button>
                <a href="{{ route('daily-sales.index') }}" class="px-6 py-3 bg-slate-300 hover:bg-slate-400 text-slate-900 font-bold rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>

    </div>
</div>

<script>
    function addRow() {
        const container = document.getElementById('items');
        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 sm:grid-cols-12 gap-3 items-end';
        row.innerHTML = `
            <div class="sm:col-span-6">
                <select name="barang_id[]" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none" required>
                    <option value="">Pilih barang</option>
                    @foreach($barangs as $b)
                        <option value="{{ $b->id }}">{{ addslashes($b->nama_barang) }} — Stok: {{ $b->stok_saat_ini }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-3">
                <input type="number" name="jumlah[]" min="1" value="1" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none" required />
            </div>
            <div class="sm:col-span-3">
                <button type="button" onclick="this.closest('.grid').remove()" class="w-full px-3 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition">Hapus</button>
            </div>
        `;
        container.appendChild(row);
    }
</script>

@endsection
