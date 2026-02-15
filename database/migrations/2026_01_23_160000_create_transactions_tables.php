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
        Schema::create('transactions', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('user_id', 36);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_jatuh_tempo');

            // rekap
            $table->decimal('total_denda', 12, 2)->default(0);
            $table->boolean('lunas')->default(false);

            // status transaksi (global)
            $table->enum('status', [
                'dipinjam',
                'menunggu_verifikasi',
                'dikembalikan',
                'terlambat',
                'rusak',
                'hilang'])->default('dipinjam');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
