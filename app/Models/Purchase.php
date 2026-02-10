<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';

    protected $fillable = [
        'purchase_request_id', 
        'barang_masuk_id',
        'barang_id',
        'supplier_id',
        'user_id',
        'nomor_po',
        'jumlah_po',
        'satuan',
        'harga_unit',
        'tanggal_pembelian',
        'total_harga',
        'status_pembayaran',
        'status_pembelian',
        'due_date', 
        'keterangan',
        'catatan',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'harga_unit' => 'decimal:2',
        'tanggal_pembelian' => 'date',
        'due_date' => 'date', 
    ];

    // Tambahkan accessor untuk menampilkan total harga berdasarkan harga_beli_terakhir dari barang
    // jika tersedia; fallback ke kolom `total_harga` yang tersimpan di DB.
    public function getDisplayUnitPriceAttribute()
    {
        if ($this->barang && !is_null($this->barang->harga_beli_terakhir) && $this->barang->harga_beli_terakhir > 0) {
            return $this->barang->harga_beli_terakhir;
        }

        return $this->harga_unit ?? 0;
    }

    public function getDisplayTotalAttribute()
    {
        // Jika barang ada dan harga_beli_terakhir tersedia, gunakan itu * jumlah
        if ($this->barang && !is_null($this->barang->harga_beli_terakhir) && $this->barang->harga_beli_terakhir > 0) {
            return bcmul((string) $this->barang->harga_beli_terakhir, (string) ($this->jumlah_po ?? 0), 2);
        }

        return $this->total_harga ?? 0;
    }

    /**
     * Relasi ke BarangMasuk
     */
    public function barangMasuk(): BelongsTo
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_masuk_id');
    }

    /**
     * Relasi ke Barang (fallback ketika tidak ada BarangMasuk)
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Relasi ke Supplier
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Relasi ke User (Admin)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Payment
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'purchase_id');
    }

    /**
     * Relasi ke Payment (ambil yang terakhir untuk kemudahan akses)
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'purchase_id')->latestOfMany();
    }

    /**
     * Relasi ke PurchaseRequest
     */
    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    /**
     * Relasi ke GoodsReceipt
     */
    public function goodsReceipt(): HasOne
    {
        return $this->hasOne(GoodsReceipt::class, 'purchase_id');
    }
}