<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('book_stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('book_id');
            $table->string('kode_eksemplar')->unique();
            $table->enum('status',['tersedia','dipinjam','rusak'])->default('tersedia');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('book_id')
                  ->references('id')
                  ->on('books')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_stocks');
    }
};
