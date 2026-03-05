@extends('layouts.superadmin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0" style="color: var(--dark); font-weight: 700;">Dashboard Super Admin</h1>
        <a href="/superadmin/admin" class="btn btn-primary shadow-sm">
            <i class="fas fa-users-cog fa-sm mr-2"></i> Kelola Admin
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Admin Count Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-3" style="border: none; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); border-left: 4px solid var(--primary);">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--gray); letter-spacing: 0.5px;">
                                Total Admin
                            </div>
                            <div class="h2 mb-0 font-weight-bold" style="color: var(--dark);" id="adminCount">0</div>
                        </div>
                        <div class="col-auto">
                            <div style="width: 48px; height: 48px; background: var(--primary-soft); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-tie fa-2x" style="color: var(--primary);"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Table -->
    <div class="card shadow mb-4" style="border: none; border-radius: 16px;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center px-4" style="background: white; border-bottom: 1px solid var(--border); border-radius: 16px 16px 0 0;">
            <h6 class="m-0 font-weight-bold" style="color: var(--dark);">
                <i class="fas fa-user-tie mr-2" style="color: var(--primary);"></i>Daftar Admin Terbaru
            </h6>
            <a href="/superadmin/admin" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-eye mr-2"></i>Lihat Semua
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="adminTable" width="100%" cellspacing="0">
                    <thead style="background: var(--gray-light);">
                        <tr>
                            <th width="50" class="px-4 py-3 text-center" style="color: var(--gray); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; border-top: none;">No</th>
                            <th class="px-4 py-3" style="color: var(--gray); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; border-top: none;">Nama Admin</th>
                            <th class="px-4 py-3" style="color: var(--gray); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; border-top: none;">Email</th>
                            <th width="120" class="px-4 py-3 text-center" style="color: var(--gray); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; border-top: none;">Role</th>
                        </tr>
                    </thead>
                    <tbody id="adminTableBody">
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2 mb-0" style="color: var(--gray);">Memuat data admin...</p>
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
            tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4" style="color: var(--gray);">Gagal memuat data admin</td></tr>`;
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
            tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4" style="color: var(--gray);">Belum ada data admin</td></tr>`;
            return;
        }

        let htmlRows = '';
        latestAdmins.forEach((admin, index) => {
            const roleBadge = admin.role.toLowerCase() === 'superadmin'
                ? '<span class="badge" style="background: #fee2e2; color: var(--danger); font-weight: 500; padding: 0.35rem 0.8rem; border-radius: 30px;">Super Admin</span>'
                : '<span class="badge" style="background: var(--primary-soft); color: var(--primary); font-weight: 500; padding: 0.35rem 0.8rem; border-radius: 30px;">Admin</span>';

            htmlRows += `<tr style="border-bottom: 1px solid var(--border);">
                <td class="px-4 py-3 text-center" style="color: var(--gray);">${index + 1}</td>
                <td class="px-4 py-3 font-weight-bold" style="color: var(--dark);">${admin.name}</td>
                <td class="px-4 py-3" style="color: var(--gray);">${admin.email}</td>
                <td class="px-4 py-3 text-center">${roleBadge}</td>
            </tr>`;
        });
        
        tbody.innerHTML = htmlRows;

    } catch(err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4" style="color: var(--danger);">Terjadi kesalahan: ${err.message}</td></tr>`;
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
:root {
    --primary: #2C5AA0;
    --primary-light: #4A7BC8;
    --primary-soft: #e8f0fe;
    --success: #10b981;
    --danger: #ef4444;
    --dark: #1e293b;
    --gray: #64748b;
    --gray-light: #f1f5f9;
    --border: #e2e8f0;
}

/* Container - Full width with less padding */
.container-fluid {
    width: 100%;
    padding-right: 2rem;
    padding-left: 2rem;
    margin-right: auto;
    margin-left: auto;
}

/* Card Styles */
.card {
    transition: transform 0.2s ease-in-out;
    width: 100%;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.03) !important;
}

/* Table Styles */
.table {
    width: 100% !important;
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-top: none;
}

.table td {
    vertical-align: middle;
    border-color: var(--border);
}

.table-hover tbody tr:hover {
    background: var(--gray-light);
    transition: background 0.2s ease;
}

/* Border Left Utilities */
.border-left-primary {
    border-left: 4px solid var(--primary) !important;
}
.border-left-success {
    border-left: 4px solid var(--success) !important;
}
.border-left-info {
    border-left: 4px solid #36b9cc !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

/* Badge Styles */
.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.8rem;
    border-radius: 30px;
    font-weight: 500;
    display: inline-block;
}

/* Spinner Animation */
@keyframes spin {
    to { transform: rotate(360deg); }
}
.spinner-border {
    animation: spin 0.6s linear infinite;
    display: inline-block;
    width: 2rem;
    height: 2rem;
    vertical-align: text-bottom;
    border: 0.25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
}
.spinner-border.text-primary {
    color: var(--primary) !important;
}
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0,0,0,0);
    border: 0;
}

/* Button Styles */
.btn-primary:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(44,90,160,0.3);
}

.btn-sm {
    padding: 0.4rem 1rem;
    font-size: 0.8rem;
}

/* Shadow */
.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,0.05) !important;
}
.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem 0 rgba(58,59,69,0.1) !important;
}

/* Text Utilities */
.font-weight-bold {
    font-weight: 700 !important;
}
.text-uppercase {
    text-transform: uppercase;
}
.text-center {
    text-align: center;
}

/* Spacing Utilities */
.px-4 {
    padding-right: 1.5rem !important;
    padding-left: 1.5rem !important;
}
.py-3 {
    padding-top: 1rem !important;
    padding-bottom: 1rem !important;
}
.mr-2 {
    margin-right: 0.5rem !important;
}
.mb-0 {
    margin-bottom: 0 !important;
}
.mb-4 {
    margin-bottom: 1.5rem !important;
}
.mt-2 {
    margin-top: 0.5rem !important;
}

/* Remove default container padding */
.container-fluid {
    padding-right: 0;
    padding-left: 0;
}

/* Full width content */
.row {
    margin-right: 0;
    margin-left: 0;
}

.col-xl-3, .col-md-6 {
    padding-right: 0.75rem;
    padding-left: 0.75rem;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding-right: 1rem;
        padding-left: 1rem;
    }
    
    .h2 {
        font-size: 1.8rem;
    }
    
    .btn-sm {
        padding: 0.3rem 0.8rem;
    }
}
</style>
@endsection