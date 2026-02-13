@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white p-6">
    <div class="max-w-4xl mx-auto">
        
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900">Pencatatan Penjualan</h1>
            <p class="text-slate-600 mt-1">Catat barang keluar untuk hari ini</p>
        </div>

        <form action="{{ route('daily-sales.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6 sm:p-8">
            @csrf

            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-slate-900">Daftar Barang</h2>
                </div>

                <div id="items" class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-start product-row">
                        <div class="sm:col-span-6">
    <label for="pilihan_barang" class="block text-sm font-semibold text-slate-700 mb-2">Barang</label>
    
    <select id="pilihan_barang" name="barang_id[]" onchange="checkStock(this)" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none" required>
        <option value="" data-stok="0">Pilih barang</option>
        @foreach($barangs as $b)
            <option value="{{ $b->id }}" data-stok="{{ $b->stok_saat_ini }}">
                {{ $b->nama_barang }} — Stok: {{ $b->stok_saat_ini }}
            </option>
        @endforeach
    </select>
</div>
                        <div class="sm:col-span-3">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah</label>
                            <input type="number" name="jumlah[]" min="1" placeholder="0" oninput="checkStock(this)" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none" required />
                            
                            <p class="text-red-600 text-xs mt-1 font-semibold hidden stock-warning">
                                <i class="fas fa-exclamation-circle mr-1"></i>Melebihi stok!
                            </p>
                        </div>
                        <div class="sm:col-span-3 pt-8"> 
                            <button type="button" onclick="addRow()" class="w-full px-3 py-2 bg-emerald-100 text-emerald-700 font-semibold rounded-lg hover:bg-emerald-200 transition">
                                <i class="fas fa-plus mr-1"></i>Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-8 border-t border-slate-200"></div>

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

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition">
                    <i class="fas fa-save mr-2"></i>Simpan Pencatatan
                </button>
                <a href="{{ route('daily-sales.index') }}" class="px-6 py-3 bg-slate-300 hover:bg-slate-400 text-slate-900 font-bold rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- SWEETALERT LOGIC ---
    document.addEventListener('DOMContentLoaded', function() {
        // Cek Error Laravel
        @if($errors->any())
            let errorMessages = '';
            @foreach($errors->all() as $error)
                errorMessages += '{{ $error }}<br>';
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan',
                html: errorMessages,
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        @endif

        // Cek Session Success (Opsional, agar konsisten)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1500
            });
        @endif
    });

    // --- FORM LOGIC ---
    function checkStock(element) {
        const row = element.closest('.product-row');
        const select = row.querySelector('select[name="barang_id[]"]');
        const input = row.querySelector('input[name="jumlah[]"]');
        const warningText = row.querySelector('.stock-warning');

        if (select.value === "") {
            input.disabled = true;
            input.value = "";
            warningText.classList.add('hidden');
            return;
        }

        const selectedOption = select.options[select.selectedIndex];
        const stock = parseInt(selectedOption.getAttribute('data-stok')) || 0;
        let inputVal = parseInt(input.value);

        input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        input.classList.add('focus:border-emerald-500', 'focus:ring-emerald-500');
        warningText.classList.add('hidden');
        input.disabled = false;

        if (stock === 0) {
            input.value = 0;
            input.max = 0;
            warningText.innerHTML = `<i class="fas fa-times-circle mr-1"></i>Stok Habis!`;
            warningText.classList.remove('hidden');
            input.classList.add('border-red-500');
        } else {
            input.max = stock;
            if (!isNaN(inputVal) && inputVal > stock) {
                input.value = stock;
                warningText.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>Maksimal: ${stock}`;
                warningText.classList.remove('hidden');
            }
        }
    }

    function addRow() {
        const container = document.getElementById('items');
        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 sm:grid-cols-12 gap-3 items-start product-row mt-3'; 
        
        row.innerHTML = `
            <div class="sm:col-span-6">
                <select name="barang_id[]" onchange="checkStock(this)" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none" required>
                    <option value="" data-stok="0">Pilih barang</option>
                    @foreach($barangs as $b)
                        <option value="{{ $b->id }}" data-stok="{{ $b->stok_saat_ini }}">
                            {{ addslashes($b->nama_barang) }} — Stok: {{ $b->stok_saat_ini }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-3">
                <input type="number" name="jumlah[]" min="1" placeholder="0" oninput="checkStock(this)" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none" required />
                <p class="text-red-600 text-xs mt-1 font-semibold hidden stock-warning">
                    <i class="fas fa-exclamation-circle mr-1"></i>Melebihi stok!
                </p>
            </div>
            <div class="sm:col-span-3 pt-1">
                <button type="button" onclick="this.closest('.grid').remove()" class="w-full px-3 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition">Hapus</button>
            </div>
        `;
        container.appendChild(row);
    }
</script>

@endsection