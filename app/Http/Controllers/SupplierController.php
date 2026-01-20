<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Tampilkan semua supplier
     */
    public function index()
    {
        $suppliers = Supplier::paginate(15);
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Form create supplier baru
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Simpan supplier baru
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_supplier' => 'required|string|max:255|unique:suppliers',
            'kontak_person' => 'nullable|string|max:255',
            // Hanya angka untuk nomor telepon, maksimal 14 digit
            'nomor_telepon' => ['nullable','regex:/^\d+$/','max:14'],
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            // Kode pos: hanya angka, maksimal 5 digit
            'kode_pos' => ['nullable','digits_between:1,5'],
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ];
        $messages = [
            'nomor_telepon.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'nomor_telepon.max' => 'Nomor telepon maksimal 14 angka.',
            'kode_pos.digits_between' => 'Kode pos harus berupa angka dan maksimal 5 digit.',
        ];
        $validated = $request->validate($rules, $messages);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }

    /**
     * Tampilkan detail supplier
     */
    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Form edit supplier
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update supplier
     */
    public function update(Request $request, Supplier $supplier)
    {
        $rules = [
            'nama_supplier' => 'required|string|max:255|unique:suppliers,nama_supplier,' . $supplier->id,
            'kontak_person' => 'nullable|string|max:255',
            // Hanya angka untuk nomor telepon, maksimal 14 digit
            'nomor_telepon' => ['nullable','regex:/^\d+$/','max:14'],
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            // Kode pos: hanya angka, maksimal 5 digit
            'kode_pos' => ['nullable','digits_between:1,5'],
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ];
        $messages = [
            'nomor_telepon.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'nomor_telepon.max' => 'Nomor telepon maksimal 14 angka.',
            'kode_pos.digits_between' => 'Kode pos harus berupa angka dan maksimal 5 digit.',
        ];
        $validated = $request->validate($rules, $messages);

        $supplier->update($validated);

        return redirect()->route('suppliers.show', $supplier->id)
            ->with('success', 'Supplier berhasil diperbarui');
    }

    /**
     * Hapus supplier
     */
    public function destroy(Supplier $supplier)
    {
        $namaSupplier = $supplier->nama_supplier;
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier ' . $namaSupplier . ' berhasil dihapus');
    }
}
