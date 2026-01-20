<?php


namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\Kategori;

class ProductController extends Controller
{
    
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
                  ->paginate(10);

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
        $rules = [
            'nama_barang' => 'required|string|max:255',
            'sku' => 'required|string|unique:barang,sku',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan' => 'required|string|max:50',
            'harga' => 'nullable|numeric|min:0',
            //'stok_saat_ini' => 'required|integer|min:0', // stok awal diatur otomatis (0)
            'deskripsi' => 'nullable|string',
        ];

        $messages = [
            'sku.unique' => 'SKU sudah digunakan. Gunakan SKU lain yang unik.',
            'sku.required' => 'SKU wajib diisi.',
        ];

        $validated = $request->validate($rules, $messages);

        // Ensure initial stock is 0 on creation; stock will change only via purchases or barang masuk/keluar
        $validated['stok_saat_ini'] = 0;

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
            //'stok_saat_ini' => 'required|integer|min:0', // jangan izinkan edit stok di form
            'deskripsi' => 'nullable|string',
        ]);

        // Prevent direct update of stok_saat_ini from edit form
        if (array_key_exists('stok_saat_ini', $validated)) {
            unset($validated['stok_saat_ini']);
        }

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