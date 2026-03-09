<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE transaction_details 
            MODIFY status ENUM(
                'menunggu_verifikasi',
                'dipinjam',
                'mengajukan_perpanjangan',
                'diperpanjang',
                'menunggu_verifikasi_kembali',
                'dikembalikan',
                'terlambat',
                'rusak',
                'hilang'
            ) NOT NULL DEFAULT 'dipinjam'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE transaction_details 
            MODIFY status ENUM(
                'menunggu_verifikasi',
                'dipinjam',
                'menunggu_verifikasi_kembali',
                'dikembalikan',
                'terlambat',
                'rusak',
                'hilang'
            ) NOT NULL DEFAULT 'dipinjam'
        ");
    }
};