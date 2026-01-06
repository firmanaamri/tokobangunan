<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\Purchase;
use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GoodsReceiptController extends Controller
{
    /**
     * Display list of goods receipts
     */
    public function index()
    {
        $receipts = GoodsReceipt::with(['purchase.supplier', 'purchase.barang', 'inspector'])
            ->latest()
            ->paginate(15);

        $stats = [
            'pending' => Purchase::where('status_pembayaran', 'lunas')->whereDoesntHave('goodsReceipt')->count(),
            'approved' => GoodsReceipt::where('status', 'approved')->count(),
            'rejected' => GoodsReceipt::where('status', 'rejected')->count(),
            'partial' => GoodsReceipt::where('status', 'partial')->count(),
        ];

        return view('goods-receipts.index', compact('receipts', 'stats'));
    }

    /**
     * Show form to receive goods for a specific purchase
     */
    public function receive(Purchase $purchase)
    {
        // Check if purchase already has GRN
        if ($purchase->goodsReceipt) {
            return redirect()->route('goods-receipts.show', $purchase->goodsReceipt->id)
                ->with('info', 'PO ini sudah memiliki penerimaan barang');
        }

        // Only lunas purchases can be received
        if ($purchase->status_pembayaran !== 'lunas') {
            return back()->with('error', 'PO harus lunas dulu sebelum menerima barang');
        }

        $purchase->load(['supplier', 'barang', 'user', 'barangMasuk.barang']);

        return view('goods-receipts.receive', compact('purchase'));
    }

    /**
     * Store goods receipt
     */
    public function store(Request $request, Purchase $purchase)
    {
        // Load relations
        $purchase->load(['barang', 'barangMasuk']);
        
        // Check if jumlah_po is set, fallback to barangMasuk
        $jumlah = $purchase->jumlah_po ?? $purchase->barangMasuk?->jumlah_barang_masuk;
        
        if (!$jumlah) {
            return back()->with('error', 'PO ini belum memiliki jumlah barang yang valid. Silakan perbarui data PO terlebih dahulu.');
        }

        $validated = $request->validate([
            'jumlah_diterima' => 'required|integer|min:0|max:' . $jumlah,
            'jumlah_rusak' => 'required|integer|min:0',
            'catatan_inspection' => 'nullable|string|max:1000',
            'foto_kerusakan' => 'nullable|image|max:2048',
        ]);

        // Validation: jumlah_diterima + jumlah_rusak must = jumlah_po
        $total = $validated['jumlah_diterima'] + $validated['jumlah_rusak'];
        if ($total != $jumlah) {
            return back()->withErrors(['jumlah_diterima' => "Total diterima + rusak harus sama dengan jumlah PO ({$jumlah})"]);
        }

        try {
            \DB::beginTransaction();

            // Generate GRN number
            $lastGRN = GoodsReceipt::orderBy('id', 'desc')->first();
            $number = ($lastGRN ? intval(substr($lastGRN->nomor_grn, 3)) + 1 : 1);
            $nomor_grn = 'GRN' . str_pad($number, 6, '0', STR_PAD_LEFT);

            // Determine status
            $status = 'approved';
            if ($validated['jumlah_rusak'] > 0) {
                $status = ($validated['jumlah_diterima'] > 0) ? 'partial' : 'rejected';
            }

            // Handle photo upload
            $fotoPath = null;
            if ($request->hasFile('foto_kerusakan')) {
                $fotoPath = $request->file('foto_kerusakan')->store('grn-photos', 'public');
            }

            // Create GRN
            $grn = GoodsReceipt::create([
                'nomor_grn' => $nomor_grn,
                'purchase_id' => $purchase->id,
                'jumlah_po' => $jumlah,
                'jumlah_diterima' => $validated['jumlah_diterima'],
                'jumlah_rusak' => $validated['jumlah_rusak'],
                'status' => $status,
                'catatan_inspection' => $validated['catatan_inspection'],
                'foto_kerusakan' => $fotoPath,
                'inspector_id' => auth()->id(),
                'tanggal_inspection' => Carbon::now(),
            ]);

            // Only create BarangMasuk if jumlah_diterima > 0
            if ($validated['jumlah_diterima'] > 0) {
                // Get barang_id - from purchase or from barangMasuk
                $barang_id = $purchase->barang_id ?? $purchase->barangMasuk?->barang_id;
                
                if (!$barang_id) {
                    throw new \Exception('Barang ID tidak ditemukan pada Purchase Order ini');
                }
                
                $barangMasuk = BarangMasuk::create([
                    'barang_id' => $barang_id,
                    'jumlah_barang_masuk' => $validated['jumlah_diterima'],
                    'tanggal_masuk' => Carbon::now(),
                    'user_id' => auth()->id(),
                    'keterangan' => "Penerimaan dari PO {$purchase->nomor_po} (GRN {$nomor_grn})",
                ]);

                // Update purchase with barang_masuk_id
                $purchase->update([
                    'barang_masuk_id' => $barangMasuk->id,
                    'status_pembelian' => 'received',
                ]);

                // Increment stock
                if ($barang_id) {
                    $barang = Barang::find($barang_id);
                    if ($barang) {
                        $barang->increment('stok_saat_ini', $validated['jumlah_diterima']);
                    }
                }
            } else {
                // All rejected
                $purchase->update(['status_pembelian' => 'rejected']);
            }

            \DB::commit();

            return redirect()->route('goods-receipts.show', $grn->id)
                ->with('success', "Penerimaan barang berhasil dicatat! GRN #{$nomor_grn}");
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal menyimpan penerimaan barang: ' . $e->getMessage());
        }
    }

    /**
     * Show GRN detail
     */
    public function show(GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->load(['purchase.supplier', 'purchase.barang', 'purchase.barangMasuk.barang', 'inspector', 'barangMasuk.barang']);
        return view('goods-receipts.show', compact('goodsReceipt'));
    }

    /**
     * List purchases that are ready to receive (lunas but no GRN yet)
     */
    public function purchasesReadyToReceive()
    {
        $purchases = Purchase::with(['supplier', 'barang', 'barangMasuk.barang', 'user'])
            ->where('status_pembayaran', 'lunas')
            ->whereDoesntHave('goodsReceipt')
            ->latest('tanggal_pembelian')
            ->paginate(15);

        return view('goods-receipts.ready-to-receive', compact('purchases'));
    }
}
