<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\BarangMasuk;
use App\Models\Supplier;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Tampilkan semua transaksi pembelian
     */
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'barangMasuk.barang', 'barang', 'user'])
            ->orderBy('tanggal_pembelian', 'desc')
            ->paginate(15);

        return view('purchases.index', compact('purchases'));
    }

    /**
     * Form create transaksi pembelian
     */
    public function create($purchaseRequestId = null)
    {
        // Purchases must be created from an approved Purchase Request (PR)
        if (!$purchaseRequestId) {
            return redirect()->route('purchase-requests.index')
                ->with('error', 'Pembelian hanya dapat dibuat dari Purchase Request yang sudah disetujui.');
        }

        $pr = \App\Models\PurchaseRequest::with('barang')->find($purchaseRequestId);
        if (!$pr) {
            return redirect()->route('purchase-requests.index')
                ->with('error', 'Purchase Request tidak ditemukan.');
        }

        if ($pr->status !== 'approved') {
            return redirect()->route('purchase-requests.show', $pr->id)
                ->with('error', 'Purchase Request belum disetujui. Hanya PR yang disetujui yang dapat dibuat pembeliannya.');
        }

        // Prefill form values from PR
        $barangMasuk = null;
        $suppliers = Supplier::where('status', 'aktif')->get();
        $prefill = [
            'barang' => $pr->barang,
            'jumlah' => $pr->jumlah_diminta,
            'satuan' => $pr->satuan,
            'supplier_id' => $pr->supplier_id,
            'purchase_request_id' => $pr->id,
        ];

        return view('purchases.create', compact('barangMasuk', 'suppliers', 'prefill'));
    }

    /**
     * Simpan transaksi pembelian baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_request_id' => 'required|exists:purchase_requests,id',
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'harga_per_unit' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'total_harga' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_pembelian',
            'keterangan' => 'nullable|string',
        ]);

        // Ensure PR exists and is approved
        $pr = \App\Models\PurchaseRequest::find($validated['purchase_request_id']);
        if (!$pr || $pr->status !== 'approved') {
            return back()->withInput()->with('error', 'Purchase Request tidak valid atau belum disetujui.');
        }

        DB::beginTransaction();
        try {
            // NOTE: Do NOT update stock here. Stock should only change when
            // barang diterima (Goods Receipt). We'll store the Purchase record
            // and let the GoodsReceiptController create BarangMasuk and
            // increment stock upon actual receipt.
            $barang = \App\Models\Barang::find($validated['barang_id']);

            // Generate nomor PO (gunakan count pada kolom nomor_po untuk menghindari race pada created_at)
            $nomorPO = 'PO-' . date('Ymd') . '-' . str_pad(Purchase::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            // Simpan purchase dengan informasi lengkap (tidak membuat BarangMasuk)
            $purchase = Purchase::create([
                'barang_id' => $validated['barang_id'],
                'supplier_id' => $validated['supplier_id'],
                'user_id' => auth()->id(),
                'nomor_po' => $nomorPO,
                'jumlah_po' => $validated['jumlah'],
                'harga_unit' => $validated['harga_per_unit'],
                'satuan' => $barang->satuan ?? null,
                'tanggal_pembelian' => $validated['tanggal_pembelian'],
                'total_harga' => $validated['total_harga'],
                'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
                'keterangan' => $validated['keterangan'],
                'purchase_request_id' => $validated['purchase_request_id'],
                'status_pembelian' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('purchases.show', $purchase->id)
                ->with('success', 'Transaksi pembelian berhasil dibuat dengan nomor PO: ' . $nomorPO);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan pembelian: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail transaksi pembelian
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'barangMasuk.barang', 'barang', 'user', 'payment', 'payments']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Form edit transaksi pembelian
     */
    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::where('status', 'aktif')->get();
        return view('purchases.edit', compact('purchase', 'suppliers'));
    }

    /**
     * Update transaksi pembelian
     */
    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'total_harga' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_pembelian',
            'keterangan' => 'nullable|string',
        ]);

        $purchase->update($validated);

        return redirect()->route('purchases.show', $purchase->id)
            ->with('success', 'Transaksi pembelian berhasil diperbarui');
    }

    /**
     * Update status pembayaran
     */
    public function updatePaymentStatus(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'status_pembayaran' => 'required|in:belum_bayar,sebagian,lunas',
        ]);

        $purchase->update($validated);

        return response()->json([
            'message' => 'Status pembayaran berhasil diperbarui',
            'status_pembayaran' => $purchase->status_pembayaran,
        ]);
    }

    /**
     * Hapus transaksi pembelian
     */
    public function destroy(Purchase $purchase)
    {
        $nomorPO = $purchase->nomor_po;
        $purchase->delete();

        return redirect()->route('purchases.index')
            ->with('success', 'Transaksi pembelian ' . $nomorPO . ' berhasil dihapus');
    }

    /**
     * Export ke PDF (opsional)
     */
    public function exportPDF(Purchase $purchase)
    {
        $purchase->load(['supplier', 'barangMasuk.barang', 'user']);
        // Implementasi export PDF bisa menggunakan library seperti TCPDF atau Barryvdh
        return view('purchases.pdf', compact('purchase'));
    }

    /**
     * Form untuk mencatat pembayaran pembelian
     */
    public function recordPayment(Purchase $purchase)
    {
        return view('purchases.record-payment', compact('purchase'));
    }

    /**
     * Simpan pembayaran pembelian
     */
    public function storePayment(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0.01',
            'metode_pembayaran' => 'required|string',
            'tanggal_pembayaran' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        // Cek apakah pembayaran melebihi total
        $totalDibayar = Payment::where('purchase_id', $purchase->id)->sum('amount');
        $totalBaru = $totalDibayar + $validated['jumlah_bayar'];

        if ($totalBaru > $purchase->total_harga) {
            return back()->with('error', 'Jumlah pembayaran melebihi total harga pembelian');
        }

        // Handle bukti pembayaran upload
        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');
        }

        // Buat record pembayaran
        Payment::create([
            'purchase_id' => $purchase->id,
            'amount' => $validated['jumlah_bayar'],
            'method' => $validated['metode_pembayaran'],
            'paid_at' => $validated['tanggal_pembayaran'],
            'bukti_pembayaran' => $buktiPath,
            'status' => 'paid',
            'metadata' => ['keterangan' => $validated['keterangan']],
        ]);

        // Update status pembayaran purchase
        $totalDibayar = Payment::where('purchase_id', $purchase->id)->sum('amount');
        
        if ($totalDibayar >= $purchase->total_harga) {
            $status = 'lunas';
        } elseif ($totalDibayar > 0) {
            $status = 'sebagian';
        } else {
            $status = 'belum_bayar';
        }

        $purchase->update(['status_pembayaran' => $status]);

        return redirect()->route('purchases.show', $purchase->id)
            ->with('success', 'Pembayaran berhasil dicatat');
    }
}
