<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aturan_peminjaman_tables', function (Blueprint $table) {
            // Tambah kolom maks_buku setelah maks_hari_pinjam
            // Default 3: anggota hanya boleh meminjam maksimal 3 buku sekaligus
            $table->unsignedTinyInteger('maks_buku')
                  ->default(3)
                  ->after('maks_hari_pinjam')
                  ->comment('Maksimal jumlah buku yang boleh dipinjam sekaligus per anggota');
        });

        // Isi nilai default untuk data yang sudah ada
        DB::table('aturan_peminjaman_tables')->update(['maks_buku' => 3]);
    }

    public function down(): void
    {
        Schema::table('aturan_peminjaman_tables', function (Blueprint $table) {
            $table->dropColumn('maks_buku');
        });
    }
};