<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class DashboardAnggotaController extends Controller
{
    public function index()
    {
        return view('anggota.dashboard');
    }
    public function stats()
    {
        $total = TransactionDetail::count();

        $belumKembali = TransactionDetail::where('status', 'dipinjam')->count();

        $sudahKembali = TransactionDetail::where('status', 'dikembalikan')->count();

        $terlambat = TransactionDetail::where('status', 'terlambat')->count();

        return response()->json([
            'total' => $total,
            'belum_kembali' => $belumKembali,
            'sudah_kembali' => $sudahKembali,
            'terlambat' => $terlambat
        ]);
    }
}

