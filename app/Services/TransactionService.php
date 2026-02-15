<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Transactions;
use App\Models\AturanPeminjaman;
use App\Services\Generators\TransactionCodeGenerator;

class TransactionService
{
    public function createPeminjaman(string $userId, array $bookIds): Transactions
    {
        $aturan = AturanPeminjaman::where('aktif', 1)->firstOrFail();

        $transaction = Transactions::create([
            'kode_transaksi' => TransactionCodeGenerator::generate(),
            'user_id' => $userId,
            'tanggal_pinjam' => now(),
            'tanggal_jatuh_tempo' => now()->addDays($aturan->maks_hari_pinjam),
            'status' => 'menunggu_verifikasi',
            'total_denda' => 0,
            'lunas' => true,
        ]);

        $transaction->details()->createMany(
            Book::whereIn('id', $bookIds)->get()->map(fn ($b) => [
                'book_id' => $b->id,
                'kode_buku' => $b->kode_buku,
                'judul_buku' => $b->judul,
                'status' => 'menunggu_verifikasi',
                'tanggal_jatuh_tempo' => now()->addDays($aturan->maks_hari_pinjam),
            ])->toArray()
        );

        return $transaction->load('details');
    }

    public function verifikasiPengembalian(Transactions $transaction, array $detailIds)
    {
        $aturan = AturanPeminjaman::aktif();
        $total = 0;

        foreach ($transaction->details()->whereIn('id', $detailIds)->get() as $detail) {
            $total += $detail->hitungDenda(
                $aturan,
                $transaction->tanggal_jatuh_tempo
            );
        }

        $transaction->update([
            'total_denda' => $total,
            'lunas' => $total === 0,
            'status' => 'dikembalikan',
        ]);
    }

    public function ajukanPengembalian(string $transactionId, array $detailIds)
    {
        $transaction = Transactions::findOrFail($transactionId);

        $transaction->details()
            ->whereIn('id', $detailIds)
            ->update([
                'status' => 'menunggu_verifikasi_kembali',
                'tanggal_kembali' => now(),
            ]);

        $transaction->update([
            'status' => 'menunggu_verifikasi',
        ]);
    }
}
