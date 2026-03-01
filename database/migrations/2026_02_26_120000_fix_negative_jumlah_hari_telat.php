<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set any negative jumlah_hari_telat to 0 to normalize existing data
        DB::table('transaction_details')
            ->where('jumlah_hari_telat', '<', 0)
            ->update(['jumlah_hari_telat' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // irreversible normalization - no-op on rollback
    }
};
