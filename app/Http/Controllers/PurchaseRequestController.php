<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestController extends Controller
{
    /**
     * Menampilkan daftar PR (Monitoring)
     */
    public function index(Request $request)
    {
        $query = PurchaseRequest::with(['barang', 'supplier', 'user'])
            ->latest();

        // Filter Pencarian
        if ($request->has('search') && $request->search) {
            $query->where('nomor_pr', 'like', "%{$request->search}%");
        }

        // Filter Status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $purchaseRequests = $query->paginate(10);

        return view('purchase-requests.index', compact('purchaseRequests'));
    }

    /**
     * Form Pengajuan Baru
     */
    public function create()
    {
        $barangs = Barang::all();
        $suppliers = Supplier::where('status', 'aktif')->get();
        
        // Generate Nomor PR Otomatis untuk tampilan (Opsional, karena di-generate ulang saat store)
        $today = date('Ymd');
        $count = PurchaseRequest::whereDate('created_at', today())->count() + 1;
        $nomorPR = 'PR-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        return view('purchase-requests.create', compact('barangs', 'suppliers', 'nomorPR'));
    }

    /**
     * Simpan Pengajuan ke Database
     */
    public function store(Request $request)
    {
        // Validasi input standar (Tanpa Harga)
        $request->validate([
            'barang_id'       => 'required|exists:barang,id',
            'supplier_id'     => 'required|exists:suppliers,id',
            'jumlah_diminta'  => 'required|integer|min:1',
            'payment_term'    => 'nullable|integer',
            'due_date'        => 'nullable|date',
            'catatan_request' => 'nullable|string'
        ]);

        // Generate Nomor PR Otomatis
        $today = date('Ymd');
        $count = PurchaseRequest::whereDate('created_at', today())->count() + 1;
        $nomorPR = 'PR-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $barang = Barang::find($request->barang_id);

        PurchaseRequest::create([
            'nomor_pr'        => $nomorPR,
            'user_id'         => Auth::id(),
            'barang_id'       => $request->barang_id,
            'supplier_id'     => $request->supplier_id, // Supplier yang diajukan staff
            'jumlah_diminta'  => $request->jumlah_diminta,
            'satuan'          => $request->satuan ?? $barang->satuan,
            'payment_term'    => $request->payment_term,
            'due_date'        => $request->due_date,
            'catatan_request' => $request->catatan_request,
            'status'          => 'pending' // Default status selalu pending
        ]);

        return redirect()->route('purchase-requests.index')->with('success', 'Permintaan pembelian berhasil diajukan. Menunggu persetujuan.');
    }

    /**
     * Lihat Detail PR
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        return view('purchase-requests.show', compact('purchaseRequest'));
    }

    /**
     * Edit Pengajuan (Hanya jika masih pending)
     */
    public function edit(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'PR yang sudah diproses tidak bisa diedit.');
        }

        $barangs = Barang::all();
        $suppliers = Supplier::where('status', 'aktif')->get();
        return view('purchase-requests.edit', compact('purchaseRequest', 'barangs', 'suppliers'));
    }

    /**
     * Update Pengajuan
     */
    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'PR yang sudah diproses tidak bisa diedit.');
        }

        $request->validate([
            'barang_id'      => 'required|exists:barang,id',
            'supplier_id'    => 'required|exists:suppliers,id',
            'jumlah_diminta' => 'required|integer|min:1',
        ]);

        $purchaseRequest->update([
            'barang_id'       => $request->barang_id,
            'supplier_id'     => $request->supplier_id,
            'jumlah_diminta'  => $request->jumlah_diminta,
            'payment_term'    => $request->payment_term,
            'due_date'        => $request->due_date,
            'catatan_request' => $request->catatan_request,
        ]);

        return redirect()->route('purchase-requests.index')->with('success', 'Pengajuan berhasil diperbarui.');
    }

    /**
     * Hapus Pengajuan (Hanya jika masih pending)
     */
    public function destroy(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'Hanya PR status pending yang bisa dihapus.');
        }

        $purchaseRequest->delete();
        return redirect()->route('purchase-requests.index')->with('success', 'Pengajuan berhasil dihapus.');
    }
}