<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusDendaRequest;
use App\Models\TransactionDetail;
use App\Services\DendaService;
use App\Exports\LaporanDendaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DendaController extends Controller
{
    protected $service;

    public function __construct(DendaService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $status = $request->query('status');

        if ($status && !in_array($status, ['lunas', 'belum_lunas'])) {
            return response()->json(['message' => 'Status tidak valid'], 422);
        }

        $details = $this->service->listDenda($status);

        $data = $details->map(function ($detail) {
            return [
                'id' => $detail->id,
                'judul_buku' => $detail->judul_buku,
                'denda' => $detail->denda,
                'jenis_denda' => $detail->jenis_denda ?? ($detail->status === 'hilang' ? 'hilang' : 'telat'),
                'status_denda' => $detail->status_denda,
                'catatan' => $detail->catatan,
                'tanggal_kembali' => $detail->tanggal_kembali,
                'jumlah_hari_telat' => $detail->jumlah_hari_telat,
                'transaction' => [
                    'id' => $detail->transaction->id ?? null,
                    'tanggal_pinjam' => $detail->transaction->tanggal_pinjam,
                    'tanggal_jatuh_tempo' => $detail->transaction->tanggal_jatuh_tempo ?? null,
                    'user' => [
                        'name' => $detail->transaction->user->name ?? 'User Terhapus'
                    ]
                ]
            ];
        });

        return response()->json([
            'message' => 'Daftar transaksi denda & buku bermasalah',
            'data' => $data
        ]);
    }

    public function cetakPdf($id = null)
    {
        // Gunakan query yang konsisten untuk single maupun massal
        $query = TransactionDetail::with(['transaction.user'])
            ->where(function($q) {
                $q->where('denda', '>', 0)
                  ->orWhereIn('jenis_denda', ['rusak', 'hilang'])
                  ->orWhereIn('status', ['rusak', 'hilang']);
            });

        if ($id) {
            $details = $query->where('id', $id)->get();
            if ($details->isEmpty()) {
                return response()->json(['message' => 'Data denda tidak ditemukan'], 404);
            }
            $filename = 'laporan-denda-' . $id . '.pdf';
        } else {
            $details = $query->get();
            $filename = 'laporan-denda.pdf';
        }

        // Trik agar format('d F Y') tidak error di Blade jika kolom bukan Carbon instance
        foreach ($details as $d) {
            if ($d->transaction) {
                $d->transaction->tanggal_pinjam = \Carbon\Carbon::parse($d->transaction->tanggal_pinjam);
            }
            if ($d->tanggal_kembali) {
                $d->tanggal_kembali = \Carbon\Carbon::parse($d->tanggal_kembali);
            }
        }

        $pdf = Pdf::loadView('laporan.denda', [
            'details' => $details
        ])->setPaper('A4', 'portrait');

        return $pdf->stream($filename);
    }

    /**
     * Generate Excel report of fines
     */
    public function exportExcel()
    {
        return Excel::download(
            new LaporanDendaExport(),
            'laporan-denda.xlsx'
        );
    }


    public function update(
        UpdateStatusDendaRequest $request,
        TransactionDetail $detail
    ) {
        $updated = $this->service->updateStatusDenda(
            $detail,
            $request->status_denda
        );

        return response()->json([
            'message' => 'Status denda berhasil diperbarui',
            'data' => $updated
        ]);
    }
}
