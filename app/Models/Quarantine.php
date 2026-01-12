<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quarantine extends Model
{
    use HasFactory;

    protected $table = 'quarantines';

    protected $fillable = [
        'barang_masuk_id',
        'barang_id',
        'supplier_id',
        'quantity',
        'reason',
        'photo',
        'status',
        'created_by',
    ];

    public function barangMasuk(): BelongsTo
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_masuk_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
