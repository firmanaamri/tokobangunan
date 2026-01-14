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
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id();
            // goods receipt FK (nullable)
            $table->foreignId('goods_receipt_id')->nullable()->after('id')->constrained('goods_receipts')->nullOnDelete();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->integer('jumlah_barang_masuk');
            // fields for inspection / rejection tracking
            $table->integer('quantity_received')->nullable();
            $table->integer('quantity_accepted')->nullable();
            $table->integer('quantity_rejected')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->string('rejection_photo')->nullable();
            $table->enum('disposition', ['pending','return','repair','dispose'])->default('pending');
            $table->date('tanggal_masuk');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
    }
};
