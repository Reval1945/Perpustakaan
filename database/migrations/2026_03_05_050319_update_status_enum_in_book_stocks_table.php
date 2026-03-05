<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE book_stocks 
            MODIFY status ENUM('tersedia','dipinjam','rusak','hilang') 
            DEFAULT 'tersedia'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE book_stocks 
            MODIFY status ENUM('tersedia','dipinjam','rusak') 
            DEFAULT 'tersedia'
        ");
    }
};