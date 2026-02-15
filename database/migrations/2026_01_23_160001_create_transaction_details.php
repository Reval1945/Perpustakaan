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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('transaction_id', 36);
            $table->char('book_id', 36);

            $table->string('kode_buku');
            $table->string('judul_buku');

            $table->date('tanggal_kembali')->nullable();

            $table->enum('status', [
                'dipinjam',
                'dikembalikan',
                'terlambat',
                'rusak',
                'hilang'
            ])->default('dipinjam');

            // denda per buku
            $table->integer('jumlah_hari_telat')->default(0);
            $table->decimal('denda', 12, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
