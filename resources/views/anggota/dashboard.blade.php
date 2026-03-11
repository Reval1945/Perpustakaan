@extends('layouts.anggota')

@section('title', 'Dashboard Anggota')

@section('content')
<div class="container-fluid animate-up">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0" style="color: var(--dark); font-weight: 700;">Dashboard</h1>
        </div>
        <div class="d-none d-sm-inline-block">
            <div class="bg-white px-3 py-2 rounded-pill shadow-sm border-0 small">
                <i class="far fa-calendar-alt text-primary mr-2"></i>
                <span class="font-weight-bold text-gray-800">{{ date('d M Y') }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card modern-card stat-card-primary shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-overline text-primary">Total Pinjam</div>
                            <div id="totalBuku" class="h2 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="icon-circle bg-primary-soft">
                            <i class="fas fa-book-reader text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card modern-card stat-card-warning shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-overline text-warning">Sedang Dipinjam</div>
                            <div id="belumKembali" class="h2 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="icon-circle bg-warning-soft">
                            <i class="fas fa-clock text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card modern-card stat-card-success shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-overline text-success">Selesai</div>
                            <div id="sudahKembali" class="h2 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="icon-circle bg-success-soft">
                            <i class="fas fa-check-double text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card modern-card stat-card-danger shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-overline text-danger">Terlambat</div>
                            <div id="terlambat" class="h2 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="icon-circle bg-danger-soft">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm modern-card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Peminjaman Saya</h6>
                    <button class="btn btn-sm btn-light border rounded-pill px-3 x-small">Filter 6 Bulan</button>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 320px;">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm modern-card mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Komposisi Status</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2" style="height: 250px;">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Kembali</span>
                        <span class="mr-2"><i class="fas fa-circle text-warning"></i> Pinjam</span>
                        <span class="mr-2"><i class="fas fa-circle text-danger"></i> Telat</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="alertBelumKembali" class="toast-custom d-none" style="border-left: 6px solid var(--warning);">
    <div class="d-flex align-items-center">
        <div class="icon-circle bg-warning-soft mr-3" style="width: 40px; height: 40px;">
            <i class="fas fa-bell text-warning fa-sm"></i>
        </div>
        <div>
            <p class="mb-0 font-weight-bold text-gray-800">Ingat Pengembalian!</p>
            <p class="mb-0 x-small text-muted">Ada <span id="jumlahBelumKembali">0</span> buku menunggu Anda.</p>
        </div>
    </div>
</div>

<style>
    /* Mengadopsi CSS Modern Style dari Admin */
    :root {
        --success: #1cc88a;
        --warning: #f6c23e;
        --danger: #e74a3b;
    }

    .modern-card {
        border-radius: 15px !important;
        transition: all 0.3s ease;
        border: none;
        width: 100%;
    }
    
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }

    .text-overline {
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        font-size: 0.65rem;
        margin-bottom: 5px;
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .bg-primary-soft { background-color: rgba(78, 115, 223, 0.1) !important; }
    .bg-success-soft { background-color: rgba(28, 200, 138, 0.1) !important; }
    .bg-warning-soft { background-color: rgba(246, 194, 62, 0.1) !important; }
    .bg-danger-soft { background-color: rgba(231, 74, 59, 0.1) !important; }

    .stat-card-primary { border-left: 4px solid var(--primary) !important; }
    .stat-card-success { border-left: 4px solid var(--success) !important; }
    .stat-card-warning { border-left: 4px solid var(--warning) !important; }
    .stat-card-danger { border-left: 4px solid var(--danger) !important; }

    .x-small { font-size: 0.7rem; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-up { animation: fadeInUp 0.5s ease-out forwards; }

    .toast-custom {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #ffffff;
        padding: 15px 25px;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        z-index: 1060;
        animation: slideIn 0.5s ease;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>

<script>
let areaChart;
let pieChart;

document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
});

async function loadDashboardStats() {
    try {
        const token = localStorage.getItem('token');
        const response = await fetch('/api/dashboard/stats', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error('Gagal mengambil data');
        const data = await response.json();

        // Update Numbers
        document.getElementById('totalBuku').innerText = data.total || 0;
        document.getElementById('belumKembali').innerText = data.belum_kembali || 0;
        document.getElementById('sudahKembali').innerText = data.sudah_kembali || 0;
        document.getElementById('terlambat').innerText = data.terlambat || 0;

        // Alert handling
        const alertBox = document.getElementById('alertBelumKembali');
        if (data.belum_kembali > 0) {
            alertBox.classList.remove('d-none');
            document.getElementById('jumlahBelumKembali').innerText = data.belum_kembali;
        }

        updateCharts(data);

    } catch (error) {
        console.error('Error:', error);
    }
}

function updateCharts(data) {
    // Area Chart
    const ctxArea = document.getElementById("myAreaChart").getContext('2d');
    if (areaChart) areaChart.destroy();
    areaChart = new Chart(ctxArea, {
        type: 'line',
        data: {
            labels: data.chart_labels || ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun"],
            datasets: [{
                label: "Pinjaman",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                data: data.chart_data || [0, 0, 0, 0, 0, 0],
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Pie Chart
    const ctxPie = document.getElementById("myPieChart").getContext('2d');
    if (pieChart) pieChart.destroy();
    pieChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ["Kembali", "Dipinjam", "Terlambat"],
            datasets: [{
                data: [data.sudah_kembali || 0, data.belum_kembali || 0, data.terlambat || 0],
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            cutout: '80%',
            plugins: { legend: { display: false } }
        }
    });
}
</script>
@endsection