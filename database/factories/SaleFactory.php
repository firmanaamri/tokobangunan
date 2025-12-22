<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition()
    {
        return [
            'nomor' => 'S'.now()->format('YmdHis').mt_rand(100,999),
            'customer_id' => null,
            'user_id' => null,
            'subtotal' => 0,
            'tax' => 0,
            'discount' => 0,
            'total' => 0,
            'status' => 'draft',
            'payment_status' => 'pending',
            'notes' => null,
        ];
    }
}
