<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\Purchase;
use App\Models\BarangMasuk;
use App\Models\Barang;
use App\Models\Quarantine; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        // Cek apakah PO sudah pernah diterima sebelumnya
        if ($purchase->goodsReceipt) {
            return redirect()->route('goods-receipts.show', $purchase->goodsReceipt->id)
                ->with('info', 'PO ini sudah memiliki penerimaan barang (GRN).');
        }

        
        if ($purchase->status_pembayaran !== 'lunas') {
            return back()->with('error', 'PO harus berstatus LUNAS sebelum barang bisa diterima.');
        }

        $purchase->load(['supplier', 'barang', 'user', 'barangMasuk.barang']);

        return view('goods-receipts.receive', compact('purchase'));
    }

    /**
     * Store goods receipt (Inti Perbaikan Ada Disini)
     */
    public function store(Request $request, Purchase $purchase)
    {
        $purchase->load(['barang', 'barangMasuk']);
        
        // Ambil jumlah yang harus diterima (dari PO atau BarangMasuk)
        $jumlahTarget = $purchase->jumlah_po ?? $purchase->barangMasuk?->jumlah_barang_masuk;
        
        if (!$jumlahTarget) {
            return back()->with('error', 'PO ini tidak memiliki data jumlah barang yang valid.');
        }

        // Validasi Input
        $validated = $request->validate([
            'quantity_received' => 'nullable|integer|min:0',
            'quantity_accepted' => 'nullable|integer|min:0',
            'quantity_rejected' => 'nullable|integer|min:0',
            // Support nama field lama (legacy)
            'jumlah_diterima' => 'nullable|integer|min:0',
            'jumlah_rusak' => 'nullable|integer|min:0',
            'catatan_inspection' => 'nullable|string|max:1000',
            'foto_kerusakan' => 'nullable|image|max:2048',
        ]);

        // Normalisasi Data (Menggabungkan field lama & baru)
        $qtyAccepted = $request->input('quantity_accepted') ?? $request->input('jumlah_diterima') ?? 0;
        $qtyRejected = $request->input('quantity_rejected') ?? $request->input('jumlah_rusak') ?? 0;
        
        // Jika quantity_received tidak diisi, asumsikan sama dengan jumlah target PO
        $qtyReceived = $request->input('quantity_received') ?? $jumlahTarget;

        // Validasi Konsistensi Jumlah
        $totalFisik = $qtyAccepted + $qtyRejected;
        
        

        try {
            DB::beginTransaction();

            // 1. Generate Nomor GRN
            $lastGRN = GoodsReceipt::orderBy('id', 'desc')->first();
            $number = ($lastGRN ? intval(substr($lastGRN->nomor_grn, 3)) + 1 : 1);
            $nomor_grn = 'GRN' . str_pad($number, 6, '0', STR_PAD_LEFT);

            // 2. Tentukan Status GRN
            $statusGRN = 'approved';
            if ($qtyRejected > 0) {
                $statusGRN = ($qtyAccepted > 0) ? 'partial' : 'rejected';
            }

            // 3. Upload Foto (Jika ada)
            $fotoPath = null;
            if ($request->hasFile('foto_kerusakan')) {
                $fotoPath = $request->file('foto_kerusakan')->store('grn-photos', 'public');
            }

            // 4. Buat Data Goods Receipt (GRN)
            $grn = GoodsReceipt::create([
                'nomor_grn' => $nomor_grn,
                'purchase_id' => $purchase->id,
                'jumlah_po' => $jumlahTarget,
                'jumlah_diterima' => $qtyAccepted, 
                'jumlah_rusak' => $qtyRejected,    
                'status' => $statusGRN,
                'catatan_inspection' => $validated['catatan_inspection'],
                'foto_kerusakan' => $fotoPath,
                'inspector_id' => Auth::id(),
                'tanggal_inspection' => Carbon::now(),
            ]);

           // 5. Buat Data BarangMasuk (Log Inventory)
            $barang_id = $purchase->barang_id ?? $purchase->barangMasuk?->barang_id;
            
            $barangMasuk = BarangMasuk::create([
                'barang_id' => $barang_id,
                'jumlah_barang_masuk' => $qtyAccepted, 
                'quantity_received' => $qtyReceived,   
                'quantity_accepted' => $qtyAccepted,
                'quantity_rejected' => $qtyRejected,
                'rejection_reason' => $validated['catatan_inspection'] ?? null,
                'rejection_photo' => $fotoPath,
                'disposition' => 'pending', 
                
                'tanggal_masuk' => Carbon::now(),
                'user_id' => Auth::id(),
                'keterangan' => "Penerimaan PO #{$purchase->nomor_po} (GRN: {$nomor_grn})",
            ]);
            // 6. Update Stok Barang (Hanya yang diterima/bagus)
            if ($qtyAccepted > 0) {
                $barang = Barang::find($barang_id);
                if ($barang) {
                    $barang->increment('stok_saat_ini', $qtyAccepted);
                }
            }

            // 7. Update Status PO (Purchase)
            $purchase->update([
                'barang_masuk_id' => $barangMasuk->id,
                // Jika ada barang diterima, status PO jadi received. Jika semua reject, tetap pending/issue.
                'status_pembelian' => ($qtyAccepted > 0) ? 'received' : 'pending',
            ]);

            
            // 8. UPDATE STATUS PR (PURCHASE REQUEST) -> AGAR DASHBOARD COMPLETED MUNCUL
            
            if ($qtyAccepted > 0 && $purchase->purchaseRequest) {
                $purchase->purchaseRequest->update(['status' => 'completed']);
            }
            

            // 9. Handle Barang Reject (Masuk Karantina)
            if ($qtyRejected > 0) {
                // Pastikan model Quarantine ada. Jika tidak, hapus blok ini.
                if (class_exists(\App\Models\Quarantine::class)) {
                    \App\Models\Quarantine::create([
                        'barang_masuk_id' => $barangMasuk->id,
                        'barang_id' => $barang_id,
                        'supplier_id' => $purchase->supplier_id ?? null,
                        'quantity' => $qtyRejected,
                        'reason' => $validated['catatan_inspection'] ?? null,
                        'photo' => $fotoPath,
                        'status' => 'pending',
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('goods-receipts.show', $grn->id)
                ->with('success', "Penerimaan barang berhasil! GRN #{$nomor_grn} diterbitkan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penerimaan barang: ' . $e->getMessage());
        }
    }

    /**
     * Show GRN detail
     */
    public function show(GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->load(['purchase.supplier', 'purchase.barang', 'inspector', 'purchase.barangMasuk']);
        return view('goods-receipts.show', compact('goodsReceipt'));
    }

    /**
     * List purchases that are ready to receive
     */
    public function purchasesReadyToReceive()
    {
        $purchases = Purchase::with(['supplier', 'barang', 'barangMasuk.barang', 'user'])
            ->where('status_pembayaran', 'lunas')
            ->whereDoesntHave('goodsReceipt') // Hanya yang belum diterima
            ->latest('tanggal_pembelian')
            ->paginate(15);

        return view('goods-receipts.ready-to-receive', compact('purchases'));
    }
}