<?php


namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\Kategori;

class ProductController extends Controller
{
    /**
     * Display a listing of products with stock information.
     */
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'barangMasuk', 'barangKeluar']);

        // Search berdasarkan nama atau SKU
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        }

        // Load relasi dengan sum aggregates
        $products = $query->withSum('barangMasuk', 'jumlah_barang_masuk')
                  ->withSum('barangKeluar', 'jumlah_barang_keluar')
                  ->paginate(15);

        return view('stokbarang', compact('products'));
    }

    

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'sku' => 'required|string|unique:barang',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan' => 'required|string|max:50',
            'harga' => 'nullable|numeric|min:0',
            'stok_saat_ini' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $new = Barang::create($validated);

        return redirect()->route('barang')
                ->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Display the specified product with detail.
     */
    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'barangMasuk', 'barangKeluar']);
        return view('barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'sku' => 'required|string|unique:barang,sku,' . $barang->id,
            'kategori_id' => 'required|exists:kategori,id',
            'satuan' => 'required|string|max:50',
            'harga' => 'nullable|numeric|min:0',
            'stok_saat_ini' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $barang->update($validated);

        return redirect()->route('barang.show', $barang)
                ->with('success', 'Barang berhasil diperbarui');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barang')
                ->with('success', 'Barang berhasil dihapus');
    }
}