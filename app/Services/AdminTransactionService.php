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
    public function createManual(string $userId, array $bookIds): Transactions
    {
        return DB::transaction(function () use ($userId, $bookIds) {

            $aturan = AturanPeminjaman::where('aktif', 1)->firstOrFail();
            $tanggalPinjam = Carbon::today();

            $transaksi = Transactions::create([
                'kode_transaksi' => TransactionCodeGenerator::generate(),
                'user_id'             => $userId,
                'tanggal_pinjam'      => $tanggalPinjam,
                'tanggal_jatuh_tempo' => $tanggalPinjam->copy()
                    ->addDays($aturan->maks_hari_pinjam),
                'status'              => 'dipinjam',
            ]);

            $books = Book::whereIn('id', $bookIds)->get();

            $transaksi->details()->createMany(
                $books->map(fn ($b) => [
                    'book_id'    => $b->id,
                    'kode_buku'  => $b->kode_buku,
                    'judul_buku' => $b->judul,
                    'tanggal_jatuh_tempo' => $tanggalPinjam->copy()
                    ->addDays($aturan->maks_hari_pinjam),
                    'status'     => 'dipinjam',
                ])->toArray()
            );

            return $transaksi;
        });
    }

    public function verifikasiPinjam($transaction)
    {
        DB::transaction(function () use ($transaction) {

            foreach ($transaction->details as $detail) {

                if ($detail->status !== 'menunggu_verifikasi') {
                    continue;
                }

                // ambil stok tersedia
                $stok = BookStock::where('book_id', $detail->book_id)
                    ->where('status', 'tersedia')
                    ->lockForUpdate()
                    ->first();

                if (!$stok) {
                    throw new \Exception("Stok buku {$detail->judul_buku} habis");
                }

                // ubah status stok
                $stok->update([
                    'status' => 'dipinjam'
                ]);

                // assign stok ke detail
                $detail->update([
                    'book_stock_id' => $stok->id,
                    'status' => 'dipinjam'
                ]);
            }

            // update transaksi utama
            $transaction->update([
                'status' => 'dipinjam'
            ]);
        });
    }
    
    public function verifyReturnTransaction(Transactions $transaction, array $data)
    {
        return DB::transaction(function () use ($transaction, $data) {

            $transaction->load('details');

            foreach ($transaction->details as $detail) {

                $today = Carbon::today();
                $jatuhTempo = Carbon::parse($detail->tanggal_jatuh_tempo);

                $hariTelat = max(0, $jatuhTempo->diffInDays($today, false));

                $payload = [
                    'status' => $data['status'],
                    'jenis_denda' => $data['jenis_denda'] ?? null,
                    'denda'       => $data['denda'] ?? 0,
                ];

                $this->verifyReturnDetail($detail, $payload);
            }

            // reload detail terbaru dari DB
            $transaction->refresh();
            $transaction->load('details');

            // update status transaksi sekali saja
            if ($transaction->details->every(fn ($d) =>
                in_array($d->status, ['dikembalikan','terlambat','rusak','hilang'])
            )) {
                $transaction->update(['status' => 'dikembalikan']);
            }

            return $transaction;
        });
    }

    public function verifyReturnDetail(TransactionDetail $detail, array $data)
    {
        $today = Carbon::now();
        $jatuhTempo = Carbon::parse($detail->tanggal_jatuh_tempo)->startOfDay();

        if ($data['status'] === 'terlambat' && !$jatuhTempo->isPast()) {
            throw ValidationException::withMessages([
                'status' => 'Pengembalian belum melewati jatuh tempo'
            ]);
        }

        $jumlahHariTelat = $today->greaterThan($jatuhTempo)
            ? $jatuhTempo->diffInDays($today)
            : 0;

        $nominalPerHari = $data['denda'] ?? 0;
        $totalDenda = $jumlahHariTelat * $nominalPerHari;

        $detail->update([
            'status'            => $data['status'],
            'tanggal_kembali'   => $today,
            'jenis_denda'       => $jumlahHariTelat > 0 ? $data['jenis_denda'] : null,
            'jumlah_hari_telat' => $jumlahHariTelat,
            'denda'             => $totalDenda,
            'catatan'           => $data['catatan'] ?? null,
        ]);

        return $detail;
    }

    public function verifikasiDetail(TransactionDetail $detail): void
    {
        DB::transaction(function () use ($detail) {

            $detail->update([
                'status' => 'dipinjam'
            ]);

            $transaksi = $detail->transaction;

            // 2. Ambil semua detail transaksi
            $details = $transaksi->details;

            // 3. Tentukan status transaksi
            if ($details->every(fn ($d) => $d->status === 'dipinjam')) {
                $transaksi->update(['status' => 'dipinjam']);
            } elseif ($details->contains(fn ($d) => $d->status === 'dipinjam')) {
                $transaksi->update(['status' => 'sebagian_dipinjam']);
            }
        });
    }
}

