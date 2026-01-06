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
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_grn')->unique(); // GRN000001
            $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
            $table->integer('jumlah_po'); // Jumlah dari PO
            $table->integer('jumlah_diterima'); // Jumlah yang benar-benar diterima
            $table->integer('jumlah_rusak')->default(0); // Jumlah yang rusak/reject
            $table->enum('status', ['pending_inspection', 'approved', 'rejected', 'partial'])->default('pending_inspection');
            $table->text('catatan_inspection')->nullable(); // Catatan dari inspector
            $table->string('foto_kerusakan')->nullable(); // Path foto bukti kerusakan
            $table->foreignId('inspector_id')->nullable()->constrained('users')->nullOnDelete(); // Staff/Inspector yang melakukan inspection
            $table->timestamp('tanggal_inspection')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receipts');
    }
};
