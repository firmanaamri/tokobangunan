<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'nama_supplier',
        'kontak_person',
        'nomor_telepon',
        'email',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'status',
        'keterangan',
    ];

    /**
     * Relasi ke Purchase
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }
}
