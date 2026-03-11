<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\BookStock;
use App\Models\Transactions;
use App\Models\AturanPeminjaman;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Services\Generators\TransactionCodeGenerator;
use Illuminate\Validation\ValidationException;


class AdminTransactionService
{
    // Admin Membua Peminjaman Manual (tanpa memilih eksemplar, atau dengan memilih eksemplar yang direservasi)
    public function createManual(string $userId, array $bookIds, array $bookStockIds = []): Transactions
    {
        return DB::transaction(function () use ($userId, $bookIds, $bookStockIds) {

            $aturan        = AturanPeminjaman::where('aktif', 1)->firstOrFail();
            $tanggalPinjam = Carbon::today();
            $jatuhTempo    = $tanggalPinjam->copy()->addDays($aturan->maks_hari_pinjam);

            $transaksi = Transactions::create([
                'kode_transaksi'      => TransactionCodeGenerator::generate(),
                'user_id'             => $userId,
                'tanggal_pinjam'      => $tanggalPinjam,
                'tanggal_jatuh_tempo' => $jatuhTempo,
                'status'              => 'menunggu_verifikasi',
            ]);

            $books = Book::whereIn('id', $bookIds)->get()->keyBy('id');

            $stockMap = count($bookStockIds) === count($bookIds)
                ? collect($bookIds)->combine($bookStockIds)->toArray()
                : [];

            $details = [];

            foreach ($bookIds as $bookId) {
                $book    = $books[$bookId] ?? null;
                $stockId = $stockMap[$bookId] ?? null;

                if (!$book) continue;

                if ($stockId) {
                    $stok = BookStock::where('id', $stockId)
                        ->where('book_id', $bookId)
                        ->where('status', 'tersedia')
                        ->lockForUpdate()
                        ->first();

                    if (!$stok) {
                        throw new \Exception(
                            "Eksemplar yang dipilih untuk buku \"{$book->judul}\" sudah tidak tersedia."
                        );
                    }

                    $stok->update(['status' => 'direservasi']);
                }

                $details[] = [
                    'book_id'             => $book->id,
                    'kode_buku'           => $book->kode_buku,
                    'judul_buku'          => $book->judul,
                    'tanggal_jatuh_tempo' => $jatuhTempo,
                    'status'              => 'menunggu_verifikasi',
                    'book_stock_id'       => $stockId,
                ];
            }

            $transaksi->details()->createMany($details);

            return $transaksi;
        });
    }

    /**
     * Verifikasi pinjam oleh admin.
     */
    public function verifikasiPinjam($transaction)
    {
        DB::transaction(function () use ($transaction) {

            foreach ($transaction->details as $detail) {

                if ($detail->status !== 'menunggu_verifikasi') {
                    continue;
                }

                if ($detail->book_stock_id) {
                    // ── Kasus A ──
                    $stok = BookStock::where('id', $detail->book_stock_id)
                        ->whereIn('status', ['direservasi', 'tersedia'])
                        ->lockForUpdate()
                        ->first();

                    if (!$stok) {
                        throw new \Exception(
                            "Stok eksemplar untuk buku \"{$detail->judul_buku}\" tidak ditemukan atau status sudah berubah."
                        );
                    }

                    $stok->update(['status' => 'dipinjam']);
                    $detail->update(['status' => 'dipinjam']);

                } else {
                    // ── Kasus B ──
                    $stok = BookStock::where('book_id', $detail->book_id)
                        ->where('status', 'tersedia')
                        ->lockForUpdate()
                        ->first();

                    if (!$stok) {
                        throw new \Exception("Stok buku \"{$detail->judul_buku}\" habis.");
                    }

                    $stok->update(['status' => 'dipinjam']);

                    $detail->update([
                        'book_stock_id' => $stok->id,
                        'status'        => 'dipinjam',
                    ]);
                }
            }

            $transaction->update(['status' => 'dipinjam']);
        });
    }

    public function verifyReturnTransaction(Transactions $transaction, array $data)
    {
        return DB::transaction(function () use ($transaction, $data) {
            $transaction->load('details');

            foreach ($transaction->details as $detail) {
                $payload = [
                    'status'            => $data['status'],
                    'jenis_denda'       => $data['jenis_denda'] ?? null,
                    'denda'             => $data['denda'] ?? 0,
                    'catatan'           => $data['catatan'] ?? null,
                    'jumlah_hari_telat' => $data['jumlah_hari_telat'] ?? 0,
                ];
                $this->verifyReturnDetail($detail, $payload);
            }

            $transaction->refresh()->load('details');

            $statusSelesai = ['dikembalikan', 'terlambat', 'rusak', 'hilang'];
            if ($transaction->details->every(fn ($d) => in_array($d->status, $statusSelesai))) {
                $newStatus = 'dikembalikan';

                if ($transaction->details->contains(fn ($d) => $d->status === 'rusak')) {
                    $newStatus = 'rusak';
                } elseif ($transaction->details->contains(fn ($d) => $d->status === 'hilang')) {
                    $newStatus = 'hilang';
                } elseif ($transaction->details->contains(fn ($d) => $d->status === 'terlambat')) {
                    $newStatus = 'terlambat';
                }

                $totalDenda = $transaction->details->sum('denda');
                $transaction->update([
                    'status'      => $newStatus,
                    'total_denda' => $totalDenda,
                    'lunas'       => $totalDenda <= 0,
                ]);
            }

            return $transaction;
        });
    }

