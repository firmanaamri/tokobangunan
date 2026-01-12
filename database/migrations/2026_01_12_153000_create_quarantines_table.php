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
        Schema::create('quarantines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuk')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->integer('quantity')->default(0);
            $table->text('reason')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['pending','returned','repaired','disposed'])->default('pending');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quarantines');
    }
};
