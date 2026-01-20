<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Guard: skip jika tabel sudah ada
        if (Schema::hasTable('payments')) {
            return;
        }

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
           
            
            
            $table->foreignId('purchase_id')->nullable();
            if (Schema::hasTable('purchases')) {
                $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            }
            
            $table->decimal('amount', 12, 2);
            $table->string('method')->nullable();
            $table->enum('status', ['pending','paid','failed','refunded'])->default('pending');
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // Alter suppliers table: ensure phone and postcode are stored as strings with proper max lengths
        if (Schema::hasTable('suppliers')) {
            Schema::table('suppliers', function (Blueprint $table) {
                // Check if column exists before modifying
                $columns = Schema::getColumns('suppliers');
                $columnNames = array_column($columns, 'name');
                
                if (in_array('nomor_telepon', $columnNames)) {
                    $table->string('nomor_telepon', 14)->nullable()->change();
                }
                if (in_array('kode_pos', $columnNames)) {
                    $table->string('kode_pos', 10)->nullable()->change();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        // Revert suppliers columns if needed
        if (Schema::hasTable('suppliers')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $columns = Schema::getColumns('suppliers');
                $columnNames = array_column($columns, 'name');
                
                if (in_array('nomor_telepon', $columnNames)) {
                    $table->string('nomor_telepon')->nullable()->change();
                }
                if (in_array('kode_pos', $columnNames)) {
                    $table->string('kode_pos')->nullable()->change();
                }
            });
        }
    }
};
