<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TransactionDetail;
use App\Services\TransactionService;
use App\Exports\MyTransactionsExport;
use App\Exports\LaporanpeminjamanExport;
use App\Interfaces\TransactionInterface;
use App\Services\AdminTransactionService;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Admin\VerifyReturnRequest;
use App\Http\Requests\AjukanPengembalianRequest;
use App\Http\Requests\Admin\StoreTransactionRequest;
use App\Http\Requests\Admin\UpdateTransactionRequest;
use App\Http\Requests\Transaction\StorePeminjamanRequest;
use App\Http\Requests\Transaction\VerifyReturnAllRequest;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    private TransactionInterface $repository;
    private TransactionService $service;
    private AdminTransactionService $admin_service;

    public function __construct(TransactionInterface $repository, TransactionService $service, AdminTransactionService $admin_service)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->admin_service = $admin_service;
    }

    public function index()
    {
        return response()->json([
            'data' => $this->repository->getAll()
        ]);
    }

    public function getDetails(){
        return response()->json([
            'data' => $this->repository->getDetails()
        ]);
    }

    public function store(StorePeminjamanRequest $request)
    {
        $userId = $request->user()->id;

        $transaksi = $this->service->createPeminjaman(
            $userId,
            $request->validated()['book_ids']
        );
        return response()->json([
            'message' => 'Peminjaman diajukan, menunggu verifikasi admin',
            'data'    => $transaksi
        ], 201);
    }

    public function AdminStore(StoreTransactionRequest $request)
    {
        try {
            $transaksi = $this->admin_service->createManual(
                $request->user_id,
                $request->book_ids
            );

            return response()->json([
                'message' => 'Transaksi berhasil dibuat',
                'data' => $transaksi->load('details.book')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat transaksi'
            ], 500);
        }
    }

    public function verifikasiPinjamDetail(string $detailId)
    {
        try {
            $detail = TransactionDetail::findOrFail($detailId);

            $this->admin_service->verifikasiDetail($detail);

            return response()->json([
                'message' => 'Buku berhasil diverifikasi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal verifikasi buku'
            ], 500);
        }
    }


    public function ajukanPengembalian(AjukanPengembalianRequest $request, string $id)
    {
        $this->service->ajukanPengembalian($id, $request->detail_ids);

        return response()->json([
            'message' => 'Pengembalian diajukan'
        ]);
    }

    public function verifikasiPinjam(string $id)
    {
        try {
            $transaksi = $this->repository->findById($id);

            $this->admin_service->verifikasiPinjam($transaksi);

            return response()->json([
                'message' => 'Peminjaman disetujui'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal verifikasi peminjaman'
            ], 500);
        }
    }

    public function verifikasiKembali(VerifyReturnAllRequest $request, $id) {
        try {
            $transaction = Transactions::findOrFail($id);
            $data = $request->validated();

            // LOGIKA TAMBAHAN: Jika status verifikasi massal adalah rusak/hilang
            if ($data['status'] === 'hilang') {
                $data['denda'] = 0;
                $data['jenis_denda'] = 'hilang';
                $data['catatan'] = "[HILANG] " . ($data['catatan'] ?? 'Wajib ganti buku fisik.');
            } elseif ($data['status'] === 'rusak') {
                $data['jenis_denda'] = 'rusak';
            }

            $this->admin_service->verifyReturnTransaction(
                $transaction,
                $data // Kirim data yang sudah dimodifikasi
            );

            return response()->json([
                'message' => 'Seluruh buku berhasil diverifikasi'
            ]);

        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function verifikasiKembaliDetail(VerifyReturnRequest $request, string $detailId) 
    {
        try {
            $detail = TransactionDetail::with('transaction')->findOrFail($detailId);
            $validatedData = $request->validated();

            // 1. LOGIKA UNTUK HILANG
            if ($validatedData['status'] === 'hilang') {
                $validatedData['denda'] = 0; // Ketua minta ganti buku, jadi uang 0
                $validatedData['jenis_denda'] = 'hilang';
                $validatedData['catatan'] = "[HILANG - WAJIB GANTI BUKU] " . ($validatedData['catatan'] ?? '');
            } 
            
            // 2. LOGIKA UNTUK RUSAK
            elseif ($validatedData['status'] === 'rusak') {
                $validatedData['jenis_denda'] = 'rusak';
                // Nilai denda tidak diubah, tetap pakai input manual dari admin
            }

            // Simpan ke Database melalui Service
            $this->admin_service->verifyReturnDetail(
                $detail,
                $validatedData
            );

            return response()->json(['message' => 'Verifikasi berhasil disimpan']);

        } catch (ValidationException $e) {
            return response()->json(['message' => collect($e->errors())->flatten()->first()], 422);
        }
    }

    public function myTransactions(Request $request)
    {
        try {
            $userId = $request->user()->id;

            $transaksi = $this->repository->getByUserId($userId);

            return response()->json([
                'message' => 'Berhasil menampilkan transaksi anda',
                'data'    => $transaksi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e
            ], 500);
        }
    }

    public function exportMyTransactions(Request $request)
    {
        $userId = $request->user()->id;

        return Excel::download(
            new MyTransactionsExport($userId),
            'riwayat_peminjaman.xlsx'
        );
    }

    public function exportLaporanPeminjaman()
    {
        return Excel::download(
            new LaporanpeminjamanExport(),
            'laporan-peminjaman.xlsx'
        );
    }

    public function update(UpdateTransactionRequest $request, string $id)
    {
        try {
            $transaksi = $this->repository->findById($id);

            $updated = $this->repository->update(
                $transaksi,
                $request->validated()
            );

            return response()->json([
                'message' => 'Transaksi berhasil diperbarui',
                'data'    => $updated
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui transaksi'
            ], 500);
        }
    }

    public function requestPerpanjangan(Request $request, $id)
    {
        $request->validate([
            'tanggal_diminta' => 'required|date|after:today'
        ]);

        // Cari detail transaksi berdasarkan UUID/ID
        $detail = TransactionDetail::findOrFail($id);

        // Cek apakah statusnya memang bisa diperpanjang
        if ($detail->status !== 'dipinjam' && $detail->status !== 'diperpanjang') {
            return response()->json(['message' => 'Status buku tidak memungkinkan untuk perpanjangan'], 400);
        }

        $detail->update([
            'status' => 'mengajukan_perpanjangan',
            'tgl_permintaan_perpanjangan' => $request->tanggal_diminta
        ]);

        return response()->json(['message' => 'Permintaan perpanjangan berhasil dikirim ke pustakawan']);
    }

    public function ajukanPerpanjangan(Request $request, $id)
    {
        // $id adalah ID dari transaction_details
        $detail = TransactionDetail::findOrFail($id);
        
        // Update status di level detail buku
        $detail->update([
            'status' => 'mengajukan_perpanjangan'
        ]);

        return response()->json([
            'message' => 'Permintaan perpanjangan buku berhasil dikirim'
        ]);
    }

   // FUNGSI 1: Memperpanjang SEMUA buku dalam satu transaksi
public function updateJatuhTempo(Request $request, $kode)
{
    $validated = $request->validate([
        'tanggal_jatuh_tempo' => 'required|date'
    ]);

    return DB::transaction(function () use ($validated, $kode) {
        $trx = Transactions::where('kode_transaksi', $kode)->firstOrFail();

        // 1. Update Header
        $trx->update([
            'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo']
        ]);

        // 2. Update SEMUA detail menjadi "diperpanjang"
        $trx->details()->update([
            'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
            'status' => 'diperpanjang'
        ]);

        return response()->json([
            'message' => 'Semua buku dalam transaksi ini berhasil diperpanjang',
            'data' => $trx->load('details')
        ]);
    });
}

// FUNGSI 2: Memperpanjang HANYA SATU buku (berdasarkan ID detail)
public function updateJatuhTempoDetail(Request $request, $id)
{
    $request->validate([
        'tanggal_jatuh_tempo' => 'required|date'
    ]);

    return DB::transaction(function () use ($request, $id) {
        $detail = TransactionDetail::findOrFail($id);

        // 1. Update baris detail, kembalikan tgl_permintaan ke null
        $detail->update([
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'status' => 'diperpanjang',
            'tgl_permintaan_perpanjangan' => null 
        ]);

        // 2. Sinkronisasi Tanggal Header (Ambil tanggal paling jauh/max)
        $trx = Transactions::find($detail->transaction_id);
        if ($trx) {
            $maxDate = TransactionDetail::where('transaction_id', $trx->id)->max('tanggal_jatuh_tempo');
            $trx->update(['tanggal_jatuh_tempo' => $maxDate]);
        }

        return response()->json([
            'message' => 'Buku berhasil diperpanjang',
            'data' => $detail
        ]);
    });
}

    public function destroy(string $id)
    {
        try {
            $transaksi = $this->repository->findById($id);
            $this->repository->delete($transaksi);

            return response()->json([
                'message' => 'Transaksi berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus transaksi'
            ], 500);
        }
    }

    public function cetakPdf()
    {
        $details = TransactionDetail::with([
            'transaction.user',
            'book'
        ])->get();

        $pdf = Pdf::loadView('laporan.transaction-detail', [
            'details' => $details
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-transaction-detail.pdf');
    }

    public function create(Request $request)
    {
        $book = null;

        // Ambil buku jika ada parameter book_id di URL
        if ($request->book_id) {
            $book = Book::find($request->book_id);
        }

        // Kirim ke view tambahpeminjaman
        return view('anggota.tambahpeminjaman', compact('book'));
    }
    public function show($id)
    {
        $data = Transactions::findOrFail($id);

        return response()->json([
            'id' => $data->id,
            'tanggal_pinjam' => $data->tanggal_pinjam,
            'tanggal_jatuh_tempo' => $data->tanggal_jatuh_tempo
        ]);
    }
}

