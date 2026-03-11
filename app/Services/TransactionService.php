<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Transactions;
use App\Models\AturanPeminjaman;
use App\Models\TransactionDetail;
use App\Models\BookStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\Generators\TransactionCodeGenerator;

class TransactionService
{
    /**
     * Buat peminjaman oleh anggota.
     * Anggota sudah memilih eksemplar di frontend → book_stock_ids dikirim.
     */
    public function createPeminjaman(string $userId, array $bookIds, array $bookStockIds = []): Transactions
    {
        return DB::transaction(function () use ($userId, $bookIds, $bookStockIds) {

            $aturan        = AturanPeminjaman::where('aktif', 1)->firstOrFail();
            $tanggalPinjam = now();
            $jatuhTempo    = $tanggalPinjam->copy()->addDays($aturan->maks_hari_pinjam);

            $transaction = Transactions::create([
                'kode_transaksi'      => TransactionCodeGenerator::generate(),
                'user_id'             => $userId,
                'tanggal_pinjam'      => $tanggalPinjam,
                'tanggal_jatuh_tempo' => $jatuhTempo,
                'status'              => 'menunggu_verifikasi',
                'total_denda'         => 0,
                'lunas'               => true,
            ]);

            $books = Book::whereIn('id', $bookIds)->get()->keyBy('id');

            // Map bookId → stockId
            $stockMap = count($bookStockIds) === count($bookIds)
                ? collect($bookIds)->combine($bookStockIds)->toArray()
                : [];

            $details = [];

            foreach ($bookIds as $bookId) {
                $book    = $books[$bookId] ?? null;
                $stockId = $stockMap[$bookId] ?? null;

                if (!$book) continue;

                if ($stockId) {
                    // Validasi stok masih tersedia + lock agar tidak race condition
                    $stok = BookStock::where('id', $stockId)
                        ->where('book_id', $bookId)
                        ->where('status', 'tersedia')
                        ->lockForUpdate()
                        ->first();

                    if (!$stok) {
                        throw new \Exception(
                            "Eksemplar yang Anda pilih untuk buku \"{$book->judul}\" sudah tidak tersedia. Silakan pilih eksemplar lain."
                        );
                    }

                    // Reservasi agar tidak bisa dipilih anggota lain
                    $stok->update(['status' => 'direservasi']);
                }

                $details[] = [
                    'id'                  => (string) Str::uuid(),
                    'book_id'             => $book->id,
                    'kode_buku'           => $book->kode_buku,
                    'judul_buku'          => $book->judul,
                    'status'              => 'menunggu_verifikasi',
                    'tanggal_jatuh_tempo' => $jatuhTempo,
                    'book_stock_id'       => $stockId, // null jika tidak dipilih
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ];
            }

            $transaction->details()->createMany($details);

            return $transaction->load('details');
        });
    }

    public function verifikasiPengembalian(Transactions $transaction, array $detailIds)
    {
        $aturan = AturanPeminjaman::aktif();
        $total  = 0;

        foreach ($transaction->details()->whereIn('id', $detailIds)->get() as $detail) {
            $total += $detail->hitungDenda($aturan, $transaction->tanggal_jatuh_tempo);
        }

        $transaction->update([
            'total_denda' => $total,
            'lunas'       => $total === 0,
            'status'      => 'dikembalikan',
        ]);
    }

    public function ajukanPengembalian(string $transactionId, array $detailIds)
    {
        $transaction = Transactions::findOrFail($transactionId);

        $transaction->details()
            ->whereIn('id', $detailIds)
            ->update([
                'status'          => 'menunggu_verifikasi_kembali',
                'tanggal_kembali' => now(),
            ]);

        $transaction->update(['status' => 'menunggu_verifikasi']);
    }
}