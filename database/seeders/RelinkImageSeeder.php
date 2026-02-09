<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Product; // <--- PASTIKAN INI SESUAI NAMA MODEL ANDA

class RelinkImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- KONFIGURASI DI SINI ---
        
        // 1. Nama folder di dalam 'public/storage' tempat gambar lama berkumpul.
        // Coba cek folder public/storage anda, biasanya namanya 'products', 'images', atau kosong ('').
        // Jika gambar ada langsung di public/storage, kosongkan jadi $folderName = '';
        $folderName = 'produk'; 
        
        // --- AKHIR KONFIGURASI ---

        // Menentukan path fisik folder
        $path = public_path("storage/" . $folderName);
        
        // Cek folder
        if (!File::exists($path)) {
            $this->command->error("Folder tidak ditemukan di: " . $path);
            $this->command->warn("Coba cek folder 'public/storage' Anda, apa nama folder tempat gambar disimpan?");
            return;
        }

        // Ambil semua file gambar
        $files = File::files($path);
        
        // Filter agar hanya mengambil file gambar (jpg, png, jpeg, webp)
        $images = array_filter($files, function ($file) {
            return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp']);
        });

        // Reset index array agar urut dari 0
        $images = array_values($images);

        if (count($images) === 0) {
            $this->command->error("Tidak ada file gambar ditemukan di folder tersebut.");
            return;
        }

        // Ambil semua produk yang kolom 'gambar'-nya masih kosong/null
        // Atau ambil semua (Product::all()) jika ingin menimpa semuanya
        $products = Barang::whereNull('gambar')->orWhere('gambar', '')->get();

        if ($products->isEmpty()) {
            $this->command->info("Semua produk sudah punya gambar. Tidak ada yang perlu diupdate.");
            // Hapus baris di atas dan uncomment bawah ini jika ingin memaksa update semua:
            // $products = Product::all();
        }

        $this->command->info('Ditemukan ' . count($images) . ' file gambar. Memproses ' . $products->count() . ' produk...');

        foreach ($products as $index => $product) {
            // Ambil gambar secara berurutan (looping jika produk lebih banyak dari gambar)
            $file = $images[$index % count($images)];
            
            // Ambil nama file (misal: a8s7d9.jpg)
            $filename = $file->getFilename();

            // Jika folderName tidak kosong, tambahkan slash
            $dbPath = $folderName ? $folderName . '/' . $filename : $filename;

            // Update database
            $product->update([
                'gambar' => $dbPath  // <--- INI NAMA KOLOM ANDA
            ]);
        }

        $this->command->info('BERHASIL! Gambar sudah tersambung ulang ke kolom "gambar".');
    }
}