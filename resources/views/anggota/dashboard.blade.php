@extends('layouts.anggota')

@section('title', 'Dashboard Anggota')

@section('content')

<!-- PAGE HEADING -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
    </h1>
    <div class="mt-3 mt-sm-0">
        <span class="badge badge-primary p-2 shadow-sm">
            <i class="fas fa-calendar-alt mr-1"></i>{{ date('d F Y') }}
        </span>
    </div>
</div>

<!-- WELCOME CARD -->
<div class="alert alert-info bg-gradient-info text-white border-0 shadow-sm mb-4">
    <div class="d-flex align-items-center">
        <div class="mr-3 d-none d-sm-block">
            <i class="fas fa-user-circle fa-3x"></i>
        </div>
        <div>
            <h5 class="mb-1">Selamat Datang, {{ Auth::user()->name ?? 'Anggota' }}!</h5>
            <p class="mb-0">Kelola peminjaman bukumu dengan mudah di dashboard ini.</p>
        </div>
    </div>
</div>

<!-- INFO CARDS -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Peminjaman
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalBuku">0</div>
                        <small class="text-muted">Buku</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Belum Dikembalikan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="belumKembali">0</div>
                        <small class="text-muted">Buku</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Sudah Dikembalikan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="sudahKembali">0</div>
                        <small class="text-muted">Buku</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Keterlambatan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="terlambat">0</div>
                        <small class="text-muted">Buku</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ALERT INFO - Tampil hanya jika ada buku yang belum dikembalikan -->
<div id="alertBelumKembali" class="alert alert-warning border-left-warning shadow-sm" style="display: none;">
    <div class="d-flex align-items-center">
        <div class="mr-3">
            <i class="fas fa-exclamation-circle fa-2x"></i>
        </div>
        <div>
            <h6 class="font-weight-bold mb-1">⚠️ Peringatan!</h6>
            <p class="mb-0">Kamu masih memiliki <strong id="jumlahBelumKembali">0</strong> buku yang belum dikembalikan.  
            Harap segera mengembalikan buku agar tidak terkena denda.</p>
        </div>
    </div>
</div>

<!-- RIWAYAT PEMINJAMAN TERAKHIR -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-history mr-1"></i>Riwayat Peminjaman Terakhir
        </h6>
        <span class="badge badge-primary px-3 py-2" id="totalRiwayat">3 Peminjaman</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered" width="100%" cellspacing="0">
                <thead class="bg-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="40%">Judul Buku</th>
                        <th width="15%">Tanggal Pinjam</th>
                        <th width="15%">Tanggal Kembali</th>
                        <th width="15%">Status</th>
                        <th width="10%">Denda</th>
                    </tr>
                </thead>
                <tbody id="riwayatTableBody">
                    <tr>
                        <td>1</td>
                        <td class="font-weight-500">Pemrograman Web</td>
                        <td>01-01-2026</td>
                        <td class="text-muted">-</td>
                        <td>
                            <span class="badge badge-warning px-3 py-2">
                                <i class="fas fa-book-open mr-1"></i>Dipinjam
                            </span>
                        </td>
                        <td class="text-danger font-weight-bold">-</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td class="font-weight-500">Basis Data</td>
                        <td>20-12-2025</td>
                        <td>27-12-2025</td>
                        <td>
                            <span class="badge badge-success px-3 py-2">
                                <i class="fas fa-check mr-1"></i>Dikembalikan
                            </span>
                        </td>
                        <td class="text-success">Rp 0</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td class="font-weight-500">Algoritma Dasar</td>
                        <td>15-12-2025</td>
                        <td>22-12-2025</td>
                        <td>
                            <span class="badge badge-success px-3 py-2">
                                <i class="fas fa-check mr-1"></i>Dikembalikan
                            </span>
                        </td>
                        <td class="text-success">Rp 0</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                <i class="fas fa-info-circle mr-1"></i>Menampilkan 3 data terakhir
            </div>
            <a href="{{ url('/anggota/riwayat-peminjaman') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-list mr-1"></i>Lihat Selengkapnya
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>

<script>
async function loadDashboardStats() {
    try {
        const token = localStorage.getItem('token');
        
        // Tampilkan loading state
        showLoading();

        const response = await fetch('/api/dashboard/stats', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        // Update cards dengan animasi
        animateNumber('totalBuku', data.total || 0);
        animateNumber('belumKembali', data.belum_kembali || 0);
        animateNumber('sudahKembali', data.sudah_kembali || 0);
        animateNumber('terlambat', data.terlambat || 0);

        // Tampilkan/sembunyikan alert
        const alertBelumKembali = document.getElementById('alertBelumKembali');
        if (data.belum_kembali > 0) {
            alertBelumKembali.style.display = 'block';
            document.getElementById('jumlahBelumKembali').innerText = data.belum_kembali;
        } else {
            alertBelumKembali.style.display = 'none';
        }

        // Update total riwayat
        document.getElementById('totalRiwayat').innerText = (data.total_riwayat || 3) + ' Peminjaman';

        // Update tabel riwayat jika ada data
        if (data.riwayat && data.riwayat.length > 0) {
            updateRiwayatTable(data.riwayat);
        }

    } catch (error) {
        console.error('Gagal memuat data dashboard:', error);
        showError();
    }
}

function animateNumber(elementId, finalNumber) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    element.innerText = finalNumber + ' Buku';
}

function showLoading() {
    // Tambahkan loading state jika diperlukan
}

function showError() {
    // Tampilkan pesan error
    console.error('Error loading data');
}

function updateRiwayatTable(riwayat) {
    const tbody = document.getElementById('riwayatTableBody');
    if (!tbody) return;
    
    // Clear existing rows
    tbody.innerHTML = '';
    
    // Add new rows
    riwayat.forEach((item, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td class="font-weight-500">${item.judul_buku || 'Unknown'}</td>
            <td>${formatDate(item.tanggal_pinjam)}</td>
            <td>${item.tanggal_kembali ? formatDate(item.tanggal_kembali) : '-'}</td>
            <td>
                ${getStatusBadge(item.status, item.terlambat)}
            </td>
            <td class="${item.denda > 0 ? 'text-danger font-weight-bold' : 'text-success'}">
                ${item.denda ? formatRupiah(item.denda) : 'Rp 0'}
            </td>
        `;
        tbody.appendChild(row);
    });
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    }).split('/').join('-');
}

function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

function getStatusBadge(status, terlambat = false) {
    if (status === 'dipinjam' || status === 'Dipinjam') {
        if (terlambat) {
            return '<span class="badge badge-danger px-3 py-2"><i class="fas fa-exclamation mr-1"></i>Terlambat</span>';
        }
        return '<span class="badge badge-warning px-3 py-2"><i class="fas fa-book-open mr-1"></i>Dipinjam</span>';
    } else if (status === 'dikembalikan' || status === 'Dikembalikan') {
        return '<span class="badge badge-success px-3 py-2"><i class="fas fa-check mr-1"></i>Dikembalikan</span>';
    }
    return '<span class="badge badge-secondary px-3 py-2">' + status + '</span>';
}

// Load data when page loads
document.addEventListener('DOMContentLoaded', loadDashboardStats);
</script>

<style>
/* Custom styles for better appearance */
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.table td, .table th {
    vertical-align: middle;
}

.font-weight-500 {
    font-weight: 500;
}

.badge {
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 50px;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.row > .col-xl-3,
.alert,
.card {
    animation: fadeIn 0.5s ease-out;
}
</style>

@endsection