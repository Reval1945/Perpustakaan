@extends('layouts.anggota')
    
@section('title', 'Petunjuk Penggunaan')

@section('content')

<style>
    /* Soft UI Circle Icon */
    .icon-circle {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        position: relative;
        transition: all 0.3s ease;
    }

    /* Badge Number */
    .step-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--dark);
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-size: 14px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
    }

    /* Hover Effect */
    .transition-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .transition-hover:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }

    /* Soft Colors */
    .bg-primary-soft { background-color: #e8f0fe !important; }
    .bg-success-soft { background-color: #e6fffa !important; }
    .bg-warning-soft { background-color: #fffaf0 !important; }

    /* Typography */
    .text-gray { color: #64748b; }
    
    .fade-in {
        animation: fadeInUp 0.6s ease forwards;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container-fluid py-4">
    <div class="text-center mb-5 fade-in">
        <h2 class="font-weight-bold text-dark" style="letter-spacing: -1px;">Cara Bergabung & Meminjam</h2>
        <p class="text-gray mx-auto" style="max-width: 600px;">
            Nikmati kemudahan akses literasi digital di SMKN 4 Bojonegoro dengan 3 langkah sederhana.
        </p>
    </div>

    <div class="row g-4">
        <div class="col-lg-4 col-md-6 fade-in">
            <div class="card h-100 border-0 shadow-sm transition-hover" style="border-radius: 20px;">
                <div class="card-body p-4 text-center">
                    <div class="icon-circle mb-4 mx-auto bg-primary-soft text-primary">
                        <i class="fas fa-user-plus fa-2x"></i>
                        <span class="step-badge">1</span>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-3">Daftar Akun</h5>
                    <p class="text-gray small px-3">
                        Klik tombol daftar dan isi formulir dengan data diri yang valid. Gunakan email aktif untuk keperluan verifikasi.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 fade-in">
            <div class="card h-100 border-0 shadow-sm transition-hover" style="border-radius: 20px;">
                <div class="card-body p-4 text-center">
                    <div class="icon-circle mb-4 mx-auto bg-success-soft text-success">
                        <i class="fas fa-sign-in-alt fa-2x"></i>
                        <span class="step-badge">2</span>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-3">Login & Verifikasi</h5>
                    <p class="text-gray small px-3">
                        Masuk ke dashboard menggunakan email Anda. Jika status belum aktif, hubungi petugas perpustakaan untuk verifikasi.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 fade-in">
            <div class="card h-100 border-0 shadow-sm transition-hover" style="border-radius: 20px;">
                <div class="card-body p-4 text-center">
                    <div class="icon-circle mb-4 mx-auto bg-warning-soft text-warning">
                        <i class="fas fa-book-reader fa-2x"></i>
                        <span class="step-badge">3</span>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-3">Mulai Jelajahi</h5>
                    <p class="text-gray small px-3">
                        Cari buku favoritmu, klik pinjam, dan ambil buku di perpustakaan atau baca versi digital yang tersedia.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection