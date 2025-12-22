<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';

    protected $fillable = [
        'barang_id',
        'jumlah_barang_keluar',
        'tanggal_keluar',
        'keterangan',
           'user_id',
    ];

    protected $casts = [
        'jumlah_barang_keluar' => 'integer',
        'tanggal_keluar' => 'date',
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