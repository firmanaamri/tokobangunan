<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase; // Pakai Model Purchase (PO)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        // 1. Ambil Data PO (Purchase)
        $purchase = Purchase::findOrFail($request->purchase_id);

        // 2. Hitung Sisa Tagihan (Agar tidak overpayment)
        // Asumsi relasi di model Purchase bernama 'payments'
        $sudahDibayar = $purchase->payments()->sum('amount');
        $sisaTagihan = $purchase->display_total - $sudahDibayar;

        // 3. Validasi
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            // Validasi: Amount tidak boleh lebih besar dari sisa tagihan
            'amount' => 'required|numeric|min:1|max:' . $sisaTagihan,
            'method' => 'nullable|string',
            'keterangan' => 'nullable|string', // Pastikan pakai 'keterangan' bukan 'metadata'
            'bukti_pembayaran' => 'nullable|image|max:2048', // Opsional jika ada upload bukti
        ], [
            'amount.max' => 'Pembayaran melebihi sisa tagihan (Sisa: Rp ' . number_format($sisaTagihan) . ')',
        ]);

        try {
            DB::beginTransaction();

            // 4. Upload Bukti (Jika ada)
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')->store('payment-proofs', 'public');
            }

            // 5. Simpan Pembayaran
            Payment::create([
                'purchase_id' => $purchase->id, // Mengacu ke PO
                'amount' => $request->amount,
                'method' => $request->input('method') ?? 'bank_transfer',
                'status' => 'paid', // Status pembayaran itu sendiri
                'keterangan' => $request->keterangan,
                'bukti_pembayaran' => $buktiPath,
                'paid_at' => now(),
            ]);

            // 6. Cek Pelunasan & Update Status PR
            $totalBayarSekarang = $sudahDibayar + $request->amount;

            // Toleransi perbedaan desimal (floating point)
            if ($totalBayarSekarang >= ($purchase->display_total - 1)) {
                // A. Update Status PO jadi Lunas
                $purchase->update(['status_pembayaran' => 'lunas']);

                // B. UPDATE STATUS PR JADI COMPLETED
                if ($purchase->purchaseRequest) {
                    $purchase->purchaseRequest->update(['status' => 'completed']);
                }
            } else {
                // Jika belum lunas
                $purchase->update(['status_pembayaran' => 'sebagian']);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Pembayaran berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan pembayaran: ' . $e->getMessage());
        }
    }
}