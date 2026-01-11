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
        Schema::table('barang_keluar', function (Blueprint $table) {
            // Add sale_id without foreign key constraint if sales table doesn't exist
            if (Schema::hasTable('sales')) {
                $table->foreignId('sale_id')->nullable()->after('barang_id')->constrained('sales')->onDelete('cascade');
            } else {
                $table->unsignedBigInteger('sale_id')->nullable()->after('barang_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->dropForeign(['sale_id']);
            $table->dropColumn('sale_id');
        });
    }
};
