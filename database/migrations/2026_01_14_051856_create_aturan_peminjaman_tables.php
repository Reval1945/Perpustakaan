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
        Schema::create('aturan_peminjaman_tables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('maks_hari_pinjam');
            $table->decimal('denda_per_hari', 12, 2)->default(0);
            $table->boolean('aktif')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aturan_peminjaman_tables');
    }
};
