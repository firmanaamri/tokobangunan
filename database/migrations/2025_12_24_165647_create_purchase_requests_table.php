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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pr')->unique(); // PR000001
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Staff yang membuat
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang')->cascadeOnDelete();
            $table->integer('jumlah_diminta');
            $table->string('satuan')->nullable();
            // Payment terms: jumlah hari (payment_term) atau tanggal jatuh tempo khusus (due_date)
            $table->integer('payment_term')->nullable()->after('satuan');
            $table->dateTime('due_date')->nullable()->after('payment_term');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('catatan_request')->nullable();
            $table->text('catatan_approval')->nullable(); // Catatan dari admin saat approve/reject
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); // Admin yang approve
            $table->timestamp('tanggal_approval')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
