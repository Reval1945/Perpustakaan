<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AturanPeminjamanController;
use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\Anggota\DashboardAnggotaController;
use App\Http\Controllers\BookStockController;
use App\Http\Controllers\Anggota\ProfileController;
use App\Http\Controllers\Admin\ProfileAdminController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// AUTH
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/register-warga', [AuthController::class, 'registerWarga'])->name('post.registerwarga');

// USER
Route::middleware(['auth:sanctum', 'role.manual:admin,superadmin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/users/export/excel', [UserController::class, 'exportExcel']);
    Route::get('/users/{id}/library-card', [UserController::class, 'exportLibraryCard']);
});

// LIBRARY CARD
Route::middleware(['auth:sanctum', 'role.manual:user'])->group(function () {
    Route::get('/me/library-card', [UserController::class, 'exportMyLibraryCard']);
});

// API FOR ROLE ADMIN
Route::middleware(['auth:sanctum', 'role.manual:admin'])->group(function () {

    Route::get('/me1',[ProfileAdminController::class,'me']);
    Route::post('/update-profile1',[ProfileAdminController::class,'update']);

    // KATEGORI
    Route::resource('/categories', CategoryController::class);

    // BUKU
    Route::resource('/books', BookController::class);
    Route::get('/books/{book}/stok', [BookStockController::class,'index']);
    Route::post('/books/{book}/stok', [BookStockController::class,'store']);
    Route::delete('/stok/{id}', [BookStockController::class,'destroy']);
    Route::get('/books/export/excel', [BookController::class, 'exportExcel']);

    //  ATURAN PEMINJAMAN
    Route::get('/aturan-peminjaman/aktif', [AturanPeminjamanController::class, 'getAktif']);
    Route::resource('/aturan-peminjaman', AturanPeminjamanController::class);
    
    // TRANSAKSI
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'AdminStore']);
    Route::put('/transactions/{id}/verifikasi-pinjam', [TransactionController::class, 'verifikasiPinjam']);
    Route::put('/transactions/{id}/verifikasi-kembali', [TransactionController::class, 'verifikasiKembali']);
    Route::put('/transactions/{id}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);

    // DETAIL TRANSAKSI
    Route::put('/transactions/{kode}/jatuh-tempo', [TransactionController::class, 'updateJatuhTempo']);
    Route::put('/transaction-detail/{id}/jatuh-tempo', [TransactionController::class, 'updateJatuhTempoDetail']);
    Route::put("/transactions/{id}/verifikasi-detail", [TransactionController::class, 'verifikasiPinjamDetail']);
    Route::get('/transactions/export/excel', [TransactionController::class, 'exportExcel']);
    Route::get('/transaction-details', [TransactionController::class, 'getDetails']);
    Route::put('/transaction-detail/{id}/verify-return',[TransactionController::class, 'verifikasiKembaliDetail']);

    // CETAK TRANSAKSI
    Route::get('/laporan/transaction-detail', [TransactionController::class, 'cetakPdf']);

    // DENDA
    Route::prefix('denda')->group(function () {
        Route::get('/details', [DendaController::class, 'index']);
        Route::put('/details/{detail}', [DendaController::class, 'update']);
    });

    // PENGUNJUNG
    Route::get('/pengunjung/export',[PengunjungController::class,'exportExcel']);
    Route::resource('/pengunjung', PengunjungController::class);
    Route::put('/pengunjung/{id}',[PengunjungController::class,'update']);
    Route::delete('/pengunjung/{id}',[PengunjungController::class,'destroy']);
    
});

// Super Admin
Route::middleware(['auth:sanctum', 'role.manual:superadmin'])->group(function () {
    Route::get('/admins', [AdminController::class, 'getAdmins']);
    Route::post('/admins', [AdminController::class, 'store']);             
    Route::put('/admins/{id}', [AdminController::class, 'update']);  
    Route::delete('/admins/{id}', [AdminController::class, 'destroy']); 
    Route::get('/admins/export', [AdminController::class, 'exportExcel'])->name('api.admin.export');
});

// API FOR ROLE USER
Route::middleware(['auth:sanctum', 'role.manual:user'])->group(function () {
    // DASHBOARD
    Route::get('/anggota/dashboard', [DashboardAnggotaController::class, 'index']);
    Route::get('/dashboard/stats', [DashboardAnggotaController::class, 'stats']);

    Route::get('/me',[ProfileController::class,'me']);
    Route::post('/update-profile',[ProfileController::class,'update']);
    Route::get('/user/print-my-card', [ProfileController::class, 'exportPdf']);

    // PENGUNJUNG
    Route::post('/pengunjung', [PengunjungController::class, 'store']);

    // BUKU
    Route::resource('/list-books', BookController::class)->only(['index', 'show']);

    // TRANSAKSI
    Route::post('/transaksi-pinjam', [TransactionController::class, 'store']);
    Route::put('/transaksi-kembali/{id}', [TransactionController::class, 'ajukanPengembalian']);
    Route::get('/transaksi-me', [TransactionController::class, 'myTransactions']);
    Route::delete('/transaksi-aktif', [TransactionController::class, 'resetAktif']);
    Route::get('/aturanpeminjaman/aktif', [AturanPeminjamanController::class, 'getAktif']);
    Route::get('/transaksi-me/export', [TransactionController::class, 'exportMyTransactions']);

    // DENDA
});
