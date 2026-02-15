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
        Schema::create('denda_tables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('transaksi_id');
            $table->integer('jumlah_hari');
            $table->decimal('total_denda', 12, 2);
            $table->boolean('lunas')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('transaksi_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denda_tables');
    }
};
