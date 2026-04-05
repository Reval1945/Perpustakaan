<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE transactions 
            MODIFY status ENUM(
                'dipinjam',
                'menunggu_verifikasi',
                'dikembalikan',
                'terlambat',
                'rusak',
                'hilang',
                'ditolak'
            ) DEFAULT 'dipinjam'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE transactions 
            MODIFY status ENUM(
                'dipinjam',
                'menunggu_verifikasi',
                'dikembalikan',
                'terlambat',
                'rusak',
                'hilang'
            ) DEFAULT 'dipinjam'
        ");
    }
};