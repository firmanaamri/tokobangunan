<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\Purchase;
use App\Models\Barang;   // <--- TAMBAHAN
use App\Models\Supplier; // <--- TAMBAHAN
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseApprovalController extends Controller
{
    public function index()
    {
        $pendingPRs = PurchaseRequest::with(['user', 'barang', 'supplier'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        $stats = [
            'pending'   => PurchaseRequest::where('status', 'pending')->count(),
            'approved'  => PurchaseRequest::where('status', 'approved')->count(),
            'rejected'  => PurchaseRequest::where('status', 'rejected')->count(),
            'completed' => PurchaseRequest::where('status', 'completed')->count(),
        ];

        return view('purchase-approvals.index', compact('pendingPRs', 'stats'));
    }

    public function show(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'PR ini sudah di-process');
        }

        $purchaseRequest->load(['user', 'barang.kategori', 'supplier']);
        
        // TAMBAHAN: Kita butuh list supplier untuk dropdown di form approval
        $suppliers = Supplier::where('status', 'aktif')->get(); 

        return view('purchase-approvals.show', compact('purchaseRequest', 'suppliers'));
    }

    public function approve(Request $request, PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'PR ini sudah di-process');
        }

        // 1. VALIDASI INPUT HARGA DEAL & SUPPLIER FINAL
        $validated = $request->validate([
            'harga_deal'       => 'required|numeric|min:0',
            'supplier_id'      => 'required|exists:suppliers,id',
            'catatan_approval' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // 2. UPDATE PR
            $purchaseRequest->update([
                'status'           => 'approved',
                'approved_by'      => Auth::id(),
                'tanggal_approval' => Carbon::now(),
                'supplier_id'      => $validated['supplier_id'], // Update supplier jika berubah
                'catatan_approval' => $validated['catatan_approval'] ?? null,
            ]);

            // 3. GENERATE PO (Kirim Harga Deal & Supplier Final)
            $po = $this->generatePO($purchaseRequest, $validated['harga_deal'], $validated['supplier_id']);

            // 4. UPDATE HARGA PASAR DI MASTER BARANG
            // Agar sistem makin pintar mencatat harga terakhir
            $barang = Barang::find($purchaseRequest->barang_id);
            if ($barang) {
                $barang->update([
                    'harga_beli_terakhir' => $validated['harga_deal']
                ]);
            }

            DB::commit();

            return redirect()->route('purchase-approvals.index')
                ->with('success', "PR Disetujui! PO #{$po->nomor_po} terbentuk dengan harga Rp " . number_format($validated['harga_deal']));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal approve PR: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'PR ini sudah di-process');
        }

        $validated = $request->validate([
            'catatan_approval' => 'required|string|max:1000',
        ]);

        $purchaseRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'tanggal_approval' => Carbon::now(),
            'catatan_approval' => $validated['catatan_approval'],
        ]);

        return redirect()->route('purchase-approvals.index')
            ->with('success', 'PR berhasil ditolak.');
    }

    // MODIFIKASI: Terima parameter Harga & Supplier
    private function generatePO(PurchaseRequest $purchaseRequest, $hargaDeal, $supplierId)
    {
        $purchaseRequest->loadMissing('barang');

        $lastPO = Purchase::orderBy('id', 'desc')->first();
        $number = 1;
        
        if ($lastPO) {
            if (preg_match('/\d+/', $lastPO->nomor_po, $matches)) {
                $number = intval($matches[0]) + 1;
            }
        }
        $nomor_po = 'PO' . str_pad($number, 6, '0', STR_PAD_LEFT);

        // HITUNG TOTAL PAKAI HARGA DEAL (BUKAN HARGA MASTER)
        $totalHarga = $hargaDeal * $purchaseRequest->jumlah_diminta;

        // Logika Due Date
        $dueDate = null;
        if (!empty($purchaseRequest->due_date)) {
            $dueDate = $purchaseRequest->due_date;
        } elseif (!empty($purchaseRequest->payment_term)) {
            $dueDate = Carbon::now()->addDays(intval($purchaseRequest->payment_term));
        }

        $po = Purchase::create([
            'nomor_po'          => $nomor_po,
            'supplier_id'       => $supplierId, // Pakai Supplier Final
            'user_id'           => Auth::id(),
            'barang_masuk_id'   => null,
            'barang_id'         => $purchaseRequest->barang_id,
            'jumlah_po'         => $purchaseRequest->jumlah_diminta,
            'satuan'            => $purchaseRequest->satuan,
            'harga_unit'        => $hargaDeal, // Pakai Harga Deal
            'total_harga'       => $totalHarga,
            'tanggal_pembelian' => Carbon::now(),
            'status_pembayaran' => 'belum_bayar',
            'status_pembelian'  => 'pending', 
            'due_date'          => $dueDate, 
            'keterangan'        => $purchaseRequest->catatan_request,
            'catatan'           => $purchaseRequest->catatan_request,
            'purchase_request_id' => $purchaseRequest->id,
        ]);

        return $po;
    }
}