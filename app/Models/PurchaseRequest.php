<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    protected $fillable = [
        'nomor_pr',
        'user_id',
        'supplier_id',
        'barang_id',
        'jumlah_diminta',
        'satuan',
        'status',
        'catatan_request',
        'catatan_approval',
        'approved_by',
        'tanggal_approval',
    ];

    protected $casts = [
        'tanggal_approval' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'purchase_request_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}

