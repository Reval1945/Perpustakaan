@extends('layouts.anggota')

@section('title', 'Dashboard Anggota')

@section('content')

<!-- PAGE HEADING -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 fw-bold text-dark">Dashboard</h1>
        <p class="text-muted mb-0">Selamat datang kembali di perpustakaan digital</p>
    </div>
    <div class="bg-white px-4 py-2 rounded-3 shadow-xs">
        <i class="far fa-calendar-alt text-primary me-2"></i>
        <span class="fw-medium">{{ date('d F Y') }}</span>
    </div>
</div>

<!-- STATISTICS CARDS -->
<div class="row g-4 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card bg-white rounded-4 p-4">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                    <i class="fas fa-book-reader fa-2x text-primary"></i>
                </div>
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Total Pinjam</span>
                    <h3 class="fw-bold mb-0" id="totalBuku">0</h3>
                </div>
            </div>
            <div class="stat-footer">
                <span class="text-success small">
                    <i class="fas fa-arrow-up me-1"></i>+12%
                </span>
                <span class="text-muted small ms-2">vs bulan lalu</span>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card bg-white rounded-4 p-4">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Dipinjam</span>
                    <h3 class="fw-bold mb-0" id="belumKembali">0</h3>
                </div>
            </div>
            <div class="stat-footer">
                <span class="text-warning small">
                    <i class="fas fa-hourglass-half me-1"></i>Menunggu
                </span>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card bg-white rounded-4 p-4">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3 me-3">
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                </div>
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Kembali</span>
                    <h3 class="fw-bold mb-0" id="sudahKembali">0</h3>
                </div>
            </div>
            <div class="stat-footer">
                <span class="text-success small">
                    <i class="fas fa-check-circle me-1"></i>Selesai
                </span>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card bg-white rounded-4 p-4">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon bg-danger bg-opacity-10 rounded-3 p-3 me-3">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                </div>
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Terlambat</span>
                    <h3 class="fw-bold mb-0" id="terlambat">0</h3>
                </div>
            </div>
            <div class="stat-footer">
                <span class="text-danger small">
                    <i class="fas fa-exclamation-circle me-1"></i>Segera dikembalikan
                </span>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card border-0 rounded-4 shadow-xs">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-chart-line text-primary me-2"></i>Aktivitas Peminjaman
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light rounded-3 px-3" type="button" data-bs-toggle="dropdown">
                            6 Bulan <i class="fas fa-chevron-down ms-2 small"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card border-0 rounded-4 shadow-xs h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-chart-pie text-primary me-2"></i>Status Peminjaman
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="chart-pie">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4">
                    <div class="d-flex justify-content-center gap-4">
                        <div class="text-center">
                            <span class="d-block w-3 h-3 bg-success rounded-circle mx-auto mb-2"></span>
                            <span class="small fw-medium">Kembali</span>
                        </div>
                        <div class="text-center">
                            <span class="d-block w-3 h-3 bg-warning rounded-circle mx-auto mb-2"></span>
                            <span class="small fw-medium">Dipinjam</span>
                        </div>
                        <div class="text-center">
                            <span class="d-block w-3 h-3 bg-danger rounded-circle mx-auto mb-2"></span>
                            <span class="small fw-medium">Terlambat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RECENT ACTIVITIES -->
<div class="card border-0 rounded-4 shadow-xs">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0">
                <i class="fas fa-history text-primary me-2"></i>Aktivitas Terkini
            </h6>
            <span class="badge bg-light text-dark rounded-pill px-3 py-2" id="totalRiwayat">0 Peminjaman</span>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 rounded-start">No</th>
                        <th class="border-0">Buku</th>
                        <th class="border-0">Tanggal Pinjam</th>
                        <th class="border-0">Tanggal Kembali</th>
                        <th class="border-0">Status</th>
                        <th class="border-0 rounded-end">Denda</th>
                    </tr>
                </thead>
                <tbody id="riwayatTableBody">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ALERT REMINDER -->
<div class="alert alert-warning border-0 rounded-4 d-none position-fixed bottom-0 end-0 m-4 shadow-lg" id="alertBelumKembali" role="alert" style="z-index: 9999;">
    <div class="d-flex align-items-center">
        <div class="flex-shrink-0 me-3">
            <i class="fas fa-bell fa-2x text-warning"></i>
        </div>
        <div class="flex-grow-1">
            <h6 class="fw-bold mb-1">Pengingat Pengembalian!</h6>
            <p class="mb-0 small">Kamu memiliki <strong id="jumlahBelumKembali">0</strong> buku yang belum dikembalikan.</p>
        </div>
        <button type="button" class="btn-close ms-3" data-bs-dismiss="alert"></button>
    </div>
</div>

<script>
let areaChart;
let pieChart;


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

        // --- TAMBAHKAN BARIS INI ---
        updateCharts(data); 
        // ---------------------------

        // Alert handling
        const alertBelumKembali = document.getElementById('alertBelumKembali');
        if (alertBelumKembali) {
            if (data.belum_kembali > 0) {
                alertBelumKembali.style.display = 'block';
                document.getElementById('jumlahBelumKembali').innerText = data.belum_kembali;
            } else {
                alertBelumKembali.style.display = 'none';
            }
        }

        // Update total riwayat & tabel
        if (document.getElementById('totalRiwayat')) {
            document.getElementById('totalRiwayat').innerText = (data.total_riwayat || 0) + ' Peminjaman';
        }

        if (data.riwayat && data.riwayat.length > 0) {
            updateRiwayatTable(data.riwayat);
        }

    } catch (error) {
        console.error('Gagal memuat data dashboard:', error);
        showError();
    }
}

