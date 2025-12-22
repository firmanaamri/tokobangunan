<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Sale;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Produk
        $totalProduk = Barang::count();
        
        // Total produk baru (misalnya dalam 30 hari terakhir)
        $produkBaru = Barang::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        
        // Stok Akan Habis (< 50)
        $stokHabis = Barang::where('stok_saat_ini', '<', 50)->count();
        
        // Stok Keluar Bulan Ini
        $stokKeluarBulanIni = BarangKeluar::whereYear('tanggal_keluar', Carbon::now()->year)
                                          ->whereMonth('tanggal_keluar', Carbon::now()->month)
                                          ->sum('jumlah_barang_keluar');
        
        // Aktivitas Terbaru (gabungan masuk dan keluar, diurutkan terbaru)
        $barangMasukRecent = BarangMasuk::with(['barang.kategori', 'user'])
                                       ->orderBy('tanggal_masuk', 'desc')
                                       ->limit(10)
                                       ->get()
                                       ->map(function ($item) {
                                           return [
                                               'tanggal' => $item->tanggal_masuk,
                                               'nama_barang' => $item->barang->nama_barang,
                                               'kategori' => $item->barang->kategori?->nama_kategori,
                                               'tipe' => 'Stok Masuk',
                                               'jumlah' => '+' . $item->jumlah_barang_masuk . ' ' . ($item->barang->satuan ?? 'pcs'),
                                               'oleh' => $item->user?->name ?? 'Admin Gudang',
                                           ];
                                       });
        
        $barangKeluarRecent = BarangKeluar::with(['barang.kategori', 'user'])
                                         ->orderBy('tanggal_keluar', 'desc')
                                         ->limit(10)
                                         ->get()
                                         ->map(function ($item) {
                                           return [
                                               'tanggal' => $item->tanggal_keluar,
                                               'nama_barang' => $item->barang->nama_barang,
                                               'kategori' => $item->barang->kategori?->nama_kategori,
                                               'tipe' => 'Stok Keluar',
                                               'jumlah' => '-' . $item->jumlah_barang_keluar . ' ' . ($item->barang->satuan ?? 'pcs'),
                                               'oleh' => $item->user?->name ?? 'Staf Proyek',
                                           ];
                                       });
        
        // Merge dan sort terbaru dulu
        $aktivitas = collect($barangMasukRecent)->merge($barangKeluarRecent)
                                               ->sortByDesc('tanggal')
                                               ->take(10);

        // Sales metrics
        $totalSales = Sale::count();
        $salesThisMonth = Sale::whereYear('created_at', Carbon::now()->year)
                              ->whereMonth('created_at', Carbon::now()->month)
                              ->count();

        $revenueThisMonth = Sale::whereYear('created_at', Carbon::now()->year)
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->sum('total');
        
        return view('admindash', compact(
            'totalProduk',
            'produkBaru',
            'stokHabis',
            'stokKeluarBulanIni',
            'aktivitas',
            'totalSales',
            'salesThisMonth',
            'revenueThisMonth'
        ));
    }
}
