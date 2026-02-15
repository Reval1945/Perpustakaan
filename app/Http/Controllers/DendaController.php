<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusDendaRequest;
use App\Models\TransactionDetail;
use App\Services\DendaService;
use Illuminate\Http\Request;

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
                    'tanggal_pinjam' => $detail->transaction->tanggal_pinjam,
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
