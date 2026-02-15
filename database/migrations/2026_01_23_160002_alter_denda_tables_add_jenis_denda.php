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
        Schema::table('denda_tables', function (Blueprint $table) {
            $table->enum('jenis_denda', [
                'telat',
                'ganti_buku'
            ])->after('transaksi_id');

            $table->text('keterangan')->nullable()->after('jenis_denda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('denda_tables', function (Blueprint $table) {
            $table->dropColumn(['jenis_denda', 'keterangan']);
        });
    }
};
