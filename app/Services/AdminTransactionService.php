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
                'status'              => 'menunggu_verifikasi',
            ]);

            $books = Book::whereIn('id', $bookIds)->get();

            $transaksi->details()->createMany(
                $books->map(fn ($b) => [
                    'book_id'    => $b->id,
                    'kode_buku'  => $b->kode_buku,
                    'judul_buku' => $b->judul,
                    'tanggal_jatuh_tempo' => $tanggalPinjam->copy()
                    ->addDays($aturan->maks_hari_pinjam),
                    'status'     => 'menunggu_verifikasi',
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
                // Kita bungkus payload agar konsisten dengan input yang dibutuhkan verifyReturnDetail
                $payload = [
                    'status'      => $data['status'],
                    'jenis_denda' => $data['jenis_denda'] ?? null,
                    'denda'       => $data['denda'] ?? 0,
                    'catatan'     => $data['catatan'] ?? null,
                    'jumlah_hari_telat' => $data['jumlah_hari_telat'] ?? 0,
                ];

                $this->verifyReturnDetail($detail, $payload);
            }

            $transaction->refresh();
            $transaction->load('details');

            // Update status transaksi utama jika semua buku sudah kembali/diproses
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

                // update aggregate penalties and payment flag as well
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
        // normalize to startOfDay to avoid off-by-one due to time portions
        $today = Carbon::now()->startOfDay();
        $jatuhTempo = Carbon::parse($detail->tanggal_jatuh_tempo)->startOfDay();

        // 1. Validasi Status Terlambat
        if ($data['status'] === 'terlambat' && $today->lte($jatuhTempo)) {
            throw ValidationException::withMessages([
                'status' => 'Buku belum melewati tanggal jatuh tempo, gunakan status "dikembalikan".'
            ]);
        }

        // 2. Hitung jumlah hari telat (untuk record DB) - pastikan non-negative.
        // API boleh mengirimkan nilai ini (dihitung klien), namun untuk keamanan
        // kita tetap pastikan non-negative dan minimal 0.
        if (isset($data['jumlah_hari_telat'])) {
            $jumlahHariTelat = max(0, (int) $data['jumlah_hari_telat']);
        } else {
            $jumlahHariTelat = $today->gt($jatuhTempo) ? (int) $today->diffInDays($jatuhTempo) : 0;
            $jumlahHariTelat = max(0, $jumlahHariTelat);
        }

        // 3. LOGIKA DENDA (Disederhanakan)
        // Karena JS sudah menghitung (Hari x Tarif), di sini kita langsung ambil totalnya
        $totalDenda = (float) ($data['denda'] ?? 0);
        
        // Tentukan jenis denda untuk ENUM database
        if ($data['status'] === 'terlambat') {
            $jenisDendaDB = 'telat';
        } else {
            $jenisDendaDB = $data['jenis_denda'] ?? null;
        }

        // 4. Mapping status stock
        $stockStatus = match ($data['status']) {
            'rusak'  => 'rusak',
            'hilang' => 'hilang',
            default  => 'tersedia' // 'dikembalikan' & 'terlambat' buku jadi tersedia lagi
        };

        return DB::transaction(function () use ($detail, $data, $today, $jumlahHariTelat, $totalDenda, $stockStatus, $jenisDendaDB) {

            // 5. Update detail
            $detail->update([
                'status'            => $data['status'],
                'tanggal_kembali'   => $today,
                'jenis_denda'       => ($totalDenda > 0) ? $jenisDendaDB : null,
                'jumlah_hari_telat' => $jumlahHariTelat,
                'denda'             => $totalDenda,
                'status_denda'      => ($totalDenda > 0) ? 'belum_lunas' : 'lunas',
                'catatan'           => $data['catatan'] ?? null,
            ]);

            // 6. Update status fisik buku (BookStock)
            if ($detail->book_stock_id) {
                BookStock::where('id', $detail->book_stock_id)->update([
                    'status' => $stockStatus
                ]);
            }

            // 7. synchronize header after a single detail was verified
            $transaction = $detail->transaction->fresh('details');
            $statuses = $transaction->details->pluck('status');

            // compute header status based on detail statuses
            if ($statuses->contains('rusak')) {
                $transaction->status = 'rusak';
            } elseif ($statuses->contains('hilang')) {
                $transaction->status = 'hilang';
            } elseif ($statuses->contains('terlambat')) {
                $transaction->status = 'terlambat';
            } elseif ($statuses->every(fn($s) => $s === 'dikembalikan')) {
                $transaction->status = 'dikembalikan';
            } elseif ($statuses->every(fn($s) => $s === 'dipinjam')) {
                $transaction->status = 'dipinjam';
            }

            // update aggregate penalties too
            $transaction->total_denda = $transaction->details->sum('denda');
            $transaction->lunas = $transaction->total_denda <= 0;
            $transaction->save();

            return $detail;
        });
    }

    public function verifikasiDetail(TransactionDetail $detail): void
    {
        DB::transaction(function () use ($detail) {
            $detail->update(['status' => 'dipinjam']);

            $transaksi = $detail->transaction;
            $details = $transaksi->details;

            // Cek apakah semua atau sebagian sudah dipinjam
            if ($details->every(fn ($d) => $d->status === 'dipinjam')) {
                $transaksi->update(['status' => 'dipinjam']);
            } else if ($details->contains(fn ($d) => $d->status === 'dipinjam')) {
                $transaksi->update(['status' => 'sebagian_dipinjam']);
            }
        });
    }
}

