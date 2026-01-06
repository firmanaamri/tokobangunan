<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition()
    {
        $productNames = [
            'Semen Portland 50kg',
            'Pasir Beton per Kubik',
            'Batu Split per Kubik',
            'Besi Beton Polos 8mm',
            'Besi Beton Polos 10mm',
            'Cat Tembok Avitex 25kg',
            'Genteng Keramik M-Class',
            'Pipa PVC 3 inch',
            'Keramik Lantai 40x40',
            'Triplek 4mm 122x244',
            'Seng Gelombang 0.3mm',
            'Bata Merah Press',
            'Batako Press',
            'Hollow Brick 10x20x40',
        ];
        
        return [
            'nama_barang' => $this->faker->randomElement($productNames),
            'sku' => strtoupper($this->faker->bothify('BRG-####')),
            'kategori_id' => null,
            'satuan' => $this->faker->randomElement(['pcs', 'dus', 'kg', 'm3', 'batang', 'lembar', 'sak']),
            'stok_saat_ini' => $this->faker->numberBetween(10, 200),
            'harga' => $this->faker->randomFloat(2, 15000, 5000000),
            'deskripsi' => $this->faker->optional()->sentence(),
        ];
    }
}
