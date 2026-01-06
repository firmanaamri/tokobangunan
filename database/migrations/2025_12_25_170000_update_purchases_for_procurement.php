<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Make barang_masuk_id nullable via raw SQL to avoid DBAL requirement
        DB::statement('ALTER TABLE purchases MODIFY barang_masuk_id BIGINT UNSIGNED NULL');

        Schema::table('purchases', function (Blueprint $table) {
            // Additional procurement fields
            $table->foreignId('barang_id')->nullable()->after('supplier_id')->constrained('barang')->nullOnDelete();
            $table->integer('jumlah_po')->nullable()->after('barang_id');
            $table->string('satuan', 50)->nullable()->after('jumlah_po');
            $table->decimal('harga_unit', 12, 2)->default(0)->after('satuan');
            $table->enum('status_pembelian', ['pending', 'ordered', 'received'])->default('pending')->after('status_pembayaran');
            $table->text('catatan')->nullable()->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->dropColumn(['barang_id', 'jumlah_po', 'satuan', 'harga_unit', 'status_pembelian', 'catatan']);
        });

        // Revert barang_masuk_id to not null
        DB::statement('ALTER TABLE purchases MODIFY barang_masuk_id BIGINT UNSIGNED NOT NULL');
    }
};
