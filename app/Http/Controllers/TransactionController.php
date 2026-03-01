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
            $this->admin_service->verifyReturnTransaction(
                $transaction,
                $request->validated()
            );

            return response()->json([
                'message' => 'Seluruh buku berhasil diverifikasi'
            ]);

        } catch (ValidationException $e) {
            dd($e->getMessage());
        }
    }

    public function verifikasiKembaliDetail(VerifyReturnRequest $request, string $detailId) 
    {
        try {

            $detail = TransactionDetail::with('transaction')
                ->findOrFail($detailId);

            $this->admin_service->verifyReturnDetail(
                $detail,
                $request->validated()
            );

            return response()->json([
                'message' => 'Pengembalian buku berhasil diverifikasi'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => collect($e->errors())->flatten()->first()
            ], 422);
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
            'daftar_peminjaman.xlsx'
        );
    }

    public function exportExcel()
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

   public function updateJatuhTempo(Request $request, $kode)
    {
        $validated = $request->validate([
            'tanggal_jatuh_tempo' => 'required|date'
        ]);

        // Menggunakan DB Transaction agar jika satu gagal, semua batal (menjaga integritas data)
        return DB::transaction(function () use ($validated, $kode) {
            
            $trx = Transactions::where('kode_transaksi', $kode)->firstOrFail();

            // 1. Update di tabel Transactions (Header)
            $trx->update([
                'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo']
            ]);

            // 2. Update SEMUA baris di tabel TransactionDetail yang memiliki transaction_id sama
            // Inilah yang membuat data di sisi Anggota ikut berubah
            $trx->details()->update([
                'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo']
            ]);

            return response()->json([
                'message' => 'Tanggal jatuh tempo berhasil diperbarui di header dan semua detail',
                'data' => $trx->load('details') // Load details agar JSON yang dikembalikan lengkap
            ]);
        });
    }

    public function updateJatuhTempoDetail(Request $request, $id)
    {
        $request->validate([
            'tanggal_jatuh_tempo' => 'required|date'
        ]);

        $detail = TransactionDetail::find($id);

        if(!$detail){
            return response()->json([
                'message'=>'Detail tidak ditemukan'
            ],404);
        }

        $detail->update([
            'tanggal_jatuh_tempo'=>$request->tanggal_jatuh_tempo
        ]);

        return response()->json([
            'message'=>'Jatuh tempo buku berhasil diupdate',
            'data'=>$detail
        ]);
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

