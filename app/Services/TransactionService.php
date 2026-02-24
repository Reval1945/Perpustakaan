<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Transactions;
use App\Models\AturanPeminjaman;
use App\Models\TransactionDetail;
use App\Models\BookStock;
use Illuminate\Support\Facades\DB;
use App\Services\Generators\TransactionCodeGenerator;

class TransactionService
{
    public function createPeminjaman(string $userId, array $bookIds): Transactions
{
    // Gunakan database transaction untuk memastikan header & detail tersimpan semua atau tidak sama sekali
    return DB::transaction(function () use ($userId, $bookIds) {
        
        // 1. Ambil aturan peminjaman yang aktif
        $aturan = AturanPeminjaman::where('aktif', 1)->firstOrFail();
        $tanggalPinjam = now();

        // 2. Buat Header Transaksi - Status WAJIB menunggu_verifikasi
        $transaction = Transactions::create([
            'kode_transaksi'      => TransactionCodeGenerator::generate(),
            'user_id'             => $userId,
            'tanggal_pinjam'      => $tanggalPinjam,
            'tanggal_jatuh_tempo' => $tanggalPinjam->copy()->addDays($aturan->maks_hari_pinjam),
            'status'              => 'menunggu_verifikasi', // Status awal pengajuan
            'total_denda'         => 0,
            'lunas'               => true,
        ]);

        // 3. Ambil data buku untuk mendapatkan judul & kode_buku
        $books = Book::whereIn('id', $bookIds)->get();

        // 4. Masukkan ke detail transaksi
        $transaction->details()->createMany(
            $books->map(fn ($b) => [
                'id'                  => (string) \Illuminate\Support\Str::uuid(),
                'book_id'             => $b->id,
                'kode_buku'           => $b->kode_buku,
                'judul_buku'          => $b->judul,
                'status'              => 'menunggu_verifikasi', // Detail juga menunggu
                'tanggal_jatuh_tempo' => $tanggalPinjam->copy()->addDays($aturan->maks_hari_pinjam),
                'book_stock_id'       => null, // KOSONGKAN, karena belum diambil stok fisiknya
                'created_at'          => now(),
                'updated_at'          => now(),
            ])->toArray()
        );

        return $transaction->load('details');
    });
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
