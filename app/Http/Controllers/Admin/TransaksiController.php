<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use App\Models\Transactions;

class TransaksiController extends Controller
{
    public function peminjaman()
    {   
        return view('admin.transaksi.peminjaman');
    }

    public function tambahpeminjaman()
    {   
        return view('admin.transaksi.tambahpeminjaman');
    }

    public function pengembalian()
    {
        return view('admin.transaksi.pengembalian');
    }

    public function tambahpengembalian()
    {
        return view('admin.transaksi.tambahpengembalian');
    }

   public function edit($id)
    {
        // Memuat transaksi beserta semua detail bukunya
        $trx = Transactions::with('details')->findOrFail($id);

        return view('admin.transaksi.edit-transaksi', [
            'trx' => $trx,
            'detail' => null, // Null karena ini mode edit massal (semua buku)
            'defaultDate' => $trx->tanggal_jatuh_tempo
        ]);
    }

    public function editDetail($id)
    {
        // Memuat detail buku spesifik beserta data transaksi induknya
        $detail = TransactionDetail::with('transaction.details')->findOrFail($id);

        $defaultDate = $detail->tgl_permintaan_perpanjangan ?? $detail->tanggal_jatuh_tempo;

        return view('admin.transaksi.edit-transaksi', [
            'detail' => $detail,
            'trx' => $detail->transaction,
            'defaultDate' => $defaultDate
        ]);
    }


}
