<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop existing FK so we can alter nullability
            $table->dropForeign(['sale_id']);
        });

        // Make sale_id nullable to support purchase-only payments
        DB::statement('ALTER TABLE payments MODIFY sale_id BIGINT UNSIGNED NULL');

        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('sale_id')->references('id')->on('sales')->cascadeOnDelete();
            $table->foreignId('purchase_id')->nullable()->after('sale_id')->constrained('purchases')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['purchase_id']);
            $table->dropColumn('purchase_id');
            $table->dropForeign(['sale_id']);
        });

        // Revert sale_id to NOT NULL
        DB::statement('ALTER TABLE payments MODIFY sale_id BIGINT UNSIGNED NOT NULL');

        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('sale_id')->references('id')->on('sales')->cascadeOnDelete();
        });
    }
};
