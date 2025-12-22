<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'barang_id',
        'jumlah_barang_masuk',
        'tanggal_masuk',
        'keterangan',
           'user_id',
    ];

    protected $casts = [
        'jumlah_barang_masuk' => 'integer',
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
}