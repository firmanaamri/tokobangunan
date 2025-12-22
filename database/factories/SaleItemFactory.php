<?php

namespace Database\Factories;

use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleItemFactory extends Factory
{
    protected $model = SaleItem::class;

    public function definition()
    {
        $qty = $this->faker->numberBetween(1, 10);
        $unit = $this->faker->randomFloat(2, 10, 1000);
        return [
            'barang_id' => null,
            'qty' => $qty,
            'unit_price' => $unit,
            'total_price' => $unit * $qty,
            'satuan' => 'pcs',
            'notes' => null,
        ];
    }
}
