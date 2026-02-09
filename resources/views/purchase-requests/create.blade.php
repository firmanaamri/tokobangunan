@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Ajukan Pengajuan Pembelian</h1>
            <p class="text-slate-600 mt-2">Buat permintaan pembelian barang baru</p>
        </div>

        <!-- Alerts handled by SweetAlert -->

        <!-- Form Card -->
        <form method="POST" action="{{ route('purchase-requests.store') }}" class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Barang -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-box mr-2 text-emerald-500"></i>Barang<span class="text-red-500">*</span>
                    </label>
                    <select id="barang-select" name="barang_id" required class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('barang_id') border-red-500 @enderror">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" data-satuan="{{ $barang->satuan }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->nama_barang }} ({{ $barang->kategori->nama_kategori ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Supplier -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-truck mr-2 text-emerald-500"></i>Supplier<span class="text-red-500">*</span>
                    </label>
                    <select id="supplier-select" name="supplier_id" required class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('supplier_id') border-red-500 @enderror">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                        <option value="new_supplier">+ Tambah supplier baru</option>
                    </select>
                    <script>
                        (function(){
                            var sel = document.getElementById('supplier-select');
                            if (!sel) return;
                            sel.addEventListener('change', function(e){
                                if (this.value === 'new_supplier') {
                                    window.location.href = '{{ route('suppliers.create') }}';
                                }
                            });
                        })();
                    </script>
                    @error('supplier_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Jumlah Diminta -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-calculator mr-2 text-emerald-500"></i>Jumlah Diminta<span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah_diminta" min="1" required value="{{ old('jumlah_diminta') }}" placeholder="100" class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('jumlah_diminta') border-red-500 @enderror">
                    @error('jumlah_diminta')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Satuan -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-ruler mr-2 text-emerald-500"></i>Satuan<span class="text-red-500">*</span>
                    </label>
                    <input id="satuan" type="text" name="satuan" required value="{{ old('satuan') }}" placeholder="pcs, dus, meter, etc" class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('satuan') border-red-500 @enderror">
                    @error('satuan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <script>
                    (function(){
                        var sel = document.getElementById('barang-select');
                        var satuanInput = document.getElementById('satuan');
                        if(!sel || !satuanInput) return;
                        var initial = true;
                        var handler = function(){
                            var opt = sel.options[sel.selectedIndex];
                            if(!opt) return;
                            var s = opt.getAttribute('data-satuan') || '';
                            if(initial){
                                // on initial load, only populate when input is empty
                                if(!satuanInput.value || satuanInput.value.trim() === ''){
                                    satuanInput.value = s;
                                }
                                initial = false;
                            } else {
                                // on change, always update
                                satuanInput.value = s;
                            }
                        };
                        sel.addEventListener('change', handler);
                        // compute initial if a barang is pre-selected
                        if(sel.value){ handler(); }
                    })();
                </script>
                
                    <!-- Payment Term -->
                    <div>
                        <label for="payment_term" class="block text-sm font-bold text-slate-700 mb-2">
                            <i class="fas fa-calendar-day mr-2 text-emerald-500"></i>Payment Term (hari)
                        </label>
                        <input type="number" name="payment_term" id="payment_term" min="0" value="{{ old('payment_term') }}" placeholder="Contoh: 30" class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('payment_term') border-red-500 @enderror">
                        <p class="text-xs text-slate-500 mt-1">Masukkan jumlah hari jatuh tempo setelah tanggal pembelian. Jika kosong, bisa diisi manual saat pembuatan Purchase.</p>
                        @error('payment_term')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-bold text-slate-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-emerald-500"></i>Tanggal Jatuh Tempo (opsional)
                        </label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('due_date') border-red-500 @enderror">
                        
                        @error('due_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
            </div>

            <!-- Catatan Request -->
            <div class="mt-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    <i class="fas fa-sticky-note mr-2 text-emerald-500"></i>Catatan / Keterangan (Opsional)
                </label>
                <textarea name="catatan_request" rows="4" placeholder="Masukkan keterangan atau spesifikasi tambahan..." class="w-full rounded-lg border border-slate-200 px-4 py-2 focus:border-emerald-400 focus:ring-emerald-200 @error('catatan_request') border-red-500 @enderror">{{ old('catatan_request') }}</textarea>
                @error('catatan_request')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="mt-8 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Informasi:</strong> PR ini akan diajukan dengan status <strong>Pending</strong> menunggu persetujuan dari admin. Anda bisa mengedit atau membatalkan selama masih pending.
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-row gap-4 mt-8">
                <button type="button" onclick="confirmCreate(this.closest('form'), 'Purchase Request')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-300">
                    <i class="fas fa-paper-plane mr-2"></i>Ajukan Pengajuan
                </button>
                <a href="{{ route('purchase-requests.index') }}" class="flex-1 bg-slate-300 hover:bg-slate-400 text-slate-900 font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-300 text-center">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
            <script>
                (function(){
                    function pad(n){ return n < 10 ? '0' + n : n }
                    function computeDue(days){
                        var d = new Date();
                        d.setDate(d.getDate() + days);
                        return d.getFullYear() + '-' + pad(d.getMonth()+1) + '-' + pad(d.getDate());
                    }
                    var pt = document.getElementById('payment_term');
                    var dd = document.getElementById('due_date');
                    if(!pt || !dd) return;
                    if(pt.disabled) return;
                    var handler = function(){
                        var v = parseInt(pt.value);
                        if(isNaN(v) || v < 0){ dd.value = ''; return; }
                        dd.value = computeDue(v);
                    };
                    pt.addEventListener('input', handler);
                    pt.addEventListener('change', handler);
                    // if page has old value, compute initial due date
                    if(pt.value){ handler(); }
                })();
            </script>
        </form>
    </div>
</div>
@endsection
