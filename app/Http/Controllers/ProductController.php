<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage; // PENTING: Jangan lupa import ini

class ProductController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'barangMasuk', 'barangKeluar']);

        // Search berdasarkan nama atau SKU
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('satuan', 'like', "%{$search}%");
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
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi Gambar
        ];

        $messages = [
            'sku.unique' => 'SKU sudah digunakan. Gunakan SKU lain yang unik.',
            'sku.required' => 'SKU wajib diisi.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ];

        $validated = $request->validate($rules, $messages);

        // --- PROSES UPLOAD GAMBAR BARU ---
        if ($request->hasFile('gambar')) {
            // Simpan ke folder 'storage/app/public/produk'
            $path = $request->file('gambar')->store('produk', 'public');
            $validated['gambar'] = $path;
        }

        // Ensure initial stock is 0 on creation
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
        'deskripsi' => 'nullable|string',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        'gambar.max'   => 'Ukuran foto terlalu besar! Maksimal adalah 2MB.',
        'gambar.image' => 'File yang diupload harus berupa gambar (JPG, PNG).',
        'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
    ]);

    // Hindari update stok manual dari form
    unset($validated['stok_saat_ini']);

    // --- LOGIKA HAPUS ATAU GANTI GAMBAR ---

    // 1. Cek jika user mencentang 'Hapus Foto'
    if ($request->has('hapus_gambar')) {
        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }
        $validated['gambar'] = null; // Set kolom gambar di DB menjadi null
    }

    // 2. Cek jika ada file gambar baru yang diunggah
    if ($request->hasFile('gambar')) {
        // Hapus gambar lama jika ada (agar tidak memenuhi storage)
        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }
        
        // Simpan gambar baru
        $path = $request->file('gambar')->store('produk', 'public');
        $validated['gambar'] = $path;
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
        // --- HAPUS GAMBAR DARI STORAGE SAAT BARANG DIHAPUS ---
        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()->route('barang')
                ->with('success', 'Barang berhasil dihapus');
    }
}