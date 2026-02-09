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
        'gambar',
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
    // 1. Accessor untuk Label Status (Teks)
   // FUNGSI 1: Untuk Mengambil TEKS Status (Habis, Menipis, Aman)
    // Dipanggil di view pakai: $product->status
    public function getStatusAttribute(): string
    {
        $qty = $this->stok_saat_ini ?? 0;

        if ($qty <= 0) {
            return 'Habis';
        }

        if ($qty < 20) {
            return 'Menipis';
        }

        return 'Aman';
    }

    // FUNGSI 2: Untuk Mengambil WARNA Status (Merah, Kuning, Hijau)
    // Dipanggil di view pakai: $product->status_color
    public function getStatusColorAttribute(): string
    {
        $qty = $this->stok_saat_ini ?? 0;

        return match (true) {
            $qty <= 0 => 'bg-red-100 text-red-800 animate-pulse', // Merah
            $qty < 20 => 'bg-amber-100 text-amber-800 animate-bounce', // Kuning/Amber
            default   => 'bg-emerald-100 text-emerald-800', // Hijau
        };
    }
}