function updateCharts(data) {
    // 1. Logic Area Chart (Line Chart)
    const ctxArea = document.getElementById("myAreaChart");
    
    // Pastikan data riwayat bulanan tersedia dari API, jika tidak gunakan dummy/default
    const labels = data.chart_labels || ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun"];
    const chartData = data.chart_data || [0, 0, 0, 0, 0, 0];

    if (areaChart) areaChart.destroy();
    areaChart = new Chart(ctxArea, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: "Jumlah Pinjam",
                lineTension: 0.4,
                backgroundColor: "rgba(102, 126, 234, 0.1)",
                borderColor: "#667eea",
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: "#667eea",
                pointBorderColor: "#fff",
                pointBorderWidth: 2,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: "#667eea",
                pointHoverBorderColor: "#fff",
                pointHoverBorderWidth: 2,
                data: chartData,
                fill: true,
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: {
                        drawBorder: false,
                        color: "rgba(0,0,0,0.05)"
                    },
                    ticks: { 
                        stepSize: 1,
                        font: { size: 11 }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });

    // 2. Logic Pie Chart
    const ctxPie = document.getElementById("myPieChart");
    if (pieChart) pieChart.destroy();
    pieChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ["Kembali", "Dipinjam", "Terlambat"],
            datasets: [{
                data: [data.sudah_kembali || 0, data.belum_kembali || 0, data.terlambat || 0],
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#17a673', '#dda20a', '#be2617'],
                borderWidth: 0,
            }],
        },
        options: {
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { display: false }
            }
        }
    });
}


function animateNumber(elementId, finalNumber) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    element.innerText = finalNumber;
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
            <td>
                <span class="fw-medium text-secondary">#${String(index + 1).padStart(2, '0')}</span>
            </td>
            <td class="fw-medium">
                <div class="d-flex align-items-center">
                    <i class="fas fa-book text-primary me-2 opacity-50"></i>
                    ${item.judul_buku || 'Unknown'}
                </div>
            </td>
            <td>
                <span class="small">
                    <i class="far fa-calendar-alt text-muted me-1"></i>
                    ${formatDate(item.tanggal_pinjam)}
                </span>
            </td>
            <td>
                <span class="small">
                    <i class="far fa-calendar-check text-muted me-1"></i>
                    ${item.tanggal_kembali ? formatDate(item.tanggal_kembali) : '-'}
                </span>
            </td>
            <td>
                ${getStatusBadge(item.status, item.terlambat)}
            </td>
            <td>
                <span class="fw-medium ${item.denda > 0 ? 'text-danger' : 'text-success'}">
                    ${item.denda ? formatRupiah(item.denda) : 'Rp 0'}
                </span>
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
            return '<span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill"><i class="fas fa-exclamation-circle me-1"></i>Terlambat</span>';
        }
        return '<span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill"><i class="fas fa-book-open me-1"></i>Dipinjam</span>';
    } else if (status === 'dikembalikan' || status === 'Dikembalikan') {
        return '<span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i>Kembali</span>';
    }
    return '<span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">' + status + '</span>';
}

// Load data when page loads
document.addEventListener('DOMContentLoaded', loadDashboardStats);
</script>

<style>
:root {
    --primary: #667eea;
    --success: #1cc88a;
    --warning: #f6c23e;
    --danger: #e74a3b;
}

/* Modern Styles */
body {
    background-color: #f8fafc;
}

/* Welcome Avatar */
.welcome-avatar {
    filter: drop-shadow(0 10px 15px rgba(0,0,0,0.2));
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

/* Stat Cards */
.stat-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0,0,0,0.02);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--warning));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 20px 30px -10px rgba(102, 126, 234, 0.15) !important;
    border-color: transparent;
}

.stat-card:hover::before {
    opacity: 1;
}

/* Stat Icons */
.stat-icon {
    transition: all 0.3s ease;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
}

/* Chart Containers */
.chart-area {
    position: relative;
    height: 15rem;
    width: 100%;
}

@media (min-width: 768px) {
    .chart-area {
        height: 20rem;
    }
}

.chart-pie {
    position: relative;
    height: 15rem;
    width: 100%;
}

/* Table Styles */
.table {
    font-size: 0.9rem;
}

.table thead th {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    font-weight: 600;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.02) !important;
}

/* Badge Styles */
.badge {
    font-weight: 500;
    font-size: 0.75rem;
}

/* Alert Styles */
.alert-warning {
    background-color: #fff3cd;
    border-left: 4px solid #f6c23e;
    max-width: 350px;
}

/* Background Opacity Helpers */
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}

/* Gap Utility */
.gap-4 {
    gap: 1.5rem;
}

/* Shadow */
.shadow-xs {
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
}

/* Rounded */
.rounded-4 {
    border-radius: 1rem !important;
}

/* Width Height Helpers */
.w-3 {
    width: 0.75rem;
}

.h-3 {
    height: 0.75rem;
}

/* Fade In Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.col-6, .col-xl-3, .col-xl-8, .col-xl-4 {
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
}

.col-6:nth-child(1) { animation-delay: 0.1s; }
.col-6:nth-child(2) { animation-delay: 0.2s; }
.col-6:nth-child(3) { animation-delay: 0.3s; }
.col-6:nth-child(4) { animation-delay: 0.4s; }
.col-xl-8 { animation-delay: 0.5s; }
.col-xl-4 { animation-delay: 0.6s; }

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 6px;
}

::-webkit-scrollbar-thumb:hover {
    background: #667eea;
}
</style>

@endsection