    public function verifyReturnDetail(TransactionDetail $detail, array $data)
    {
        $today      = Carbon::now()->startOfDay();
        $jatuhTempo = Carbon::parse($detail->tanggal_jatuh_tempo)->startOfDay();

        if ($data['status'] === 'terlambat' && $today->lte($jatuhTempo)) {
            throw ValidationException::withMessages([
                'status' => 'Buku belum melewati tanggal jatuh tempo, gunakan status "dikembalikan".'
            ]);
        }

        $jumlahHariTelat = isset($data['jumlah_hari_telat'])
            ? max(0, (int) $data['jumlah_hari_telat'])
            : max(0, $today->gt($jatuhTempo) ? (int) $today->diffInDays($jatuhTempo) : 0);

        $totalDenda = (float) ($data['denda'] ?? 0);

        $stockStatus = match ($data['status']) {
            'rusak'  => 'rusak',
            'hilang' => 'hilang',
            default  => 'tersedia',
        };

        return DB::transaction(function () use ($detail, $data, $today, $jumlahHariTelat, $totalDenda, $stockStatus) {

            $jenisDendaOtomatis = match ($data['status']) {
                'terlambat' => 'telat',
                'rusak'     => 'rusak',
                'hilang'    => 'hilang',
                default     => null,
            };

            $detail->update([
                'status'            => $data['status'],
                'tanggal_kembali'   => $today,
                'jenis_denda'       => $jenisDendaOtomatis,
                'jumlah_hari_telat' => $jumlahHariTelat,
                'denda'             => $totalDenda,
                'status_denda'      => $jenisDendaOtomatis !== null ? 'belum_lunas' : 'lunas',
                'catatan'           => $data['catatan'] ?? null,
            ]);

            if ($detail->book_stock_id) {
                BookStock::where('id', $detail->book_stock_id)
                    ->update(['status' => $stockStatus]);
            }

            $transaction = $detail->transaction->fresh('details');
            $statuses    = $transaction->details->pluck('status');

            if ($statuses->contains('rusak')) {
                $transaction->status = 'rusak';
            } elseif ($statuses->contains('hilang')) {
                $transaction->status = 'hilang';
            } elseif ($statuses->contains('terlambat')) {
                $transaction->status = 'terlambat';
            } elseif ($statuses->every(fn ($s) => $s === 'dikembalikan')) {
                $transaction->status = 'dikembalikan';
            } elseif ($statuses->every(fn ($s) => $s === 'dipinjam')) {
                $transaction->status = 'dipinjam';
            }

            $transaction->total_denda = $transaction->details->sum('denda');
            $transaction->lunas       = $transaction->total_denda <= 0;
            $transaction->save();

            return $detail;
        });
    }

    public function verifikasiDetail(TransactionDetail $detail): void
    {
        DB::transaction(function () use ($detail) {

            if ($detail->book_stock_id) {
                // ── Kasus A: stok sudah direservasi ──
                $stok = BookStock::where('id', $detail->book_stock_id)
                    ->whereIn('status', ['direservasi', 'tersedia'])
                    ->lockForUpdate()
                    ->first();

                if ($stok) {
                    $stok->update(['status' => 'dipinjam']);
                }

                $detail->update(['status' => 'dipinjam']);

            } else {
                // ── Kasus B: pilih stok otomatis ──
                $stok = BookStock::where('book_id', $detail->book_id)
                    ->where('status', 'tersedia')
                    ->lockForUpdate()
                    ->first();

                if (!$stok) {
                    throw new \Exception("Stok buku \"{$detail->judul_buku}\" habis.");
                }

                $stok->update(['status' => 'dipinjam']);

                $detail->update([
                    'book_stock_id' => $stok->id,
                    'status'        => 'dipinjam',
                ]);
            }

            $transaksi = $detail->transaction;
            $details   = $transaksi->details()->get();

            if ($details->every(fn ($d) => $d->status === 'dipinjam')) {
                $transaksi->update(['status' => 'dipinjam']);
            } elseif ($details->contains(fn ($d) => $d->status === 'dipinjam')) {
                $transaksi->update(['status' => 'sebagian_dipinjam']);
            }
        });
    }
}