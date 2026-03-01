@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid animate-up">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Dashboard</h1>
        <div class="d-none d-sm-inline-block">
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card modern-card stat-card-primary shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-overline text-primary">Total Anggota</div>
                            <div id="countAnggota" class="h2 mb-0 font-weight-bold text-gray-800">Loading...</div>
                            <div class="text-muted small mt-2">
                                <span class="text-success mr-2"><i class="fas fa-users"></i></span> User Aktif
                            </div>
                        </div>
                        <div class="icon-circle bg-primary-soft">
                            <i class="fas fa-users text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card modern-card stat-card-success shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-overline text-success">Koleksi Buku</div>
                            <div id="countBuku" class="h2 mb-0 font-weight-bold text-gray-800">Loading...</div>
                            <div class="text-muted small mt-2">
                                <span class="text-primary mr-2">Total</span> Judul terdaftar
                            </div>
                        </div>
                        <div class="icon-circle bg-success-soft">
                            <i class="fas fa-book text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card modern-card stat-card-warning shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-overline text-warning">Peminjaman Aktif</div>
                            <div id="countTransaksi" class="h2 mb-0 font-weight-bold text-gray-800">Loading...</div>
                            <div class="text-muted small mt-2">
                                <span id="countTerlambat" class="text-danger mr-4"><i class="fas fa-clock"></i> 0 Terlambat</span>
                            </div>
                        </div>
                        <div class="icon-circle bg-warning-soft">
                            <i class="fas fa-exchange-alt text-warning"></i>
                        </div>
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
                <h6 class="m-0 font-weight-bold text-primary">Statistik Peminjaman (7 Hari Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow-sm modern-card mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Buku Baru Terdaftar</h6>
            </div>
            <div class="card-body">
                <div id="bukuTerbaruList" class="list-group list-group-flush">
                    </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS Root & Variables */
    :root {
        --success: #1cc88a;
        --info: #36b9cc;
        --warning: #f6c23e;
        --danger: #e74a3b;
    }

    /* Cards Enhancements */
    .modern-card {
        border-radius: 15px !important;
        transition: all 0.3s ease;
        border: none;
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

    /* Icon circles */
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    /* Avatar & Mini Icons */
    .avatar-info {
        width: 45px;
        height: 45px;
        background: var(--primary);
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .avatar-sm {
        width: 30px;
        height: 30px;
        background: #eaecf4;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: bold;
    }

    .book-icon {
        width: 35px;
        height: 35px;
        background: #f8f9fc;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Soft Colors */
    .bg-primary-soft { background-color: rgba(78, 115, 223, 0.1) !important; }
    .bg-success-soft { background-color: rgba(28, 200, 138, 0.1) !important; }
    .bg-warning-soft { background-color: rgba(246, 194, 62, 0.1) !important; }
    .badge-primary-soft { background: #e8edff; color: var(--primary); }
    .badge-success-soft { background: #dffff3; color: var(--success); }
    .badge-warning-soft { background: #fff9e6; color: var(--warning); }
    .badge-danger-soft { background: #ffebeb; color: var(--danger); }

    /* Info Section */
    .info-item {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        color: #5a5c69;
        margin-bottom: 10px;
    }

    /* Typography */
    .x-small { font-size: 0.7rem; }
    
    /* Animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-up { animation: fadeInUp 0.5s ease-out forwards; }

    /* Border Left Accents */
    .stat-card-primary { border-left: 4px solid var(--primary) !important; }
    .stat-card-success { border-left: 4px solid var(--success) !important; }
    .stat-card-warning { border-left: 4px solid var(--warning) !important; }

    /* Table styling */
    .table thead th {
        background-color: #f8f9fc;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        border: none;
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const token = localStorage.getItem('token'); 

    fetch('/api/dashboard/statsadmin', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}` // Sertakan token Sanctum
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Gagal mengambil data dari server');
        }
        return response.json();
    })
    .then(data => {
        // Mengisi angka ke dalam Card menggunakan id
        document.getElementById('countAnggota').innerText = data.total_anggota;
        document.getElementById('countBuku').innerText = data.total_buku;
        document.getElementById('countTransaksi').innerText = data.peminjaman_aktif;
        
        // Mengisi status terlambat
        document.getElementById('countTerlambat').innerHTML = `<i class="fas fa-clock"></i> ${data.terlambat} Terlambat`;
    })
    .catch(error => {
        console.error('Error fetching dashboard stats:', error);
        document.getElementById('countAnggota').innerText = 'Error';
        document.getElementById('countBuku').innerText = 'Error';
        document.getElementById('countTransaksi').innerText = 'Error';
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const token = localStorage.getItem('token'); 
    const ctx = document.getElementById('myAreaChart').getContext('2d');
    
    // Inisialisasi Chart Kosong
    let myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: "Jumlah Peminjaman",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: [],
            }],
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Fetch Data
    fetch('/api/dashboard/statsadmin', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    })
    .then(response => response.json())
    .then(data => {
        // 1. Update Card Stats
        document.getElementById('countAnggota').innerText = data.total_anggota;
        document.getElementById('countBuku').innerText = data.total_buku;
        document.getElementById('countTransaksi').innerText = data.peminjaman_aktif;
        document.getElementById('countTerlambat').innerHTML = `<i class="fas fa-clock"></i> ${data.terlambat} Terlambat`;

        // 2. Update Chart
        myChart.data.labels = data.chart_labels;
        myChart.data.datasets[0].data = data.chart_data;
        myChart.update();

        // 3. Update List Buku Terbaru
        const bukuContainer = document.getElementById('bukuTerbaruList');
        bukuContainer.innerHTML = '';
        data.buku_terbaru.forEach(buku => {
            bukuContainer.innerHTML += `
                <div class="info-item mb-3">
                    <div class="book-icon mr-3">
                        <i class="fas fa-book fa-sm text-primary"></i>
                    </div>
                    <div class="text-truncate">
                        <span class="font-weight-bold text-gray-800 d-block">${buku.judul}</span>
                        <small class="text-muted">Baru saja ditambahkan</small>
                    </div>
                </div>`;
        });
    })
    .catch(error => console.error('Error:', error));
});
</script>
@endsection
