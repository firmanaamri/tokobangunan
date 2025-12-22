<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'sku',
        'kategori_id',
        'satuan',
        'harga',
        'stok_saat_ini',
        'deskripsi',
    ];

    protected $casts = [
        'stok_saat_ini' => 'integer',
        'harga' => 'decimal:2',
    ];

    /**
     * Relasi ke Kategori
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi ke BarangMasuk
     */
    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class, 'barang_id');
    }

    /**
     * Relasi ke BarangKeluar
     */
    public function barangKeluar(): HasMany
    {
        return $this->hasMany(BarangKeluar::class, 'barang_id');
    }

    /**
     * Human-readable status based on current stock.
     */
    public function getStatusAttribute(): string
    {
        $qty = $this->stok_saat_ini ?? 0;

        if ($qty <= 0) {
            return 'Habis';
        }

        if ($qty < 50) {
            return 'Stok Menipis';
        }

        return 'Aman';
    }
}