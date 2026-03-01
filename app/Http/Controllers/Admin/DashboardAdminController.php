<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Transactions;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function stats(Request $request)
    {
        try {
            // 1. Hitung Statistik Utama
            $totalAnggota = User::where('role', 'user')->count();
            $totalBuku = Book::count();
            $peminjamanAktif = TransactionDetail::where('status', 'dipinjam')->count();
            $terlambat = TransactionDetail::where('status', 'terlambat')->count();

            // 2. Data untuk Chart (7 Hari Terakhir) - Dioptimalkan dengan 1 Query
            $chartLabels = [];
            $chartDataMap = [];
            
            // Siapkan label dan default value 0 untuk 7 hari terakhir
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $chartLabels[] = $date->translatedFormat('D'); 
                $chartDataMap[$date->toDateString()] = 0;
            }

            // Ambil data dari database dalam 1 kali proses
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $dailyCounts = TransactionDetail::where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                ->groupBy('date')
                ->pluck('count', 'date');

            // Gabungkan hasil query dengan map tanggal yang sudah dibuat
            foreach ($dailyCounts as $date => $count) {
                if (isset($chartDataMap[$date])) {
                    $chartDataMap[$date] = $count;
                }
            }
            
            $chartData = array_values($chartDataMap);

            // 3. Buku Terbaru (Ambil 5 terakhir)
            $bukuTerbaru = Book::select('judul')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 4. Log Peminjaman Terkini (Join dengan User dan Book)
            $recentTransaksi = TransactionDetail::with(['transaction.user', 'book'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    // Penambahan validasi untuk mencegah error jika relasi kosong
                    $tanggalPinjam = optional($item->transaction)->tanggal_pinjam;

                    return [
                        'nama_anggota'   => $item->transaction->user->name ?? 'User Terhapus',
                        'judul_buku'     => $item->book->judul ?? 'Buku Terhapus',
                        'tanggal_pinjam' => $tanggalPinjam ? Carbon::parse($tanggalPinjam)->translatedFormat('d M Y') : '-',
                        'status'         => ucfirst($item->status),
                    ];
                });

            return response()->json([
                'total_anggota'    => $totalAnggota,
                'total_buku'       => $totalBuku,
                'peminjaman_aktif' => $peminjamanAktif,
                'terlambat'        => $terlambat,
                'chart_labels'     => $chartLabels,
                'chart_data'       => $chartData,
                'buku_terbaru'     => $bukuTerbaru,
                'recent_transaksi' => $recentTransaksi
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memuat data dashboard: ' . $e->getMessage()
            ], 500);
        }
    }
}