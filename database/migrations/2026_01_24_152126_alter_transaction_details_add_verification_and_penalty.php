<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        //Ubah ENUM status (MariaDB / MySQL perlu raw SQL)
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

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->enum('jenis_denda', ['telat', 'rusak', 'hilang'])
                ->nullable()
                ->after('status');

            $table->date('tanggal_jatuh_tempo')
                ->nullable()
                ->after('tanggal_kembali');
        });
    }

    public function down(): void
    {
        // rollback ENUM ke versi lama
        DB::statement("
            ALTER TABLE transaction_details 
            MODIFY status ENUM(
                'dipinjam',
                'dikembalikan',
                'terlambat',
                'rusak',
                'hilang'
            ) NOT NULL DEFAULT 'dipinjam'
        ");

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn(['jenis_denda', 'tanggal_jatuh_tempo']);
        });
    }
};
