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
        Schema::create('books', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->string('kode_buku')->unique();
        $table->string('judul');
        $table->uuid('category_id');
        $table->string('penulis');
        $table->string('penerbit');
        $table->year('tahun');
        $table->integer('stok');
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
