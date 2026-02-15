@extends('layouts.superadmin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Super Admin</h1>
        <a href="/superadmin/admin" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-users-cog fa-sm text-white-50"></i> Kelola Admin
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Admin Count Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Admin
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="adminCount">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Row -->
    <div class="row">
        <!-- Info Perpustakaan -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Perpustakaan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-gray-600 mb-1">Nama Perpustakaan</label>
                                <p class="font-weight-bold mb-0">Perpustakaan Digital SMK</p>
                            </div>
                            <div class="mb-3">
                                <label class="small text-gray-600 mb-1">Alamat</label>
                                <p class="mb-0">Jl. Pendidikan No. 12, Kota Bandung</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-gray-600 mb-1">Tahun Berdiri</label>
                                <p class="font-weight-bold mb-0">2022</p>
                            </div>
                            <div class="mb-3">
                                <label class="small text-gray-600 mb-1">Status</label>
                                <span class="badge badge-success">Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jam Operasional -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-clock mr-2"></i>Jam Operasional
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold">Senin - Jumat</td>
                                <td>:</td>
                                <td>08.00 – 15.00 WIB</td>
                                <td><span class="badge badge-primary">Buka</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Sabtu</td>
                                <td>:</td>
                                <td>08.00 – 12.00 WIB</td>
                                <td><span class="badge badge-primary">Buka</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Minggu</td>
                                <td>:</td>
                                <td>Libur</td>
                                <td><span class="badge badge-secondary">Tutup</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="text-center mt-3">
                        <p class="small text-gray-600 mb-0">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            Perpustakaan tutup pada hari libur nasional
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user-tie mr-2"></i>Daftar Admin Terbaru
            </h6>
            <a href="/superadmin/admin" class="btn btn-sm btn-primary">
                <i class="fas fa-eye mr-2"></i>Lihat Semua
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="adminTable" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Admin</th>
                            <th>Email</th>
                            <th width="120">Role</th>
                        </tr>
                    </thead>
                    <tbody id="adminTableBody">
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted">Memuat data admin...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let semuaAdmin = [];

// Load admin dari API
async function loadAdminDashboard() {
    const tbody = document.getElementById('adminTableBody');
    const adminCountElem = document.getElementById('adminCount');
    
    try {
        const token = localStorage.getItem('token');
        if (!token) throw new Error('Token tidak ditemukan');

        const res = await fetch('/api/admins', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center">Gagal memuat data admin</td></tr>`;
            return;
        }

        const result = await res.json();
        semuaAdmin = result.data || [];

        // Filter case-insensitive
        const admins = semuaAdmin.filter(a => ['admin','superadmin'].includes(a.role.toLowerCase()));

        // Hitung jumlah
        const adminCount = admins.filter(a => a.role.toLowerCase() === 'admin').length;
    
        adminCountElem.textContent = adminCount;

        // Render tabel 5 terbaru
        const latestAdmins = admins.slice(0, 5);

        if (latestAdmins.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center">Belum ada data admin</td></tr>`;
            return;
        }

        tbody.innerHTML = latestAdmins.map((admin,index) => {
            const roleBadge = admin.role.toLowerCase() === 'superadmin'
                ? '<span class="badge badge-danger">Super Admin</span>'
                : '<span class="badge badge-primary">Admin</span>';

            return `<tr>
                <td class="text-center">${index+1}</td>
                <td class="font-weight-bold">${admin.name}</td>
                <td class="text-truncate" style="max-width:250px;">${admin.email}</td>
                <td class="text-center">${roleBadge}</td>
            </tr>`;
        }).join('');

    } catch(err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="5" class="text-center">Terjadi kesalahan: ${err.message}</td></tr>`;
    }
}

// Load saat halaman siap
$(document).ready(function() {
    loadAdminDashboard();
    
    // Auto refresh setiap 30 detik
    setInterval(loadAdminDashboard, 30000);
});
</script>

<style>
.card {
    transition: transform 0.3s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.badge {
    font-size: 0.75em;
    padding: 0.35em 0.65em;
}

.table th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
}

.border-left-primary {
    border-left: 4px solid #4e73df !important;
}

.border-left-success {
    border-left: 4px solid #1cc88a !important;
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}
</style>
@endsection