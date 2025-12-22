<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\BarangMasuk;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kategori contoh
        $k1 = Kategori::firstOrCreate(['nama_kategori' => 'Bahan Bangunan'], ['deskripsi' => 'Pasir, semen, bata, dll.']);
        $k2 = Kategori::firstOrCreate(['nama_kategori' => 'Peralatan'], ['deskripsi' => 'Alat tukang dan perlengkapan']);

        // Barang contoh
        $b1 = Barang::firstOrCreate([
            'sku' => 'SKU-SEMEN-001'
        ], [
            'nama_barang' => 'Semen Portland 50kg',
            'kategori_id' => $k1->id,
            'satuan' => 'sak',
            'stok_saat_ini' => 0,
            'deskripsi' => 'Semen kualitas standar 50kg'
        ]);

        $b2 = Barang::firstOrCreate([
            'sku' => 'SKU-PASIR-001'
        ], [
            'nama_barang' => 'Pasir Putih 1m3',
            'kategori_id' => $k1->id,
            'satuan' => 'm3',
            'stok_saat_ini' => 0,
            'deskripsi' => 'Pasir untuk adukan'
        ]);

        $b3 = Barang::firstOrCreate([
            'sku' => 'SKU-PALU-001'
        ], [
            'nama_barang' => 'Palu Tukang',
            'kategori_id' => $k2->id,
            'satuan' => 'pcs',
            'stok_saat_ini' => 0,
            'deskripsi' => 'Palu untuk keperluan tukang'
        ]);

        // Tambahan barang contoh
        $b4 = Barang::firstOrCreate([
            'sku' => 'SKU-CAT-5KG-001'
        ], [
            'nama_barang' => 'Cat Tembok ABC 5kg',
            'kategori_id' => $k1->id,
            'satuan' => 'kaleng',
            'stok_saat_ini' => 0,
            'deskripsi' => 'Cat tembok interior, coverage baik'
        ]);

        $b5 = Barang::firstOrCreate([
            'sku' => 'SKU-PIPA-004'
        ], [
            'nama_barang' => 'Pipa PVC 4"',
            'kategori_id' => $k1->id,
            'satuan' => 'batang',
            'stok_saat_ini' => 0,
            'deskripsi' => 'Pipa PVC untuk instalasi air'
        ]);

        $b6 = Barang::firstOrCreate([
            'sku' => 'SKU-SKOP-001'
        ], [
            'nama_barang' => 'Sekop Besi',
            'kategori_id' => $k2->id,
            'satuan' => 'pcs',
            'stok_saat_ini' => 0,
            'deskripsi' => 'Sekop untuk penggalian dan adukan'
        ]);

        $b7 = Barang::firstOrCreate([
            'sku' => 'SKU-KUNCI-ING-001'
        ], [
            'nama_barang' => 'Kunci Inggris 10"',
            'kategori_id' => $k2->id,
            'satuan' => 'pcs',
            'stok_saat_ini' => 0,
            'deskripsi' => 'Kunci serbaguna untuk pekerjaan garasi dan proyek'
        ]);

        $b8 = Barang::firstOrCreate([
            'sku' => 'SKU-KAWAT-10M'
        ], [
            'nama_barang' => 'Kawat Bendrat 10m',
            'kategori_id' => $k2->id,
            'satuan' => 'roll',
            'stok_saat_ini' => 0,
            'deskripsi' => 'Kawat bendrat untuk pengikat dan konstruksi'
        ]);

        // Entri barang_masuk contoh
        $m1 = BarangMasuk::create([
            'barang_id' => $b1->id,
            'jumlah_barang_masuk' => 20,
            'tanggal_masuk' => now()->subDays(5),
            'keterangan' => 'Stok awal dari supplier A'
        ]);
        $b1->increment('stok_saat_ini', 20);

        $m2 = BarangMasuk::create([
            'barang_id' => $b2->id,
            'jumlah_barang_masuk' => 5,
            'tanggal_masuk' => now()->subDays(3),
            'keterangan' => 'Pengiriman pasokan'
        ]);
        $b2->increment('stok_saat_ini', 5);

        $m3 = BarangMasuk::create([
            'barang_id' => $b3->id,
            'jumlah_barang_masuk' => 10,
            'tanggal_masuk' => now()->subDays(1),
            'keterangan' => 'Pengadaan alat baru'
        ]);
        $b3->increment('stok_saat_ini', 10);

        // Barang masuk tambahan untuk produk baru
        $m4 = BarangMasuk::create([
            'barang_id' => $b4->id,
            'jumlah_barang_masuk' => 50,
            'tanggal_masuk' => now()->subDays(2),
            'keterangan' => 'Stok awal cat tembok'
        ]);
        $b4->increment('stok_saat_ini', 50);

        $m5 = BarangMasuk::create([
            'barang_id' => $b5->id,
            'jumlah_barang_masuk' => 30,
            'tanggal_masuk' => now()->subDays(3),
            'keterangan' => 'Pengiriman pipa PVC'
        ]);
        $b5->increment('stok_saat_ini', 30);

        $m6 = BarangMasuk::create([
            'barang_id' => $b6->id,
            'jumlah_barang_masuk' => 15,
            'tanggal_masuk' => now()->subDays(4),
            'keterangan' => 'Perlengkapan tukang'
        ]);
        $b6->increment('stok_saat_ini', 15);

        $m7 = BarangMasuk::create([
            'barang_id' => $b7->id,
            'jumlah_barang_masuk' => 12,
            'tanggal_masuk' => now()->subDays(6),
            'keterangan' => 'Stok alat kunci'
        ]);
        $b7->increment('stok_saat_ini', 12);

        $m8 = BarangMasuk::create([
            'barang_id' => $b8->id,
            'jumlah_barang_masuk' => 8,
            'tanggal_masuk' => now()->subDays(7),
            'keterangan' => 'Kawat bendrat untuk proyek'
        ]);
        $b8->increment('stok_saat_ini', 8);
    }
}
