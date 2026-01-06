<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    protected $fillable = [
        'nomor_grn',
        'purchase_id',
        'jumlah_po',
        'jumlah_diterima',
        'jumlah_rusak',
        'status',
        'catatan_inspection',
        'foto_kerusakan',
        'inspector_id',
        'tanggal_inspection',
    ];

    protected $casts = [
        'tanggal_inspection' => 'datetime',
    ];

    // Relationships
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function barangMasuk()
    {
        return $this->hasOne(BarangMasuk::class, 'goods_receipt_id');
    }

    // Scopes
    public function scopePendingInspection($query)
    {
        return $query->where('status', 'pending_inspection');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }
}

