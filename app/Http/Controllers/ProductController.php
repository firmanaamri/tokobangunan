<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage; 

class ProductController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'barangMasuk', 'barangKeluar']);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('satuan', 'like', "%{$search}%");
        }

        $products = $query->withSum('barangMasuk', 'jumlah_barang_masuk')
                  ->withSum('barangKeluar', 'jumlah_barang_keluar')
                  ->paginate(10);

        return view('stokbarang', compact('products'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_barang' => 'required|string|max:255',
            'sku'         => 'required|string|unique:barang,sku',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan'      => 'required|string|max:50',
            'harga'       => 'nullable|numeric|min:0', // Harga Jual
            'harga_beli_terakhir' => 'nullable|numeric|min:0', // <--- BARU: Harga Beli (Opsional saat create)
            'deskripsi'   => 'nullable|string',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $messages = [
            'sku.unique'   => 'SKU sudah digunakan. Gunakan SKU lain yang unik.',
            'sku.required' => 'SKU wajib diisi.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max'   => 'Ukuran gambar maksimal 2MB.',
        ];

        $validated = $request->validate($rules, $messages);

        // --- PROSES UPLOAD GAMBAR BARU ---
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('produk', 'public');
            $validated['gambar'] = $path;
        }

        // Set stok awal 0
        $validated['stok_saat_ini'] = 0;

        Barang::create($validated);

        return redirect()->route('barang')
                ->with('success', 'Barang berhasil ditambahkan');
    }

    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'barangMasuk', 'barangKeluar']);
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

   public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'sku'         => 'required|string|unique:barang,sku,' . $barang->id,
            'kategori_id' => 'required|exists:kategori,id',
            'satuan'      => 'required|string|max:50',
            'harga'       => 'nullable|numeric|min:0', // Harga Jual
            'harga_beli_terakhir' => 'nullable|numeric|min:0', // <--- BARU: Update Harga Beli Manual (Jika perlu)
            'deskripsi'   => 'nullable|string',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'gambar.max'   => 'Ukuran foto terlalu besar! Maksimal adalah 2MB.',
            'gambar.image' => 'File yang diupload harus berupa gambar (JPG, PNG).',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
        ]);

        // Hindari update stok manual dari form edit produk
        unset($validated['stok_saat_ini']);

        // --- LOGIKA HAPUS ATAU GANTI GAMBAR ---
        if ($request->has('hapus_gambar')) {
            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $validated['gambar'] = null;
        }

        if ($request->hasFile('gambar')) {
            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }
            
            $path = $request->file('gambar')->store('produk', 'public');
            $validated['gambar'] = $path;
        }

        $barang->update($validated);

        return redirect()->route('barang.show', $barang)
                        ->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()->route('barang')
                ->with('success', 'Barang berhasil dihapus');
    }
}