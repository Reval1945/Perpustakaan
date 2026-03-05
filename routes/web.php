<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\Anggota\KehadiranController;
use App\Http\Controllers\Admin\LaporanDendaController;
use App\Http\Controllers\Anggota\DaftarBukuController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Anggota\DaftarPinjamController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\Anggota\RiwayatPinjamController;
use App\Http\Controllers\Admin\AturanPeminjamanController;
use App\Http\Controllers\Admin\LaporanPeminjamanController;
use App\Http\Controllers\Anggota\DashboardAnggotaController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RegisterWargaController;
use App\Http\Controllers\Admin\PengunjungController;
use App\Http\Controllers\CategoryController;

// Halaman utama
Route::get('/', fn () => view('welcome'));

// Login
Route::get('/login', [AuthController::class, 'loginPage'])->name('login');

// Register
// Route::get('/pilihrole', [AuthController::class, 'pilihRolePage']);
Route::get('/register', [RegisterController::class, 'showRegister']);
Route::get('/registerwarga', [RegisterWargaController::class, 'showRegisterWarga']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Super admin
Route::prefix('superadmin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/admin', [AdminController::class, 'index']);
});


// Admin
Route::prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardAdminController::class, 'index']);

    Route::get('/anggota', [AnggotaController::class, 'index']);

    Route::get('/buku', [BukuController::class, 'index']);

    Route::get('/pengunjung', [PengunjungController::class, 'index']);

    Route::get('/laporanpeminjaman', [LaporanPeminjamanController::class, 'index']);

    Route::get('/laporandenda', [LaporanDendaController::class, 'index']);

    Route::get('/aturanpeminjaman', [AturanPeminjamanController::class, 'index']);

    Route::get('/kategori', [CategoryController::class, 'showKategori']);
});

Route::prefix('admin/transaksi')->group(function () {
    Route::get('/peminjaman', [TransaksiController::class, 'peminjaman']);
    Route::get('/tambahpeminjaman', [TransaksiController::class, 'tambahpeminjaman']);
    Route::get('/pengembalian', [TransaksiController::class, 'pengembalian']);
    Route::get('/tambahpengembalian', [TransaksiController::class, 'tambahpengembalian']);
    Route::get('/edit/{id}', [TransaksiController::class, 'edit']);
    Route::get('/detail/edit/{id}', [TransaksiController::class,'editDetail']);
});

// ANGGOTA
Route::get('/anggota/dashboard', [DashboardAnggotaController::class, 'index'])
    ->name('anggota.dashboard');

Route::get('/anggota/kehadiran', [KehadiranController::class, 'index'])
    ->name('anggota.kehadiran');

Route::get('/anggota/buku', [DaftarBukuController::class, 'index'])
    ->name('anggota.buku');

Route::get('/anggota/daftarpinjam', [DaftarPinjamController::class, 'index'])
    ->name('anggota.daftarpinjam');

Route::get('/anggota/riwayatpinjam', [RiwayatPinjamController::class, 'index'])
    ->name('anggota.riwayatpinjam');

Route::get('/anggota/tambahpeminjaman', function () {
    return view('anggota.tambahpeminjaman');

});

Route::get('/anggota/peminjaman/create', [TransactionController::class, 'create'])
    ->name('anggota.peminjaman.create');

