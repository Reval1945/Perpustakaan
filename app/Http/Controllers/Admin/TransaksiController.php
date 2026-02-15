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
        $trx = Transactions::with('details')->findOrFail($id);

    return view('admin.transaksi.edit-transaksi', [
        'trx' => $trx,
        'detail' => $trx->details->first()
    ]);
    }
    public function editDetail($id)
    {
        $detail = TransactionDetail::with('transaction')
                    ->findOrFail($id);

        return view('admin.transaksi.edit-transaksi', [
            'detail' => $detail,
            'trx' => $detail->transaction
        ]);
    }


}
