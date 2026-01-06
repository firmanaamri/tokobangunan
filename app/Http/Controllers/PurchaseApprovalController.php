<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PurchaseApprovalController extends Controller
{
    /**
     * Show pending PRs for approval
     */
    public function index()
    {
        $this->authorize('isAdmin');

        $pendingPRs = PurchaseRequest::with(['user', 'barang', 'supplier'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        $stats = [
            'pending' => PurchaseRequest::where('status', 'pending')->count(),
            'approved' => PurchaseRequest::where('status', 'approved')->count(),
            'rejected' => PurchaseRequest::where('status', 'rejected')->count(),
            'completed' => PurchaseRequest::where('status', 'completed')->count(),
        ];

        return view('purchase-approvals.index', compact('pendingPRs', 'stats'));
    }

    /**
     * Show approval form
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        $this->authorize('isAdmin');

        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'PR ini sudah di-process');
        }

        $purchaseRequest->load(['user', 'barang.kategori', 'supplier']);

        return view('purchase-approvals.show', compact('purchaseRequest'));
    }

    /**
     * Approve PR and auto-generate PO
     */
    public function approve(Request $request, PurchaseRequest $purchaseRequest)
    {
        $this->authorize('isAdmin');

        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'PR ini sudah di-process');
        }

        $validated = $request->validate([
            'catatan_approval' => 'nullable|string|max:1000',
        ]);

        try {
            // Start transaction
            \DB::beginTransaction();

            // Update PR status
            $purchaseRequest->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'tanggal_approval' => Carbon::now(),
                'catatan_approval' => $validated['catatan_approval'] ?? null,
            ]);

            // Auto-generate PO from approved PR
            $po = $this->generatePO($purchaseRequest);

            \DB::commit();

            return redirect()->route('purchase-approvals.index')
                ->with('success', "PR berhasil disetujui! PO #{$po->nomor_po} otomatis dibuat.");
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal approve PR: ' . $e->getMessage());
        }
    }

    /**
     * Reject PR
     */
    public function reject(Request $request, PurchaseRequest $purchaseRequest)
    {
        $this->authorize('isAdmin');

        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'PR ini sudah di-process');
        }

        $validated = $request->validate([
            'catatan_approval' => 'required|string|max:1000',
        ]);

        $purchaseRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'tanggal_approval' => Carbon::now(),
            'catatan_approval' => $validated['catatan_approval'],
        ]);

        return redirect()->route('purchase-approvals.index')
            ->with('success', 'PR berhasil ditolak.');
    }

    /**
     * Generate PO from approved PR
     */
    private function generatePO(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->loadMissing('barang');

        // Generate nomor PO
        $lastPO = Purchase::orderBy('id', 'desc')->first();
        $number = ($lastPO ? intval(substr($lastPO->nomor_po, 2)) + 1 : 1);
        $nomor_po = 'PO' . str_pad($number, 6, '0', STR_PAD_LEFT);

        $hargaUnit = $purchaseRequest->barang?->harga ?? 0;
        $jumlah = $purchaseRequest->jumlah_diminta;
        $totalHarga = $hargaUnit * $jumlah;

        // Create Purchase record from PR
        $po = Purchase::create([
            'nomor_po' => $nomor_po,
            'supplier_id' => $purchaseRequest->supplier_id,
            'user_id' => auth()->id(),
            'barang_masuk_id' => null,
            'barang_id' => $purchaseRequest->barang_id,
            'jumlah_po' => $jumlah,
            'satuan' => $purchaseRequest->satuan,
            'harga_unit' => $hargaUnit,
            'total_harga' => $totalHarga,
            'tanggal_pembelian' => Carbon::now(),
            'status_pembayaran' => 'belum_bayar',
            'status_pembelian' => 'pending',
            'keterangan' => $purchaseRequest->catatan_request,
            'catatan' => $purchaseRequest->catatan_request,
            'purchase_request_id' => $purchaseRequest->id,
        ]);

        return $po;
    }
}
