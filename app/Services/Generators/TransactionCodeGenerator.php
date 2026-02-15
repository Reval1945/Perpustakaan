<?php

namespace App\Services\Generators;

use App\Models\Transactions;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionCodeGenerator
{
    public static function generate(): string
    {
        return DB::transaction(function () {

            $today = Carbon::today()->format('Ymd');

            $lastTransaction = Transactions::whereDate('created_at', today())
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            if ($lastTransaction) {
                $lastNumber = (int) substr($lastTransaction->kode_transaksi, -4);
                $sequence = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $sequence = '0001';
            }

            return "TRX-{$today}-{$sequence}";
        });
    }
}
