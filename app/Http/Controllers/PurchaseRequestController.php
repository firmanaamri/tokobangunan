<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of purchase requests
     */
    public function index()
    {
        $purchaseRequests = PurchaseRequest::with(['user', 'barang', 'supplier', 'approver'])
            ->latest()
            ->paginate(15);
        
        return view('purchase-requests.index', compact('purchaseRequests'));
    }

    /**
     * Show the form for creating a new purchase request
     */
    public function create()
    {
        $barangs = Barang::with('kategori')->get();
        $suppliers = Supplier::where('status', 'aktif')->get();
        
        return view('purchase-requests.create', compact('barangs', 'suppliers'));
    }

    /**
     * Store a newly created purchase request in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_diminta' => 'required|integer|min:1',
            'satuan' => 'required|string',
            'supplier_id' => 'required|exists:suppliers,id',
            'catatan_request' => 'nullable|string',
        ]);

        // Generate nomor PR
        $lastPR = PurchaseRequest::orderBy('id', 'desc')->first();
        $number = ($lastPR ? intval(substr($lastPR->nomor_pr, 2)) + 1 : 1);
        $nomor_pr = 'PR' . str_pad($number, 6, '0', STR_PAD_LEFT);

        $purchaseRequest = PurchaseRequest::create([
            'nomor_pr' => $nomor_pr,
            'user_id' => auth()->id(),
            'barang_id' => $validated['barang_id'],
            'supplier_id' => $validated['supplier_id'],
            'jumlah_diminta' => $validated['jumlah_diminta'],
            'satuan' => $validated['satuan'],
            'catatan_request' => $validated['catatan_request'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('purchase-requests.show', $purchaseRequest)
            ->with('success', 'Purchase Request berhasil dibuat!');
    }

    /**
     * Display the specified purchase request
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->load(['user', 'barang.kategori', 'supplier', 'approver']);
        
        return view('purchase-requests.show', compact('purchaseRequest'));
    }

    /**
     * Show the form for editing the specified purchase request
     */
    public function edit(PurchaseRequest $purchaseRequest)
    {
        // Only staff yang membuat bisa edit, dan hanya jika status pending
        if ($purchaseRequest->status !== 'pending' || $purchaseRequest->user_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak bisa edit PR ini');
        }

        $barangs = Barang::with('kategori')->get();
        $suppliers = Supplier::where('status', 'aktif')->get();
        
        return view('purchase-requests.edit', compact('purchaseRequest', 'barangs', 'suppliers'));
    }

    /**
     * Update the specified purchase request in storage
     */
    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending' || $purchaseRequest->user_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak bisa edit PR ini');
        }

        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_diminta' => 'required|integer|min:1',
            'satuan' => 'required|string',
            'supplier_id' => 'required|exists:suppliers,id',
            'catatan_request' => 'nullable|string',
        ]);

        $purchaseRequest->update([
            'barang_id' => $validated['barang_id'],
            'supplier_id' => $validated['supplier_id'],
            'jumlah_diminta' => $validated['jumlah_diminta'],
            'satuan' => $validated['satuan'],
            'catatan_request' => $validated['catatan_request'] ?? null,
        ]);

        return redirect()->route('purchase-requests.show', $purchaseRequest)
            ->with('success', 'Purchase Request berhasil diupdate!');
    }

    /**
     * Remove the specified purchase request from storage
     */
    public function destroy(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending' || $purchaseRequest->user_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak bisa hapus PR ini');
        }

        $purchaseRequest->delete();

        return redirect()->route('purchase-requests.index')
            ->with('success', 'Purchase Request berhasil dihapus!');
    }
}
