<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailySaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $q = $request->input('q');

        $items = BarangKeluar::with('barang', 'user')
            ->whereDate('tanggal_keluar', $date)
            ->when($q, function ($query) use ($q) {
                $query->whereHas('barang', function ($b) use ($q) {
                    $b->where('nama_barang', 'like', "%{$q}%");
                });
            })
            ->orderBy('tanggal_keluar', 'desc')
            ->get();

        // Rekap per barang untuk tanggal yang dipilih
        $records = BarangKeluar::selectRaw('barang_id, SUM(jumlah_barang_keluar) as total_jumlah, MAX(created_at) as last_recorded')
            ->whereDate('tanggal_keluar', $date)
            ->when($q, function ($query) use ($q) {
                $query->whereHas('barang', function ($b) use ($q) {
                    $b->where('nama_barang', 'like', "%{$q}%");
                });
            })
            ->groupBy('barang_id')
            ->with('barang')
            ->get()
            ->each(function ($record) {
                $record->last_recorded = Carbon::parse($record->last_recorded);
            });

        $totalPortions = $records->sum('total_jumlah');
        $typesCount = $records->count();

        return view('daily-sales.index', compact('items', 'date', 'q', 'records', 'totalPortions', 'typesCount'));
    }

    public function recap(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $q = $request->input('q');

        $records = BarangKeluar::selectRaw('barang_id, SUM(jumlah_barang_keluar) as total_jumlah, MAX(created_at) as last_recorded')
            ->whereDate('tanggal_keluar', $date)
            ->when($q, function ($query) use ($q) {
                $query->whereHas('barang', function ($b) use ($q) {
                    $b->where('nama_barang', 'like', "%{$q}%");
                });
            })
            ->groupBy('barang_id')
            ->with('barang')
            ->get()
            ->each(function ($record) {
                $record->last_recorded = Carbon::parse($record->last_recorded);
            });

        $totalPortions = $records->sum('total_jumlah');
        $typesCount = $records->count();

        return view('daily-sales.recap', compact('records', 'date', 'q', 'totalPortions', 'typesCount'));
    }

    public function create()
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('daily-sales.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barang,id',
            'jumlah.*' => 'required|integer|min:1',
            'tanggal_keluar' => 'nullable|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $barangIds = $request->input('barang_id', []);
        $jumlahs = $request->input('jumlah', []);
        $tanggal = $request->input('tanggal_keluar') ?: now()->toDateString();
        $keterangan = $request->input('keterangan');

        // --- TAHAP 1: HITUNG TOTAL PERMINTAAN PER BARANG ---
        // Kita gabungkan dulu jumlahnya jika user memilih barang yang sama di baris berbeda
        $totalPermintaan = [];
        
        foreach ($barangIds as $index => $id) {
            $qty = (int) ($jumlahs[$index] ?? 0);
            if ($qty > 0) {
                if (!isset($totalPermintaan[$id])) {
                    $totalPermintaan[$id] = 0;
                }
                $totalPermintaan[$id] += $qty;
            }
        }

        // --- TAHAP 2: VALIDASI STOK BERDASARKAN TOTAL ---
        // Cek apakah total permintaan melebihi stok database
        foreach ($totalPermintaan as $id => $totalMinta) {
            $barang = Barang::find($id);

            // Jika barang tidak ada atau stok kurang dari total yang diminta
            if (!$barang || $barang->stok_saat_ini < $totalMinta) {
                $stokTersedia = $barang ? $barang->stok_saat_ini : 0;
                $namaBarang = $barang ? $barang->nama_barang : 'Unknown';

                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'stok' => "Gagal: Total permintaan untuk '{$namaBarang}' adalah {$totalMinta}, tapi stok hanya tersedia {$stokTersedia}."
                    ]);
            }
        }

        // --- TAHAP 3: SIMPAN KE DATABASE ---
        // Gunakan Transaction agar jika satu gagal, semua batal (Data Integrity)
        DB::transaction(function () use ($barangIds, $jumlahs, $tanggal, $keterangan) {
            foreach ($barangIds as $index => $barangId) {
                $jumlah = (int) ($jumlahs[$index] ?? 0);
                
                if ($jumlah <= 0) continue;

                $barang = Barang::lockForUpdate()->find($barangId); // Lock baris agar tidak ditimpa user lain bersamaan
                
                if ($barang) {
                    // Create BarangKeluar
                    BarangKeluar::create([
                        'barang_id' => $barang->id,
                        'jumlah_barang_keluar' => $jumlah,
                        'tanggal_keluar' => $tanggal,
                        'keterangan' => $keterangan,
                        'user_id' => Auth::id(),
                    ]);

                    // Kurangi stok
                    $barang->decrement('stok_saat_ini', $jumlah);
                }
            }
        });

        return redirect()->route('daily-sales.index')->with('success', 'Pencatatan penjualan tersimpan.');
    }

    public function show(BarangKeluar $daily_sale)
    {
        return view('daily-sales.show', ['item' => $daily_sale]);
    }
}