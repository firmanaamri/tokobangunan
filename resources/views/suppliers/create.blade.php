@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-slate-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Tambah Supplier Baru</h1>
            <p class="text-slate-600 mt-2">Masukkan informasi supplier lengkap</p>
        </div>

        <!-- Form -->
        <form action="{{ route('suppliers.store') }}" method="POST" class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
            @csrf
            <div class="p-8 space-y-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Tambah Supplier Baru</h2>
                        <p class="text-sm text-slate-500 mt-1">Masukkan informasi supplier lengkap untuk keperluan pembelian.</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400">Formulir cepat</span>
                    </div>
                </div>
                <!-- Nama Supplier -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Nama Supplier <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_supplier" value="{{ old('nama_supplier') }}" placeholder="Contoh: PT. Bahan Bangunan Jaya" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('nama_supplier') border-red-500 @enderror">
                    @error('nama_supplier')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    </div>

                    <!-- Kontak Person -->
                    <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Kontak Person</label>
                    <input type="text" name="kontak_person" value="{{ old('kontak_person') }}" placeholder="Nama kontak person" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('kontak_person') border-red-500 @enderror">
                    @error('kontak_person')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Nomor Telepon -->
                    <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Nomor Telepon</label>
                    <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon') }}" placeholder="Contoh: +62-812-3456-7890" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('nomor_telepon') border-red-500 @enderror">
                    @error('nomor_telepon')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    </div>

                    <!-- Email -->
                    <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: info@supplier.com" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    </div>
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Alamat</label>
                    <textarea name="alamat" rows="3" placeholder="Alamat lengkap supplier" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Kota -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Kota</label>
                    <select id="kota-select" name="kota" data-old="{{ old('kota') }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('kota') border-red-500 @enderror">
                        <option value="">-- Pilih atau ketik kota --</option>
                        @foreach(config('indonesia.cities', []) as $city)
                            <option value="{{ $city }}" @selected(old('kota') == $city)>{{ $city }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-slate-500">Ketik untuk mencari atau tambahkan kota baru.</p>
                    @error('kota')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    </div>

                    <!-- Provinsi -->
                    <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Provinsi</label>
                    <select id="provinsi-select" name="provinsi" data-old="{{ old('provinsi') }}" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('provinsi') border-red-500 @enderror">
                        <option value="">-- Pilih provinsi --</option>
                        @foreach(config('indonesia.provinces', []) as $prov)
                            <option value="{{ $prov }}" @selected(old('provinsi') == $prov)>{{ $prov }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-slate-500">Pilih provinsi dari daftar.</p>
                    @error('provinsi')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Kode Pos -->
                    <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Kode Pos</label>
                    <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" placeholder="Contoh: 12345" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('kode_pos') border-red-500 @enderror">
                    @error('kode_pos')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    </div>

                    <!-- Status -->
                    <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('status') border-red-500 @enderror">
                        <option value="aktif" @selected(old('status') == 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status') == 'nonaktif')>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="4" placeholder="Catatan tambahan tentang supplier" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-emerald-500 focus:outline-none @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
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
                <a href="{{ route('suppliers.index') }}" class="bg-slate-500 hover:bg-slate-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
    {{-- Select2 assets and init for kota search --}}
    <style>
        /* Make Select2 look like Tailwind inputs */
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem;
            height: 44px;
            padding: 0.25rem 0.75rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 44px;
            color: #0f172a;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
        }
        /* Ensure dropdown matches width nicely */
        .select2-container { width: 100% !important; }
    </style>
    {{-- Select2 is bundled via Vite and initialized in resources/js/app.js --}}
@endsection
