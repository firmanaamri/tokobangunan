<?php

namespace App\Http\Controllers;

use App\Models\Quarantine;
use App\Models\Barang;
use Illuminate\Http\Request;

class QuarantineController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $supplierId = $request->get('supplier_id');
        $q = $request->get('q');

        $query = Quarantine::with(['barang', 'barangMasuk.purchase']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->whereHas('barang', function($b) use ($q) {
                    $b->where('nama_barang', 'like', "%{$q}%");
                })->orWhereHas('barangMasuk.purchase', function($p) use ($q) {
                    $p->where('nomor_po', 'like', "%{$q}%");
                })->orWhere('reason', 'like', "%{$q}%");
            });
        }

        $quarantines = $query->latest()->paginate(15)->appends($request->query());

        // Suppliers list for filter (use actual column name)
        $suppliers = \App\Models\Supplier::orderBy('nama_supplier')->get();

        return view('quarantines.index', compact('quarantines', 'suppliers'));
    }

    public function show(Quarantine $quarantine)
    {
        $quarantine->load(['barang', 'barangMasuk.purchase']);
        return view('quarantines.show', compact('quarantine'));
    }

    public function update(Request $request, Quarantine $quarantine)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,returned,repaired,disposed',
            'note' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $quarantine->status;
        $quarantine->status = $data['status'];
        if (isset($data['note'])) $quarantine->note = $data['note'];
        $quarantine->save();

        // If marked repaired, add back to stock
        if ($oldStatus !== 'repaired' && $data['status'] === 'repaired') {
            if ($quarantine->barang_id && $quarantine->quantity > 0) {
                $barang = Barang::find($quarantine->barang_id);
                if ($barang) {
                    $barang->increment('stok_saat_ini', $quarantine->quantity);
                }
            }
        }

        return redirect()->route('admin.quarantines.index')->with('success', 'Status karantina diperbarui.');
    }
}
