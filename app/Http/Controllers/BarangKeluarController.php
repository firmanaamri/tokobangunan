<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = BarangKeluar::with('barang')->orderBy('tanggal_keluar', 'desc')->paginate(20);
        return view('barang_keluar.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::with('kategori')->orderBy('nama_barang')->get();
        $kategoris = \App\Models\Kategori::orderBy('nama_kategori')->get();
        return view('barang_keluar.create', compact('barangs', 'kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_barang_keluar' => 'required|integer|min:1',
            'tanggal_keluar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $validated['tanggal_keluar'] = $validated['tanggal_keluar'] ?? now();
           $validated['user_id'] = auth()->id();

        // Check if stock is sufficient
        $barang = Barang::find($validated['barang_id']);
        if ($barang && ($barang->stok_saat_ini ?? 0) < $validated['jumlah_barang_keluar']) {
            return back()->withErrors(['jumlah_barang_keluar' => 'Stok tidak cukup untuk pengeluaran ini.']);
        }

        $entry = BarangKeluar::create($validated);

        // Decrement stock
        if ($barang) {
            $barang->stok_saat_ini = max(0, ($barang->stok_saat_ini ?? 0) - $validated['jumlah_barang_keluar']);
            $barang->save();
        }

        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangKeluar $barang_keluar)
    {
        $barangs = Barang::with('kategori')->orderBy('nama_barang')->get();
        $kategoris = \App\Models\Kategori::orderBy('nama_kategori')->get();
        return view('barang_keluar.edit', ['item' => $barang_keluar, 'barangs' => $barangs, 'kategoris' => $kategoris]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangKeluar $barang_keluar)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_barang_keluar' => 'required|integer|min:1',
            'tanggal_keluar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $oldJumlah = $barang_keluar->jumlah_barang_keluar;
        $barang = Barang::find($validated['barang_id']);

        // Check if stock would be sufficient after adjustment
        $diff = $validated['jumlah_barang_keluar'] - $oldJumlah;
        if ($barang && (($barang->stok_saat_ini ?? 0) + $oldJumlah - $validated['jumlah_barang_keluar']) < 0) {
            return back()->withErrors(['jumlah_barang_keluar' => 'Stok tidak cukup untuk perubahan ini.']);
        }

        $barang_keluar->update($validated);

        // Adjust stock (add back the old amount, subtract the new amount)
        if ($barang) {
            $barang->stok_saat_ini = max(0, ($barang->stok_saat_ini ?? 0) + $oldJumlah - $validated['jumlah_barang_keluar']);
            $barang->save();
        }

        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangKeluar $barang_keluar)
    {
        // Add back to stock
        $barang = $barang_keluar->barang;
        if ($barang) {
            $barang->stok_saat_ini = ($barang->stok_saat_ini ?? 0) + ($barang_keluar->jumlah_barang_keluar ?? 0);
            $barang->save();
        }

        $barang_keluar->delete();

        return redirect()->route('barang-keluar.index')->with('success', 'Entri barang keluar dihapus.');
    }
}
