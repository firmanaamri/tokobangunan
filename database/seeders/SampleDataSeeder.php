<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\User;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();

        // Ambil kategori yang sudah ada dari KategoriSeeder
        $kSemen = Kategori::where('nama_kategori', 'Semen')->first();
        $kBata = Kategori::where('nama_kategori', 'Batu Bata & Batako')->first();
        $kPasir = Kategori::where('nama_kategori', 'Pasir & Kerikil')->first();
        $kBesi = Kategori::where('nama_kategori', 'Besi & Baja')->first();
        $kCat = Kategori::where('nama_kategori', 'Cat & Finishing')->first();
        $kPipa = Kategori::where('nama_kategori', 'Pipa & Fitting')->first();
        $kKeramik = Kategori::where('nama_kategori', 'Keramik & Granit')->first();
        $kKayu = Kategori::where('nama_kategori', 'Kayu & Plywood')->first();
        $kListrik = Kategori::where('nama_kategori', 'Listrik & Kabel')->first();
        $kPintu = Kategori::where('nama_kategori', 'Pintu & Jendela')->first();
        $kAlat = Kategori::where('nama_kategori', 'Alat Pertukangan')->first();
        $kAtap = Kategori::where('nama_kategori', 'Atap & Genteng')->first();

        // Barang contoh sesuai kategori
        $b1 = Barang::firstOrCreate([
            'sku' => 'SMN-GRS-50KG'
        ], [
            'nama_barang' => 'Semen Gresik 50kg',
            'kategori_id' => $kSemen->id,
            'satuan' => 'sak',
            'harga' => 65000,
            'stok_saat_ini' => 0,
            'deskripsi' => 'Semen Portland tipe I'
        ]);

        $b2 = Barang::firstOrCreate([
            'sku' => 'PSR-BET-M3'
        ], [
            'nama_barang' => 'Pasir Beton 1m3',
            'kategori_id' => $kPasir->id,
            'satuan' => 'm3',
            'harga' => 350000,
            'stok_saat_ini' => 0,
            'deskripsi' => 'Pasir untuk cor beton dan adukan'
        ]);

        $b3 = Barang::firstOrCreate([
            'sku' => 'BTA-MRH-001'
        ], [
            'nama_barang' => 'Batu Bata Merah Press',
            'kategori_id' => $kBata->id,
            'satuan' => 'biji',
            'harga' => 1200,
            'stok_saat_ini' => 0,
            'deskripsi' => 'Bata merah kualitas press untuk dinding'
        ]);

        $b4 = Barang::firstOrCreate([
            'sku' => 'CAT-AVN-5KG'
        ], [
            'nama_barang' => 'Cat Avian 5kg Putih',
            'kategori_id' => $kCat->id,
            'satuan' => 'kaleng',
            'harga' => 145000,
            'stok_saat_ini' => 0,
            'deskripsi' => 'Cat tembok interior dan eksterior'
        ]);

        $b5 = Barang::firstOrCreate([
            'sku' => 'PIP-PVC-4IN'
        ], [
            'nama_barang' => 'Pipa PVC Rucika 4 inch',
            'kategori_id' => $kPipa->id,
            'satuan' => 'batang',
            'harga' => 95000,
            'stok_saat_ini' => 0,
            'deskripsi' => 'Pipa PVC untuk instalasi air bersih'
        ]);

        $b6 = Barang::firstOrCreate([
            'sku' => 'BSI-10MM-12M'
        ], [
            'nama_barang' => 'Besi Beton 10mm 12m',
            'kategori_id' => $kBesi->id,
            'satuan' => 'batang',
            'harga' => 85000,
            'stok_saat_ini' => 0,
            'deskripsi' => 'Besi beton polos ukuran 10mm panjang 12 meter'
        ]);

        $b7 = Barang::firstOrCreate([
            'sku' => 'KRM-60X60-001'
        ], [
            'nama_barang' => 'Keramik Lantai 60x60 cm',
            'kategori_id' => $kKeramik->id,
            'satuan' => 'dus',
            'harga' => 185000,
            'stok_saat_ini' => 0,
            'deskripsi' => 'Keramik granit tile ukuran 60x60 cm per dus isi 4'
        ]);

        $b8 = Barang::firstOrCreate([
            'sku' => 'GNT-BET-001'
        ], [
            'nama_barang' => 'Genteng Beton Flat',
            'kategori_id' => $kAtap->id,
            'satuan' => 'biji',
            'harga' => 8500,
            'stok_saat_ini' => 0,
            'deskripsi' => 'Genteng beton flat untuk atap rumah'
        ]);

        $b9 = Barang::firstOrCreate([
            'sku' => 'ALT-PLU-001'
        ], [
            'nama_barang' => 'Palu Tukang Batu',
            'kategori_id' => $kAlat->id,
            'satuan' => 'pcs',
            'harga' => 55000,
            'stok_saat_ini' => 0,
            'deskripsi' => 'Palu dengan gagang kayu untuk pekerjaan tukang'
        ]);

        $b10 = Barang::firstOrCreate([
            'sku' => 'KBL-NYM-2X15'
        ], [
            'nama_barang' => 'Kabel NYM 2x1.5mm',
            'kategori_id' => $kListrik->id,
            'satuan' => 'meter',
            'harga' => 5500,
            'stok_saat_ini' => 0,
            'deskripsi' => 'Kabel listrik instalasi rumah 2x1.5mm'
        ]);

        $b10 = Barang::firstOrCreate([
            'sku' => 'KBL-NYM-2X15'
        ], [
            'nama_barang' => 'Kabel NYM 2x1.5mm',
            'kategori_id' => $kListrik->id,
            'satuan' => 'meter',
            'harga' => 5500,
            'stok_saat_ini' => 0,
            'deskripsi' => 'Kabel listrik instalasi rumah 2x1.5mm'
        ]);

        // Entri barang_masuk contoh untuk stok awal
        $barangMasuk = [
            ['barang' => $b1, 'jumlah' => 100, 'keterangan' => 'Stok awal semen dari supplier'],
            ['barang' => $b2, 'jumlah' => 15, 'keterangan' => 'Pengiriman pasir beton'],
            ['barang' => $b3, 'jumlah' => 5000, 'keterangan' => 'Stok bata merah'],
            ['barang' => $b4, 'jumlah' => 80, 'keterangan' => 'Stok cat tembok'],
            ['barang' => $b5, 'jumlah' => 120, 'keterangan' => 'Pengadaan pipa PVC'],
            ['barang' => $b6, 'jumlah' => 150, 'keterangan' => 'Stok besi beton'],
            ['barang' => $b7, 'jumlah' => 40, 'keterangan' => 'Keramik lantai berbagai motif'],
            ['barang' => $b8, 'jumlah' => 2000, 'keterangan' => 'Genteng untuk proyek perumahan'],
            ['barang' => $b9, 'jumlah' => 25, 'keterangan' => 'Perlengkapan alat tukang'],
            ['barang' => $b10, 'jumlah' => 500, 'keterangan' => 'Kabel instalasi rumah'],
        ];

        foreach ($barangMasuk as $index => $item) {
            BarangMasuk::create([
                'barang_id' => $item['barang']->id,
                'jumlah_barang_masuk' => $item['jumlah'],
                'tanggal_masuk' => now()->subDays(10 - $index),
                'user_id' => $admin?->id,
                'keterangan' => $item['keterangan']
            ]);
            $item['barang']->increment('stok_saat_ini', $item['jumlah']);
        }

        $this->command->info('✓ ' . count($barangMasuk) . ' barang sample berhasil ditambahkan dengan stok awal');
    }
}
