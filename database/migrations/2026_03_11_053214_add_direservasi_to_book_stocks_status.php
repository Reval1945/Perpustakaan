<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE book_stocks 
            MODIFY COLUMN status 
            ENUM('tersedia', 'dipinjam', 'rusak', 'hilang', 'direservasi')
            NOT NULL DEFAULT 'tersedia'
        ");
    }

    public function down(): void
    {
        // Kembalikan stok yang masih direservasi → tersedia sebelum rollback
        DB::statement("UPDATE book_stocks SET status = 'tersedia' WHERE status = 'direservasi'");

        DB::statement("
            ALTER TABLE book_stocks 
            MODIFY COLUMN status 
            ENUM('tersedia', 'dipinjam', 'rusak', 'hilang')
            NOT NULL DEFAULT 'tersedia'
        ");
    }
};