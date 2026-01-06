<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('isAdmin');
        $query = BarangMasuk::with('barang');
        
        // Apply date filters
        $filter = $request->input('filter', 'all');
        
        switch ($filter) {
            case 'today':
                $query->whereDate('tanggal_masuk', today());
                break;
            case 'week':
                $query->whereBetween('tanggal_masuk', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('tanggal_masuk', now()->month)
                      ->whereYear('tanggal_masuk', now()->year);
                break;
            case 'year':
                $query->whereYear('tanggal_masuk', now()->year);
                break;
            case 'previous_year':
                $query->whereYear('tanggal_masuk', now()->year - 1);
                break;
            case 'custom':
                if ($request->has('start_date') && $request->start_date) {
                    $query->whereDate('tanggal_masuk', '>=', $request->start_date);
                }
                if ($request->has('end_date') && $request->end_date) {
                    $query->whereDate('tanggal_masuk', '<=', $request->end_date);
                }
                break;
        }
        
        $items = $query->orderBy('tanggal_masuk', 'desc')->paginate(20);
        return view('barang_masuk.index', compact('items'));
    }

    /**
     * Export barang masuk to PDF
     */
    public function exportPDF(Request $request)
    {
        $this->authorize('isAdmin');
        $query = BarangMasuk::with(['barang.kategori', 'user']);
        
        // Apply date filters
        $filter = $request->input('filter', 'all');
        $filterLabel = 'Semua Data';
        
        switch ($filter) {
            case 'today':
                $query->whereDate('tanggal_masuk', today());
                $filterLabel = 'Hari Ini - ' . today()->format('d F Y');
                break;
            case 'week':
                $query->whereBetween('tanggal_masuk', [now()->startOfWeek(), now()->endOfWeek()]);
                $filterLabel = 'Minggu Ini - ' . now()->startOfWeek()->format('d M') . ' s/d ' . now()->endOfWeek()->format('d M Y');
                break;
            case 'month':
                $query->whereMonth('tanggal_masuk', now()->month)
                      ->whereYear('tanggal_masuk', now()->year);
                $filterLabel = 'Bulan ' . now()->format('F Y');
                break;
            case 'year':
                $query->whereYear('tanggal_masuk', now()->year);
                $filterLabel = 'Tahun ' . now()->year;
                break;
            case 'previous_year':
                $query->whereYear('tanggal_masuk', now()->year - 1);
                $filterLabel = 'Tahun ' . (now()->year - 1);
                break;
            case 'custom':
                if ($request->has('start_date') && $request->start_date) {
                    $query->whereDate('tanggal_masuk', '>=', $request->start_date);
                }
                if ($request->has('end_date') && $request->end_date) {
                    $query->whereDate('tanggal_masuk', '<=', $request->end_date);
                }
                if ($request->start_date && $request->end_date) {
                    $filterLabel = \Carbon\Carbon::parse($request->start_date)->format('d M Y') . ' s/d ' . \Carbon\Carbon::parse($request->end_date)->format('d M Y');
                }
                break;
        }
        
        $items = $query->orderBy('tanggal_masuk', 'desc')->get();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('barang_masuk.pdf', compact('items', 'filterLabel'));
        return $pdf->download('laporan-barang-masuk-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::with('kategori')->orderBy('nama_barang')->get();
        $kategoris = \App\Models\Kategori::orderBy('nama_kategori')->get();
        return view('barang_masuk.create', compact('barangs', 'kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_barang_masuk' => 'required|integer|min:1',
            'tanggal_masuk' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $validated['tanggal_masuk'] = $validated['tanggal_masuk'] ?? now();
        $validated['user_id'] = Auth::id();

        $entry = BarangMasuk::create($validated);

        // update stock
        $barang = Barang::find($validated['barang_id']);
        if ($barang) {
            $barang->stok_saat_ini = ($barang->stok_saat_ini ?? 0) + $validated['jumlah_barang_masuk'];
            $barang->save();
        }

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangMasuk $barang_masuk)
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('barang_masuk.edit', ['item' => $barang_masuk, 'barangs' => $barangs]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangMasuk $barang_masuk)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_barang_masuk' => 'required|integer|min:1',
            'tanggal_masuk' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $oldJumlah = $barang_masuk->jumlah_barang_masuk;
        $barang_masuk->update($validated);

        // adjust stock (difference)
        $diff = $validated['jumlah_barang_masuk'] - $oldJumlah;
        $barang = Barang::find($validated['barang_id']);
        if ($barang) {
            $barang->stok_saat_ini = ($barang->stok_saat_ini ?? 0) + $diff;
            $barang->save();
        }

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangMasuk $barang_masuk)
    {
        // subtract from stock
        $barang = $barang_masuk->barang;
        if ($barang) {
            $barang->stok_saat_ini = max(0, ($barang->stok_saat_ini ?? 0) - ($barang_masuk->jumlah_barang_masuk ?? 0));
            $barang->save();
        }

        $barang_masuk->delete();

        return redirect()->route('barang-masuk.index')->with('success', 'Entri barang masuk dihapus.');
    }
}
