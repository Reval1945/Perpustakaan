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
            return response()->json([
                'message' => 'Status tidak valid'
            ], 422);
        }

        $details = $this->service->listDenda($status);

        $data = $details->map(function ($detail) {
            return [
                'id' => $detail->id,
                'judul_buku' => $detail->judul_buku,
                'denda' => $detail->denda,
                'jenis_denda' => $detail->jenis_denda,
                'status_denda' => $detail->status_denda,
                'tanggal_kembali' => $detail->tanggal_kembali,
                'jumlah_hari_telat' => $detail->jumlah_hari_telat,
                'transaction' => [
                    'id' => $detail->transaction->id ?? null,
                    'tanggal_pinjam' => $detail->transaction->tanggal_pinjam,
                    'tanggal_jatuh_tempo' => $detail->transaction->tanggal_jatuh_tempo ?? null,
                    'user' => [
                        'name' => $detail->transaction->user->name
                    ]
                ]
            ];
        });

        return response()->json([
            'message' => 'Daftar transaksi yang memiliki denda',
            'data' => $data
        ]);
    }

    /**
     * Generate PDF report of fines using DomPdf
     * @param int|null $id - optional detail ID for single record
     */
    public function cetakPdf($id = null)
    {
        if ($id) {
            // Load single detail by ID
            $details = TransactionDetail::with(['transaction.user'])
                ->where('id', $id)
                ->where('denda', '>', 0)
                ->get();
            
            if ($details->isEmpty()) {
                return response()->json([
                    'message' => 'Data denda tidak ditemukan'
                ], 404);
            }
            
            $filename = 'laporan-denda-' . $id . '.pdf';
        } else {
            // Load all details that include a denda value
            $details = TransactionDetail::with(['transaction.user'])
                ->where('denda', '>', 0)
                ->get();
            
            $filename = 'laporan-denda.pdf';
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
