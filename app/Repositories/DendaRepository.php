<?php

namespace App\Repositories;

use App\Models\TransactionDetail;
use App\Interfaces\DendaInterface;
use App\Models\BookStock;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DendaRepository implements DendaInterface
{
    public function getAllWithDenda(?string $status)
    {
        $query = TransactionDetail::with([
            'transaction.user:id,name'
        ])
        ->where(function($q) {
            $q->where('denda', '>', 0) // Ada denda uang (karena telat)
            ->orWhereIn('jenis_denda', ['rusak', 'hilang']) // Atau tipenya rusak/hilang
            ->orWhereIn('status', ['rusak', 'hilang']); // Atau status bukunya rusak/hilang
        });

        if ($status) {
            $query->where('status_denda', $status);
        }

        return $query->orderBy('status_denda')->get();
    }

        public function updateStatus(TransactionDetail $detail, string $status)
    {
        // 1. Update status denda (lunas / belum_lunas)
        $detail->status_denda = $status;

        // 2. Jika denda diubah menjadi lunas
        if ($status === 'lunas') {
            
            // Pastikan status transaksi menjadi dikembalikan agar badge berubah
            $detail->status = 'dikembalikan';
            
            // Buat catatan menjadi NULL sesuai permintaan sebelumnya
            $detail->catatan = null;

            // 3. Logika update status book_stoks saja
            if ($detail->book_stock_id) {
                // Menggunakan UUID yang ada di book_stock_id untuk mencari id di tabel book_stocks
                \App\Models\BookStock::where('id', $detail->book_stock_id)->update([
                    'status' => 'tersedia'
                ]);
            }
        }

        $detail->save();

        return $detail;
    }
}
