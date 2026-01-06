<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Purchase;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada supplier
        $supplier = Supplier::first();
        if (!$supplier) {
            $supplier = Supplier::create([
                'nama_supplier' => 'PT Merdeka Jaya',
                'kontak_person' => 'Budi Santoso',
                'nomor_telepon' => '081234567890',
                'email' => 'budi@merdekajaya.com',
                'alamat' => 'Jl. Raya Industri No. 123',
                'kota' => 'Jakarta',
                'status' => 'aktif',
            ]);
        }

        // Pastikan ada barang
        $barang = Barang::first();
        if (!$barang) {
            $barang = Barang::create([
                'nama_barang' => 'Semen Gresik 50kg',
                'sku' => 'SMN-001',
                'kategori_id' => 1,
                'satuan' => 'Sak',
                'harga' => 65000,
                'stok_saat_ini' => 100,
                'deskripsi' => 'Semen Portland tipe I',
            ]);
        }

        // Pastikan ada user admin
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::where('email', 'admin@tokobangunan.com')->first();
        }

        // Buat Purchase Request
        $lastPR = PurchaseRequest::orderBy('id', 'desc')->first();
        $prNumber = $lastPR ? (intval(substr($lastPR->nomor_pr, 2)) + 1) : 1;
        
        $pr = PurchaseRequest::create([
            'nomor_pr' => 'PR' . str_pad($prNumber, 6, '0', STR_PAD_LEFT),
            'user_id' => $admin->id,
            'supplier_id' => $supplier->id,
            'barang_id' => $barang->id,
            'jumlah_diminta' => 50,
            'satuan' => $barang->satuan,
            'status' => 'approved',
            'catatan_request' => 'Stok menipis, perlu restock segera',
            'catatan_approval' => 'Disetujui untuk pembelian',
            'approved_by' => $admin->id,
            'tanggal_approval' => now(),
        ]);

        // Buat Purchase Order (PO)
        $lastPO = Purchase::orderBy('id', 'desc')->first();
        $poNumber = $lastPO ? (intval(substr($lastPO->nomor_po, -4)) + 1) : 1;
        
        $po = Purchase::create([
            'nomor_po' => 'PO-' . now()->format('Ymd') . '-' . str_pad($poNumber, 4, '0', STR_PAD_LEFT),
            'purchase_request_id' => $pr->id,
            'supplier_id' => $supplier->id,
            'barang_id' => $barang->id,
            'user_id' => $admin->id,
            'jumlah_po' => $pr->jumlah_diminta,
            'satuan' => $barang->satuan,
            'harga_unit' => $barang->harga,
            'total_harga' => $barang->harga * $pr->jumlah_diminta,
            'tanggal_pembelian' => now(),
            'status_pembayaran' => 'lunas',
            'status_pembelian' => 'ordered',
            'keterangan' => 'PO dari PR ' . $pr->nomor_pr,
        ]);

        $this->command->info('✓ Purchase Request dan Purchase Order sample berhasil dibuat');
        $this->command->info('  PR: ' . $pr->nomor_pr);
        $this->command->info('  PO: ' . $po->nomor_po . ' (Status: ' . $po->status_pembayaran . ')');
        $this->command->info('  Barang: ' . $barang->nama_barang . ' - ' . $po->jumlah_po . ' ' . $po->satuan);
    }
}
