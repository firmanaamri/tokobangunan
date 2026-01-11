<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                $record->last_recorded = \Carbon\Carbon::parse($record->last_recorded);
            });

        $totalPortions = $records->sum('total_jumlah');
        $typesCount = $records->count();

        return view('daily-sales.index', compact('items', 'date', 'q', 'records', 'totalPortions', 'typesCount'));
    }

    /**
     * Rekap penjualan per menu untuk tanggal tertentu.
     */
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
                $record->last_recorded = \Carbon\Carbon::parse($record->last_recorded);
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
        $data = $request->validate([
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barang,id',
            'jumlah.*' => 'required|integer|min:1',
            'tanggal_keluar' => 'nullable|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $tanggal = $request->input('tanggal_keluar') ?: now()->toDateString();

        foreach ($data['barang_id'] as $index => $barangId) {
            $jumlah = (int) ($data['jumlah'][$index] ?? 0);
            if ($jumlah <= 0) {
                continue;
            }

            $barang = Barang::find($barangId);
            if (! $barang) {
                continue;
            }

            // Create BarangKeluar
            BarangKeluar::create([
                'barang_id' => $barang->id,
                'jumlah_barang_keluar' => $jumlah,
                'tanggal_keluar' => $tanggal,
                'keterangan' => $data['keterangan'] ?? null,
                'user_id' => Auth::id(),
            ]);

            // Decrease stock safely
            $barang->stok_saat_ini = max(0, ($barang->stok_saat_ini ?? 0) - $jumlah);
            $barang->save();
        }

        return redirect()->route('daily-sales.index')->with('success', 'Pencatatan penjualan tersimpan.');
    }

    public function show(BarangKeluar $daily_sale)
    {
        // optional: show a specific barang keluar record
        return view('daily-sales.show', ['item' => $daily_sale]);
    }
}
