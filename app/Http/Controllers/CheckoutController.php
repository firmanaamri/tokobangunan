<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function cart()
    {
        $cart = session('cart', []);
        return view('checkout.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'qty' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'satuan' => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($data['barang_id']);

        // default unit price from product if not provided
        if (empty($data['unit_price'])) {
            $data['unit_price'] = $barang->harga ?? 0;
        }

        // check stock availability
        if (isset($barang->stok_saat_ini) && $data['qty'] > $barang->stok_saat_ini) {
            return back()->withErrors(['qty' => 'Stok tidak cukup untuk produk ' . $barang->nama_barang])->withInput();
        }

        $cart = session('cart', []);
        $cart[] = $data;
        session(['cart' => $cart]);

        return back()->with('success', 'Item ditambahkan ke keranjang.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'index' => 'required|integer|min:0',
            'qty' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        $cart = session('cart', []);
        if (!isset($cart[$data['index']])) {
            return back()->withErrors(['index' => 'Item tidak ditemukan di keranjang.']);
        }

        $item = $cart[$data['index']];
        $barang = Barang::find($item['barang_id']);
        if ($barang && $data['qty'] > $barang->stok_saat_ini) {
            return back()->withErrors(['qty' => 'Stok tidak cukup untuk produk ' . $barang->nama_barang]);
        }

        $cart[$data['index']]['qty'] = $data['qty'];
        if (isset($data['unit_price'])) {
            $cart[$data['index']]['unit_price'] = $data['unit_price'];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(Request $request)
    {
        $index = $request->input('index');
        $cart = session('cart', []);
        if (isset($cart[$index])) {
            array_splice($cart, $index, 1);
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'notes' => 'nullable|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('warning', 'Keranjang kosong.');
        }

        // validate stock again before committing
        foreach ($cart as $item) {
            $barang = Barang::find($item['barang_id']);
            if (!$barang) {
                return back()->withErrors(['cart' => 'Salah satu item tidak ditemukan.']);
            }
            if ($item['qty'] > ($barang->stok_saat_ini ?? 0)) {
                return back()->withErrors(['cart' => 'Stok tidak cukup untuk produk ' . $barang->nama_barang]);
            }
        }

        DB::beginTransaction();
        try {
            $sale = Sale::create([
                'nomor' => 'S'.now()->format('YmdHis').rand(10,99),
                'customer_id' => $request->input('customer_id'),
                'user_id' => $request->user()->id ?? null,
                'subtotal' => 0,
                'tax' => 0,
                'discount' => 0,
                'total' => 0,
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $request->input('notes'),
            ]);

            $subtotal = 0;

            foreach ($cart as $item) {
                $barang = Barang::find($item['barang_id']);
                // use product price as fallback
                $unit = $item['unit_price'] ?? ($barang->harga ?? 0);
                $qty = (int) $item['qty'];
                $totalPrice = $unit * $qty;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'barang_id' => $barang->id,
                    'qty' => $qty,
                    'unit_price' => $unit,
                    'total_price' => $totalPrice,
                    'satuan' => $item['satuan'] ?? $barang->satuan ?? null,
                ]);

                // Automatically create BarangKeluar record
                BarangKeluar::create([
                    'barang_id' => $barang->id,
                    'sale_id' => $sale->id,
                    'jumlah_barang_keluar' => $qty,
                    'tanggal_keluar' => now(),
                    'keterangan' => 'Penjualan #' . $sale->nomor,
                    'user_id' => $request->user()->id ?? null,
                ]);

                // decrement stock
                $barang->decrement('stok_saat_ini', $qty);

                $subtotal += $totalPrice;
            }

            $sale->update([
                'subtotal' => $subtotal,
                'tax' => 0,
                'discount' => 0,
                'total' => $subtotal,
            ]);

            DB::commit();
            session()->forget('cart');

            return redirect()->route('sales.show', $sale)->with('success', 'Transaksi berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat transaksi: '.$e->getMessage());
        }
    }

    public function confirm(Sale $sale)
    {
        $sale->load('items.barang', 'payments', 'customer');
        return view('checkout.confirm', compact('sale'));
    }
}
