<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
{
    public function run(): void
    {
        // ensure some barang exist
        if (Barang::count() < 5) {
            Barang::factory()->count(10)->create();
        }

        $barangs = Barang::all();

        // create sample sales
        foreach (range(1, 20) as $i) {
            $sale = Sale::factory()->create([
                'customer_id' => rand(0,1) ? null : null,
                'status' => 'completed',
                'payment_status' => 'paid',
            ]);

            $itemsCount = rand(1,4);
            $subtotal = 0;

            for ($j = 0; $j < $itemsCount; $j++) {
                $barang = $barangs->random();
                $qty = rand(1,5);
                $unit = rand(1000, 50000) / 100;
                $total = $unit * $qty;

                $sale->items()->create([
                    'barang_id' => $barang->id,
                    'qty' => $qty,
                    'unit_price' => $unit,
                    'total_price' => $total,
                    'satuan' => $barang->satuan ?? 'pcs',
                ]);

                // reduce stock to reflect sale
                $barang->decrement('stok_saat_ini', $qty);

                $subtotal += $total;
            }

            $sale->update([
                'subtotal' => $subtotal,
                'tax' => 0,
                'discount' => 0,
                'total' => $subtotal,
                'status' => 'completed',
                'payment_status' => 'paid',
            ]);

            Payment::create([
                'sale_id' => $sale->id,
                'amount' => $subtotal,
                'method' => 'cash',
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }
    }
}
