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
            // purchase_request FK (nullable) — purchase_requests created later, keep plain column
            $table->unsignedBigInteger('purchase_request_id')->nullable();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuk')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nomor_po')->unique();
            $table->date('tanggal_pembelian');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status_pembayaran', ['belum_bayar', 'sebagian', 'lunas'])->default('belum_bayar');
            $table->datetime('due_date')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

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
