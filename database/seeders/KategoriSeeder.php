<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Semen',
                'deskripsi' => 'Berbagai jenis semen untuk konstruksi',
            ],
            [
                'nama_kategori' => 'Batu Bata & Batako',
                'deskripsi' => 'Batu bata merah, batako, dan bahan dinding lainnya',
            ],
            [
                'nama_kategori' => 'Pasir & Kerikil',
                'deskripsi' => 'Pasir beton, pasir halus, kerikil, dan split',
            ],
            [
                'nama_kategori' => 'Besi & Baja',
                'deskripsi' => 'Besi beton, wiremesh, baja ringan, dan logam konstruksi',
            ],
            [
                'nama_kategori' => 'Cat & Finishing',
                'deskripsi' => 'Cat tembok, cat kayu, plamir, dan bahan finishing',
            ],
            [
                'nama_kategori' => 'Pipa & Fitting',
                'deskripsi' => 'Pipa PVC, pipa galvanis, fitting, dan aksesoris plumbing',
            ],
            [
                'nama_kategori' => 'Keramik & Granit',
                'deskripsi' => 'Keramik lantai, keramik dinding, granit, dan marmer',
            ],
            [
                'nama_kategori' => 'Kayu & Plywood',
                'deskripsi' => 'Kayu konstruksi, tripleks, plywood, dan papan',
            ],
            [
                'nama_kategori' => 'Listrik & Kabel',
                'deskripsi' => 'Kabel listrik, stop kontak, saklar, dan aksesoris elektrik',
            ],
            [
                'nama_kategori' => 'Pintu & Jendela',
                'deskripsi' => 'Pintu kayu, pintu aluminium, jendela, dan aksesoris',
            ],
            [
                'nama_kategori' => 'Alat Pertukangan',
                'deskripsi' => 'Peralatan tukang, palu, gergaji, dan alat konstruksi',
            ],
            [
                'nama_kategori' => 'Atap & Genteng',
                'deskripsi' => 'Genteng, asbes, spandek, dan bahan penutup atap',
            ],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }

        $this->command->info('✓ ' . count($kategoris) . ' kategori barang berhasil ditambahkan');
    }
}
