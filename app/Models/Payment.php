<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'purchase_id',
        'amount',
        'method',
        'status',
        'reference',
        'bukti_pembayaran',
        'paid_at',
        'keterangan',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relasi ke Sale
     */
    

    /**
     * Relasi ke Purchase (Pembelian)
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
}
