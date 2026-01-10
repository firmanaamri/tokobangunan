<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
// use App\Models\Sale;
use App\Models\Payment;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Staff Dashboard
        if ($user->isStaff()) {
            return $this->staffDashboard();
        }

        // Admin Dashboard
        return $this->adminDashboard();
    }

    private function adminDashboard()
    {
        // Total Produk
        $totalProduk = Barang::count();
        
        // Total produk baru (misalnya dalam 30 hari terakhir)
        $produkBaru = Barang::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        
        // Stok Akan Habis (< 50)
        $stokHabis = Barang::where('stok_saat_ini', '<', 50)->count();
        
        // Total Supplier Aktif
        $totalSuppliers = Supplier::where('status', 'aktif')->count();
        
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

        // Barang Keluar metrics (for dashboard cards)
        $totalBarangKeluar = BarangKeluar::count();
        $barangKeluarThisMonth = BarangKeluar::whereYear('tanggal_keluar', Carbon::now()->year)
                                    ->whereMonth('tanggal_keluar', Carbon::now()->month)
                                    ->sum('jumlah_barang_keluar');
        $barangKeluarToday = BarangKeluar::whereDate('tanggal_keluar', Carbon::today())->count();

        return view('admindash', compact(
            'totalProduk',
            'produkBaru',
            'stokHabis',
            'totalSuppliers',
            'aktivitas',
            'totalBarangKeluar',
            'barangKeluarThisMonth',
            'barangKeluarToday'
        ));
    }

    private function staffDashboard()
    {
        $userId = auth()->id();

        // Staff PR Stats
        $myPRs = PurchaseRequest::where('user_id', $userId)->count();
        $pendingPRs = PurchaseRequest::where('user_id', $userId)->where('status', 'pending')->count();
        $approvedPRs = PurchaseRequest::where('user_id', $userId)->where('status', 'approved')->count();

        // Sales Today
        // $salesToday = Sale::whereDate('created_at', Carbon::today())->count();
        // $revenueToday = Sale::whereDate('created_at', Carbon::today())->sum('total');

        // Product Stats
        $totalProduk = Barang::count();
        $produkBaru = Barang::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $stokHabis = Barang::where('stok_saat_ini', '<', 50)->count();
        $totalSuppliers = Supplier::where('status', 'aktif')->count();

        // Barang Keluar metrics (match admin cards)
        $totalBarangKeluar = BarangKeluar::count();
        $barangKeluarThisMonth = BarangKeluar::whereYear('tanggal_keluar', Carbon::now()->year)
                                    ->whereMonth('tanggal_keluar', Carbon::now()->month)
                                    ->sum('jumlah_barang_keluar');
        $barangKeluarToday = BarangKeluar::whereDate('tanggal_keluar', Carbon::today())->count();

        // Recent PRs
        $recentPRs = PurchaseRequest::with(['barang', 'supplier'])
                                    ->where('user_id', $userId)
                                    ->latest()
                                    ->limit(5)
                                    ->get();

        // Recent Activities (simplified for staff)
        $barangMasukRecent = BarangMasuk::with(['barang.kategori', 'user'])
                                       ->orderBy('tanggal_masuk', 'desc')
                                       ->limit(5)
                                       ->get()
                                       ->map(function ($item) {
                                           return [
                                               'tanggal' => $item->tanggal_masuk,
                                               'nama_barang' => $item->barang->nama_barang,
                                               'tipe' => 'Stok Masuk',
                                               'jumlah' => '+' . $item->jumlah_barang_masuk . ' ' . ($item->barang->satuan ?? 'pcs'),
                                               'oleh' => $item->user?->name ?? 'Admin Gudang',
                                           ];
                                       });
        
        $barangKeluarRecent = BarangKeluar::with(['barang.kategori', 'user'])
                                         ->orderBy('tanggal_keluar', 'desc')
                                         ->limit(5)
                                         ->get()
                                         ->map(function ($item) {
                                           return [
                                               'tanggal' => $item->tanggal_keluar,
                                               'nama_barang' => $item->barang->nama_barang,
                                               'tipe' => 'Stok Keluar',
                                               'jumlah' => '-' . $item->jumlah_barang_keluar . ' ' . ($item->barang->satuan ?? 'pcs'),
                                               'oleh' => $item->user?->name ?? 'Staf Proyek',
                                           ];
                                       });
        
        $aktivitas = collect($barangMasukRecent)->merge($barangKeluarRecent)
                                               ->sortByDesc('tanggal')
                                               ->take(10);

        return view('staffdash', compact(
            'myPRs',
            'pendingPRs',
            'approvedPRs',
            
            
            'totalProduk',
            'stokHabis',
            'produkBaru',
            'totalSuppliers',
            'totalBarangKeluar',
            'barangKeluarThisMonth',
            'barangKeluarToday',
            'recentPRs',
            'aktivitas'
        ));
    }
}
