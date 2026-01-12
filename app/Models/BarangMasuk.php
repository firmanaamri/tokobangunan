<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'barang_id',
        'jumlah_barang_masuk',
        'quantity_received',
        'quantity_accepted',
        'quantity_rejected',
        'rejection_reason',
        'rejection_photo',
        'disposition',
        'tanggal_masuk',
        'keterangan',
        'user_id',
        'goods_receipt_id',
    ];

    protected $casts = [
        'jumlah_barang_masuk' => 'integer',
        'quantity_received' => 'integer',
        'quantity_accepted' => 'integer',
        'quantity_rejected' => 'integer',
        'tanggal_masuk' => 'date',
    ];

    /**
     * Relasi ke Barang
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Purchase (Transaksi)
     */
    public function purchase(): HasOne
    {
        return $this->hasOne(Purchase::class, 'barang_masuk_id');
    }

    /**
     * Relasi ke GoodsReceipt (Barang Inspection)
     */
    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class, 'goods_receipt_id');
    }

    public function quarantine(): HasOne
    {
        return $this->hasOne(Quarantine::class, 'barang_masuk_id');
    }
}