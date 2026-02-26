@extends('layouts.anggota')

@section('title', 'Dashboard Anggota')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-5 mt-2">
    <div>
        <h1 class="h3 mb-1 fw-800 text-dark">Ringkasan Aktivitas</h1>
        <p class="text-muted mb-0">Halo, selamat datang kembali di sistem perpustakaan.</p>
    </div>
    <div class="d-none d-md-block">
        <div class="date-display bg-white px-4 py-2 rounded-pill shadow-sm border">
            <i class="far fa-calendar-alt text-primary me-2"></i>
            <span class="fw-bold text-dark">{{ date('d M Y') }}</span>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-6 col-xl-3">
        <div class="stat-card card-primary">
            <div class="stat-body">
                <div class="stat-icon">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Pinjam</span>
                    <h2 class="stat-number" id="totalBuku">0</h2>
                </div>
            </div>
            <div class="stat-progress">
                <div class="progress-bar" style="width: 70%"></div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card card-warning">
            <div class="stat-body">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Dipinjam</span>
                    <h2 class="stat-number" id="belumKembali">0</h2>
                </div>
            </div>
            <div class="stat-status text-warning">
                <i class="fas fa-circle-notch fa-spin me-1"></i> Aktif
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card card-success">
            <div class="stat-body">
                <div class="stat-icon">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Selesai</span>
                    <h2 class="stat-number" id="sudahKembali">0</h2>
                </div>
            </div>
            <div class="stat-status text-success">
                <i class="fas fa-check-circle me-1"></i> Aman
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card card-danger">
            <div class="stat-body">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Terlambat</span>
                    <h2 class="stat-number" id="terlambat">0</h2>
                </div>
            </div>
            <div class="stat-status text-danger">
                <i class="fas fa-info-circle me-1"></i> Perlu Tindakan
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card main-card">
            <div class="card-header-custom">
                <h6 class="m-0 fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i>Tren Peminjaman</h6>
                <button class="btn btn-sm btn-light border rounded-pill px-3">Filter 6 Bulan</button>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card main-card">
            <div class="card-header-custom">
                <h6 class="m-0 fw-bold"><i class="fas fa-chart-pie me-2 text-primary"></i>Komposisi Status</h6>
            </div>
            <div class="card-body">
                <div class="chart-container-pie">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="legend-custom mt-4">
                    <div class="legend-item"><span class="dot bg-success"></span> Kembali</div>
                    <div class="legend-item"><span class="dot bg-warning"></span> Dipinjam</div>
                    <div class="legend-item"><span class="dot bg-danger"></span> Telat</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="alertBelumKembali" class="toast-custom border-warning d-none">
    <div class="d-flex align-items-center">
        <div class="toast-icon bg-warning">
            <i class="fas fa-bell text-white"></i>
        </div>
        <div class="ms-3">
            <p class="mb-0 fw-bold text-dark">Ingat Pengembalian!</p>
            <p class="mb-0 small text-muted">Ada <span id="jumlahBelumKembali">0</span> buku menunggu Anda.</p>
        </div>
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

// Load data when page loads
document.addEventListener('DOMContentLoaded', loadDashboardStats);
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root {
    --primary: #4e73df;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --dark-blue: #1e293b;
}

body {
    background-color: #f1f5f9;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #334155;
}

.fw-800 { font-weight: 800; }

/* Stat Card Styling */
.stat-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 24px;
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
}

.stat-body { display: flex; align-items: center; }

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-right: 16px;
}

.card-primary .stat-icon { background: rgba(78,115,223,0.1); color: var(--primary); }
.card-warning .stat-icon { background: rgba(245,158,11,0.1); color: var(--warning); }
.card-success .stat-icon { background: rgba(16,185,129,0.1); color: var(--success); }
.card-danger .stat-icon { background: rgba(239,68,68,0.1); color: var(--danger); }

.stat-label { font-size: 0.85rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
.stat-number { font-size: 1.75rem; font-weight: 800; margin-bottom: 0; color: var(--dark-blue); }

.stat-status { font-size: 0.75rem; font-weight: 600; margin-top: 12px; }

/* Table Styling */
.main-card {
    border-radius: 24px;
    border: none;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
}

.card-header-custom {
    padding: 24px;
    background: transparent;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-custom thead th {
    background: #f8fafc;
    border: none;
    padding: 16px 24px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
}

.table-custom tbody td {
    padding: 18px 24px;
    vertical-align: middle;
    border-color: #f1f5f9;
}

/* Badge Styling */
.badge-soft-primary {
    background: rgba(78,115,223,0.1);
    color: var(--primary);
    padding: 8px 16px;
    border-radius: 10px;
    font-weight: 600;
}

/* Chart Container */
.chart-container { height: 350px; }
.chart-container-pie { height: 250px; }

/* Legend */
.legend-custom { display: flex; justify-content: center; gap: 20px; }
.legend-item { font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; }
.dot { width: 10px; height: 10px; border-radius: 50%; margin-right: 8px; }

/* Floating Toast */
.toast-custom {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #ffffff;
    padding: 16px 24px;
    border-radius: 20px;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    z-index: 1060;
    border-left: 6px solid;
    animation: slideIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.row > div {
    animation: fadeInUp 0.5s ease-out forwards;
}
</style>

@endsection