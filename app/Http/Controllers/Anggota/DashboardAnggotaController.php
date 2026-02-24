<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardAnggotaController extends Controller
{
    public function index()
    {
        return view('anggota.dashboard');
    }

    public function stats(Request $request)
    {
        // Pastikan kita hanya mengambil data milik user yang sedang login
        $user = $request->user();
        $userId = $user->id;  
        
        // 1. Data Ringkasan (Info Cards)
        $total = TransactionDetail::whereHas('transaction', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->count();

        $belumKembali = TransactionDetail::whereHas('transaction', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('status', 'dipinjam')->count();

        $sudahKembali = TransactionDetail::whereHas('transaction', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('status', 'dikembalikan')->count();

        $terlambat = TransactionDetail::whereHas('transaction', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('status', 'terlambat')->count();

        // 2. Data Chart Garis (6 Bulan Terakhir)
        $chartLabels = [];
        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartLabels[] = $month->translatedFormat('M'); // Contoh: Jan, Feb, Mar

            $count = TransactionDetail::whereHas('transaction', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->whereMonth('created_at', $month->month)
            ->whereYear('created_at', $month->year)
            ->count();

            $chartData[] = $count;
        }

        return response()->json([
            'total' => $total,
            'belum_kembali' => $belumKembali,
            'sudah_kembali' => $sudahKembali,
            'terlambat' => $terlambat,
            'chart_labels' => $chartLabels, // Dikirim ke Chart.js
            'chart_data' => $chartData      // Dikirim ke Chart.js
        ]);
    }
}