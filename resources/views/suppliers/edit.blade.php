@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FAF7F2] via-[#F8F4EE] to-[#FAF7F2] p-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Edit Supplier</h1>
            <p class="text-slate-600 mt-2">Perbarui informasi: <span class="font-semibold">{{ $supplier->nama_supplier }}</span></p>
        </div>

        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            @csrf
            @method('PUT')

            <div class="p-8 space-y-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Form Edit Supplier</h2>
                        <p class="text-sm text-slate-500 mt-1">Silakan perbarui data supplier di bawah ini.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Nama Supplier <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_supplier" value="{{ old('nama_supplier', $supplier->nama_supplier) }}" placeholder="Contoh: PT. Bahan Bangunan Jaya" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('nama_supplier') border-red-500 @enderror">
                        @error('nama_supplier') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Kontak Person</label>
                        <input type="text" name="kontak_person" value="{{ old('kontak_person', $supplier->kontak_person) }}" placeholder="Nama kontak person" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('kontak_person') border-red-500 @enderror">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Nomor Telepon</label>
                        <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon', $supplier->nomor_telepon) }}" maxlength="14" oninput="this.value = this.value.replace(/\D/g, '').slice(0,14)" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('nomor_telepon') border-red-500 @enderror">
                        @error('nomor_telepon') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $supplier->email) }}" placeholder="Contoh: info@supplier.com" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Alamat</label>
                    <textarea name="alamat" rows="2" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('alamat') border-red-500 @enderror">{{ old('alamat', $supplier->alamat) }}</textarea>
                    @error('alamat') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Kota</label>
                        <select id="kota-select" name="kota" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none">
                            <option value="">-- Pilih Kota --</option>
                            @foreach(config('indonesia.cities', []) as $city => $province)
                                <option value="{{ $city }}" @selected(old('kota', $supplier->kota) == $city)>{{ $city }}</option>
                            @endforeach
                        </select>
                        @error('kota') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Provinsi</label>
                        <select id="provinsi-select" name="provinsi" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none bg-slate-100 cursor-not-allowed" readonly>
                            <option value="">-- Otomatis --</option>
                            @foreach(config('indonesia.provinces', []) as $prov)
                                <option value="{{ $prov }}" @selected(old('provinsi', $supplier->provinsi) == $prov)>{{ $prov }}</option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-slate-500">Otomatis terisi saat memilih kota.</p>
                        @error('provinsi') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos', $supplier->kode_pos) }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('kode_pos') border-red-500 @enderror">
                        @error('kode_pos') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('status') border-red-500 @enderror">
                            <option value="aktif" @selected(old('status', $supplier->status) == 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status', $supplier->status) == 'nonaktif')>Nonaktif</option>
                        </select>
                        @error('status') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="4" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $supplier->keterangan) }}</textarea>
                    @error('keterangan') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="bg-slate-100 px-8 py-4 flex gap-4">
                <button type="button" onclick="confirmSave(this.closest('form'), 'Supplier')" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('suppliers.index') }}" class="bg-slate-500 hover:bg-slate-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>

{{-- CSS Select2 Custom untuk tampilan Tailwind --}}
<style>
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 2px solid #cbd5e1;
        border-radius: 0.5rem;
        height: 45px;
        padding: 0.25rem 0.5rem;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #0f172a;
        font-weight: 500;
        padding-left: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px;
        right: 10px;
    }
    .select2-container { width: 100% !important; }
</style>

{{-- SCRIPT: Logic Otomatisasi Provinsi --}}
<script type="module">
    $(document).ready(function() {
        console.log("jQuery siap (Edit Mode).");

        // 1. Ambil data Mapping dari Config Laravel
        const cityMap = @json(config('indonesia.cities'));
        
        // 2. Init Select2
        $('#kota-select').select2({ placeholder: "-- Pilih Kota --", allowClear: true });
        $('#provinsi-select').select2({ placeholder: "-- Provinsi --" });

        // 3. Event Listener: Ketika Kota Berubah
        $('#kota-select').on('change', function() {
            const selectedCity = $(this).val();
            const provinceName = cityMap[selectedCity]; 

            if (provinceName) {
                $('#provinsi-select').val(provinceName).trigger('change');
            }
        });
    });
</script>
@endsection