<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\BarangMasuk;
use App\Models\Supplier;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'barangMasuk.barang', 'barang', 'user'])
            ->orderBy('tanggal_pembelian', 'desc')
            ->paginate(15);
        return view('purchases.index', compact('purchases'));
    }

    public function create($purchaseRequestId = null)
    {
        if (!$purchaseRequestId) {
            return redirect()->route('purchase-requests.index')->with('error', 'Pembelian hanya dapat dibuat dari Purchase Request yang sudah disetujui.');
        }

        $pr = \App\Models\PurchaseRequest::with('barang')->find($purchaseRequestId);
        if (!$pr || $pr->status !== 'approved') {
            return redirect()->route('purchase-requests.index')->with('error', 'PR tidak ditemukan atau belum disetujui.');
        }

        $suppliers = Supplier::where('status', 'aktif')->get();
        $barangMasuk = null;
        
        $defaultDue = null;
        if (!empty($pr->due_date)) {
            $defaultDue = $pr->due_date->toDateString();
        } elseif (!empty($pr->payment_term)) {
            $defaultDue = date('Y-m-d', strtotime('+' . intval($pr->payment_term) . ' days'));
        }

        $prefill = [
            'barang' => $pr->barang,
            'jumlah' => $pr->jumlah_diminta,
            'satuan' => $pr->satuan,
            'supplier_id' => $pr->supplier_id,
            'purchase_request_id' => $pr->id,
            'due_date' => $defaultDue, 
        ];

        return view('purchases.create', compact('barangMasuk', 'suppliers', 'prefill'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_request_id' => 'required|exists:purchase_requests,id',
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'harga_per_unit' => 'required|numeric|min:0', // Ini harga hasil nego
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'total_harga' => 'required|numeric|min:0',
            'due_date' => 'nullable|date|after_or_equal:tanggal_pembelian', 
            'keterangan' => 'nullable|string',
        ]);

        $pr = \App\Models\PurchaseRequest::find($validated['purchase_request_id']);

        DB::beginTransaction();
        try {
            // Ambil Data Barang
            $barang = \App\Models\Barang::find($validated['barang_id']);
            
            $nomorPO = 'PO-' . date('Ymd') . '-' . str_pad(Purchase::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $dueDate = $validated['due_date'] ?? null;
            if (empty($dueDate)) {
                if (!empty($pr->due_date)) {
                    $dueDate = $pr->due_date->toDateString();
                } elseif (!empty($pr->payment_term)) {
                    $dueDate = Carbon::parse($validated['tanggal_pembelian'])->addDays(intval($pr->payment_term))->toDateString();
                }
            }

            // 1. SIMPAN TRANSAKSI PO
            $purchase = Purchase::create([
                'barang_id' => $validated['barang_id'],
                'supplier_id' => $validated['supplier_id'],
                'user_id' => Auth::id(),
                'nomor_po' => $nomorPO,
                'jumlah_po' => $validated['jumlah'],
                'harga_unit' => $validated['harga_per_unit'], // Simpan harga nego
                'satuan' => $barang->satuan ?? null,
                'tanggal_pembelian' => $validated['tanggal_pembelian'],
                'total_harga' => $validated['total_harga'],
                'due_date' => $dueDate,
                'keterangan' => $validated['keterangan'],
                'purchase_request_id' => $validated['purchase_request_id'],
                'status_pembelian' => 'pending',
            ]);

            // 2. MODIFIKASI: UPDATE HARGA BELI TERAKHIR DI MASTER BARANG
            // Ini agar next time beli barang ini, harganya sudah update
            $barang->update([
                'harga_beli_terakhir' => $validated['harga_per_unit']
            ]);

            DB::commit();
            return redirect()->route('purchases.show', $purchase->id)->with('success', 'Transaksi pembelian berhasil dibuat dan harga pasar barang telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'barangMasuk.barang', 'barang', 'user', 'payment', 'payments']);
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::where('status', 'aktif')->get();
        return view('purchases.edit', compact('purchase', 'suppliers'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'total_harga' => 'required|numeric|min:0',
            'due_date' => 'nullable|date|after_or_equal:tanggal_pembelian',
            'keterangan' => 'nullable|string',
        ]);

        $purchase->update([
            'supplier_id' => $validated['supplier_id'],
            'tanggal_pembelian' => $validated['tanggal_pembelian'],
            'total_harga' => $validated['total_harga'],
            'due_date' => $validated['due_date'], 
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('purchases.show', $purchase->id)->with('success', 'Transaksi diperbarui');
    }

    public function updatePaymentStatus(Request $request, Purchase $purchase)
    {
        $validated = $request->validate(['status_pembayaran' => 'required|in:belum_bayar,sebagian,lunas']);
        $purchase->update($validated);
        return response()->json(['message' => 'Status diperbarui', 'status_pembayaran' => $purchase->status_pembayaran]);
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Transaksi dihapus');
    }

    public function exportPDF(Purchase $purchase)
    {
        $purchase->load(['supplier', 'barangMasuk.barang', 'user']);
        return view('purchases.pdf', compact('purchase'));
    }

    public function recordPayment(Purchase $purchase)
    {
        return view('purchases.record-payment', compact('purchase'));
    }

    public function storePayment(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0.01',
            'metode_pembayaran' => 'required|string',
            'tanggal_pembayaran' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $totalDibayar = Payment::where('purchase_id', $purchase->id)->sum('amount');
        $displayTotal = $purchase->display_total;
        if (($totalDibayar + $validated['jumlah_bayar']) > $displayTotal) {
            return back()->with('error', 'Jumlah pembayaran melebihi total harga.');
        }

        $buktiPath = $request->hasFile('bukti_pembayaran') ? $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public') : null;

        Payment::create([
            'purchase_id' => $purchase->id,
            'amount' => $validated['jumlah_bayar'],
            'method' => $validated['metode_pembayaran'],
            'paid_at' => $validated['tanggal_pembayaran'],
            'bukti_pembayaran' => $buktiPath,
            'status' => 'paid',
            'keterangan' => $validated['keterangan'],
        ]);

        $totalDibayar = Payment::where('purchase_id', $purchase->id)->sum('amount');
        $status = ($totalDibayar >= $purchase->display_total) ? 'lunas' : (($totalDibayar > 0) ? 'sebagian' : 'belum_bayar');
        $purchase->update(['status_pembayaran' => $status]);

        return redirect()->route('purchases.show', $purchase->id)->with('success', 'Pembayaran dicatat');
    }
}