<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition()
    {
        return [
            'nama_barang' => $this->faker->productName ?? $this->faker->word(),
            'sku' => strtoupper($this->faker->bothify('SKU-###??')),
            'kategori_id' => null,
            'satuan' => 'pcs',
            'stok_saat_ini' => $this->faker->numberBetween(10, 200),
            'harga' => $this->faker->randomFloat(2, 5, 500),
            'deskripsi' => $this->faker->optional()->sentence(),
        ];
    }
}
