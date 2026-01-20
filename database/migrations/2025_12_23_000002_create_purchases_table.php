<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            
            // Kolom dari file pertama (Create)
            $table->unsignedBigInteger('purchase_request_id')->nullable();
            
            // PERUBAHAN 1: barang_masuk_id dibuat NULLABLE (sesuai update file ke-2)
            // agar bisa mencatat PO sebelum barang diterima.
            $table->foreignId('barang_masuk_id')->nullable()->constrained('barang_masuk')->onDelete('cascade');
            
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            
            // PERUBAHAN 2: Penambahan kolom baru dari file update (Procurement)
            // Disisipkan di sini agar urutan kolomnya rapi
            $table->foreignId('barang_id')->nullable()->constrained('barang')->nullOnDelete();
            $table->integer('jumlah_po')->nullable();
            $table->string('satuan', 50)->nullable();
            $table->decimal('harga_unit', 12, 2)->default(0);
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nomor_po')->unique();
            $table->date('tanggal_pembelian');
            $table->decimal('total_harga', 12, 2);
            
            $table->enum('status_pembayaran', ['belum_bayar', 'sebagian', 'lunas'])->default('belum_bayar');
            
            // PERUBAHAN 3: Penambahan kolom status_pembelian dari file update
            $table->enum('status_pembelian', ['pending', 'ordered', 'received'])->default('pending');
            
            $table->datetime('due_date')->nullable();
            $table->text('keterangan')->nullable();
            
            // PERUBAHAN 4: Penambahan kolom catatan dari file update
            $table->text('catatan')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index('supplier_id');
            $table->index('user_id');
            $table->index('tanggal_pembelian');
            $table->index('status_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};