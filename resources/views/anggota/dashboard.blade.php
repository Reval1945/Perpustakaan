@extends('layouts.anggota')

@section('title', 'Dashboard Anggota')

@section('content')


<div class="dash-wrapper">

    {{-- ======= HEADER ======= --}}
    <div class="dash-topbar mb-5">
        <div>
            <span class="dash-eyebrow">Perpustakaan &middot; Anggota</span>
            <h1 class="dash-headline">Dashboard</h1>
        </div>
        <div class="meta-chip d-none d-sm-flex">
            <span class="meta-dot pulse-dot"></span>
            <span class="meta-text">{{ date('d M Y') }}</span>
        </div>
    </div>

    {{-- ======= KPI CARDS ======= --}}
    <div class="row mb-4" style="row-gap:1.25rem;">

        <div class="col-xl-3 col-md-6 mb-0">
            <div class="kpi-card kpi-blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Pinjam</span>
                    <div class="kpi-icon"><i class="fas fa-book-reader"></i></div>
                </div>
                <div id="totalBuku" class="kpi-value">0</div>
                <div class="kpi-footer">
                    <span class="kpi-tag">
                        <i class="fas fa-circle kpi-dot"></i> Semua Waktu
                    </span>
                </div>
                <div class="kpi-glow"></div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-0">
            <div class="kpi-card kpi-amber">
                <div class="kpi-top">
                    <span class="kpi-label">Sedang Dipinjam</span>
                    <div class="kpi-icon"><i class="fas fa-clock"></i></div>
                </div>
                <div id="belumKembali" class="kpi-value">0</div>
                <div class="kpi-footer">
                    <span class="kpi-tag">
                        <i class="fas fa-circle kpi-dot"></i> Belum Kembali
                    </span>
                </div>
                <div class="kpi-glow"></div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-0">
            <div class="kpi-card kpi-green">
                <div class="kpi-top">
                    <span class="kpi-label">Selesai</span>
                    <div class="kpi-icon"><i class="fas fa-check-double"></i></div>
                </div>
                <div id="sudahKembali" class="kpi-value">0</div>
                <div class="kpi-footer">
                    <span class="kpi-tag">
                        <i class="fas fa-circle kpi-dot"></i> Sudah Kembali
                    </span>
                </div>
                <div class="kpi-glow"></div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-0">
            <div class="kpi-card kpi-red">
                <div class="kpi-top">
                    <span class="kpi-label">Terlambat</span>
                    <div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
                <div id="terlambat" class="kpi-value">0</div>
                <div class="kpi-footer">
                    <span class="kpi-tag kpi-tag-danger">
                        <i class="fas fa-circle kpi-dot"></i> Perlu Dikembalikan
                    </span>
                </div>
                <div class="kpi-glow"></div>
            </div>
        </div>

    </div>

    {{-- ======= CHART ROW ======= --}}
    <div class="row" style="row-gap:1.25rem;">

        <div class="col-xl-8 col-lg-7 mb-0">
            <div class="panel">
                <div class="panel-head">
                    <div>
                        <p class="panel-eyebrow">Aktivitas</p>
                        <h2 class="panel-title">Statistik Peminjaman Saya</h2>
                    </div>
                    <span class="panel-badge">6 Bulan</span>
                </div>
                <div class="panel-body">
                    <div class="chart-wrap">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-0">
            <div class="panel h-100">
                <div class="panel-head">
                    <div>
                        <p class="panel-eyebrow">Ringkasan</p>
                        <h2 class="panel-title">Komposisi Status</h2>
                    </div>
                    <span class="panel-badge panel-badge-green">Live</span>
                </div>
                <div class="panel-body d-flex flex-column align-items-center justify-content-center">
                    <div class="donut-wrap">
                        <canvas id="myPieChart"></canvas>
                        <div class="donut-center" id="donutCenter">—</div>
                    </div>
                    <div class="legend-row mt-4">
                        <div class="legend-item">
                            <span class="legend-dot" style="background:#1cc88a;"></span>
                            <span class="legend-label">Kembali</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot" style="background:#f6c23e;"></span>
                            <span class="legend-label">Dipinjam</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot" style="background:#e74a3b;"></span>
                            <span class="legend-label">Terlambat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Toast Notification -->
<div id="alertBelumKembali" class="toast-custom d-none">
    <div class="toast-inner">
        <div class="toast-icon">
            <i class="fas fa-bell"></i>
        </div>
        <div class="toast-content">
            <p class="toast-title">Ingat Pengembalian!</p>
            <p class="toast-sub">Ada <span id="jumlahBelumKembali">0</span> buku menunggu Anda.</p>
        </div>
        <button class="toast-close" onclick="this.closest('.toast-custom').classList.add('d-none')">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="toast-progress"></div>
</div>


<style>
*, *::before, *::after { box-sizing: border-box; }

body, .card, .card-body, h1,h2,h3,h4,h5,h6,
p,span,div,a,li,td,th,button,input,select,textarea {
    font-family: 'Sora', sans-serif !important;
}

:root {
    --success:#1cc88a; --info:#36b9cc; --warning:#f6c23e; --danger:#e74a3b;
    --blue:#4e73df; --green:#1cc88a; --amber:#f6c23e; --red:#e74a3b;
    --surface:#ffffff; --surface2:#f7f8fc; --border:#eef0f6;
    --text-1:#1a1d2e; --text-2:#6b7280; --text-3:#b0b5c8;
    --r-xl:20px; --r-lg:14px; --r-md:10px;
}

.dash-wrapper { padding: 6px 2px 40px; }

/* --- Header --- */
.dash-topbar {
    display: flex; align-items: flex-end;
    justify-content: space-between; flex-wrap: wrap; gap: 12px;
}
.dash-eyebrow {
    display: block; font-size: .67rem; font-weight: 700;
    letter-spacing: 2px; text-transform: uppercase;
    color: var(--text-3); margin-bottom: 5px;
}
.dash-headline {
    font-size: 2rem; font-weight: 800; color: var(--text-1);
    letter-spacing: -.8px; margin: 0; line-height: 1;
}
.meta-chip {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 50px; padding: 8px 18px;
}
.meta-dot {
    width: 8px; height: 8px; border-radius: 50%; background: var(--green);
}
.pulse-dot {
    box-shadow: 0 0 0 0 rgba(28,200,138,.4);
    animation: kpulse 2s infinite;
}
@keyframes kpulse {
    0%  { box-shadow: 0 0 0 0   rgba(28,200,138,.4); }
    70% { box-shadow: 0 0 0 8px rgba(28,200,138,0);  }
    100%{ box-shadow: 0 0 0 0   rgba(28,200,138,0);  }
}
.meta-text { font-size: .77rem; font-weight: 600; color: var(--text-2); }

/* --- KPI Cards --- */
.kpi-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--r-xl); padding: 24px 26px 20px;
    position: relative; overflow: hidden;
    transition: transform .3s ease, box-shadow .3s ease;
    height: 100%;
}
.kpi-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,.07); }

.kpi-top {
    display: flex; align-items: flex-start;
    justify-content: space-between; margin-bottom: 12px;
}
.kpi-label {
    font-size: .68rem; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: var(--text-3);
}
.kpi-icon {
    width: 40px; height: 40px; border-radius: var(--r-md);
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem; flex-shrink: 0;
    transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
}
.kpi-card:hover .kpi-icon {
    transform: scale(1.18) rotate(-6deg);
    filter: brightness(1.15);
    box-shadow: 0 6px 18px rgba(0,0,0,.13);
}
.kpi-value {
    font-family: 'JetBrains Mono', monospace !important;
    font-size: 2.4rem; font-weight: 700; color: var(--text-1);
    letter-spacing: -2px; line-height: 1;
    margin-bottom: 14px; min-height: 40px;
}
.kpi-footer { display: flex; align-items: center; }
.kpi-tag {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .69rem; font-weight: 600;
    padding: 4px 10px; border-radius: 50px;
    background: var(--surface2); color: var(--text-2);
}
.kpi-dot { font-size: .35rem; }
.kpi-tag-danger { background: rgba(231,74,59,.08) !important; color: var(--danger) !important; }

.kpi-blue  { border-top: 3px solid var(--blue);  }
.kpi-blue  .kpi-icon { background: rgba(78,115,223,.1);  color: var(--blue);  }
.kpi-blue  .kpi-dot  { color: var(--blue);  }

.kpi-amber { border-top: 3px solid var(--amber); }
.kpi-amber .kpi-icon { background: rgba(246,194,62,.1); color: var(--amber); }
.kpi-amber .kpi-dot  { color: var(--amber); }

.kpi-green { border-top: 3px solid var(--green); }
.kpi-green .kpi-icon { background: rgba(28,200,138,.1); color: var(--green); }
.kpi-green .kpi-dot  { color: var(--green); }

.kpi-red   { border-top: 3px solid var(--red);   }
.kpi-red   .kpi-icon { background: rgba(231,74,59,.1);  color: var(--red);   }
.kpi-red   .kpi-dot  { color: var(--red);   }

.kpi-glow {
    position: absolute; width: 120px; height: 120px; border-radius: 50%;
    right: -30px; bottom: -40px; opacity: .055; pointer-events: none;
    transition: opacity .3s ease, transform .3s ease;
}
.kpi-card:hover .kpi-glow {
    opacity: .13;
    transform: scale(1.25);
}
.kpi-blue  .kpi-glow { background: var(--blue);  }
.kpi-amber .kpi-glow { background: var(--amber); }
.kpi-green .kpi-glow { background: var(--green); }
.kpi-red   .kpi-glow { background: var(--red);   }

/* --- Panels --- */
.panel {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--r-xl); overflow: hidden;
}
.panel-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 24px 16px; border-bottom: 1px solid var(--border);
}
.panel-eyebrow {
    font-size: .64rem; font-weight: 700; letter-spacing: 2px;
    text-transform: uppercase; color: var(--text-3); margin: 0 0 2px;
}
.panel-title {
    font-size: .97rem; font-weight: 700; color: var(--text-1);
    margin: 0; letter-spacing: -.3px;
}
.panel-badge {
    font-size: .64rem; font-weight: 700; letter-spacing: .8px;
    text-transform: uppercase; padding: 5px 13px; border-radius: 50px;
    background: rgba(78,115,223,.08); color: var(--blue);
    border: 1px solid rgba(78,115,223,.14);
}
.panel-badge-green {
    background: rgba(28,200,138,.08); color: var(--green);
    border-color: rgba(28,200,138,.15);
}
.panel-body { padding: 22px 24px; }

/* --- Chart --- */
.chart-wrap { position: relative; height: 300px; }

/* --- Donut chart --- */
.donut-wrap {
    position: relative; width: 200px; height: 200px;
    display: flex; align-items: center; justify-content: center;
}
.donut-wrap canvas { position: absolute; top: 0; left: 0; }
.donut-center {
    position: relative; z-index: 2;
    font-family: 'JetBrains Mono', monospace !important;
    font-size: 1.8rem; font-weight: 700; color: var(--text-1);
    letter-spacing: -1px;
    pointer-events: none;
}

/* --- Legend --- */
.legend-row { display: flex; gap: 18px; flex-wrap: wrap; justify-content: center; }
.legend-item { display: flex; align-items: center; gap: 6px; }
.legend-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.legend-label { font-size: .72rem; font-weight: 600; color: var(--text-2); }

/* --- Toast --- */
.toast-custom {
    position: fixed; bottom: 28px; right: 28px;
    background: #fff; border-radius: 16px;
    box-shadow: 0 16px 40px rgba(0,0,0,.12);
    z-index: 1060; width: 310px; overflow: hidden;
    border-top: 3px solid var(--amber);
    animation: slideIn .45s cubic-bezier(.22,1,.36,1);
}
.toast-inner {
    display: flex; align-items: center; gap: 14px; padding: 16px 18px 14px;
}
.toast-icon {
    width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
    background: rgba(246,194,62,.12); color: var(--amber);
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem;
}
.toast-content { flex: 1; }
.toast-title { font-size: .83rem; font-weight: 700; color: var(--text-1); margin: 0 0 2px; }
.toast-sub   { font-size: .72rem; color: var(--text-3); margin: 0; }
.toast-close {
    background: none; border: none; cursor: pointer;
    color: var(--text-3); font-size: .8rem; padding: 4px;
    transition: color .2s;
}
.toast-close:hover { color: var(--text-1); }
.toast-progress {
    height: 3px;
    background: linear-gradient(90deg, var(--amber), rgba(246,194,62,.3));
    animation: toastBar 5s linear forwards;
}
@keyframes toastBar { from { width: 100%; } to { width: 0%; } }
@keyframes slideIn {
    from { transform: translateX(110%); opacity: 0; }
    to   { transform: translateX(0);    opacity: 1; }
}

/* --- Animations --- */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.dash-topbar { animation: fadeUp .4s ease both; }
.col-xl-3:nth-child(1) .kpi-card,
.col-md-6:nth-child(1) .kpi-card { animation: fadeUp .4s .08s ease both; }
.col-xl-3:nth-child(2) .kpi-card,
.col-md-6:nth-child(2) .kpi-card { animation: fadeUp .4s .16s ease both; }
.col-xl-3:nth-child(3) .kpi-card,
.col-md-6:nth-child(3) .kpi-card { animation: fadeUp .4s .24s ease both; }
.col-xl-3:nth-child(4) .kpi-card,
.col-md-6:nth-child(4) .kpi-card { animation: fadeUp .4s .32s ease both; }
.panel { animation: fadeUp .4s .36s ease both; }

/* --- Compat --- */
.bg-primary-soft { background-color: rgba(78,115,223,.1) !important; }
.bg-success-soft { background-color: rgba(28,200,138,.1) !important; }
.bg-warning-soft { background-color: rgba(246,194,62,.1) !important; }
.bg-danger-soft  { background-color: rgba(231,74,59,.1)  !important; }
.stat-card-primary { border-left: 4px solid var(--primary) !important; }
.stat-card-success { border-left: 4px solid var(--success) !important; }
.stat-card-warning { border-left: 4px solid var(--warning) !important; }
.stat-card-danger  { border-left: 4px solid var(--danger)  !important; }
.text-overline {
    text-transform: uppercase; letter-spacing: 1px;
    font-weight: 700; font-size: .65rem; margin-bottom: 5px;
}
.x-small { font-size: .7rem; }
</style>

{{-- ============================================================
     SCRIPTS
============================================================ --}}
<script>
let areaChart;
let pieChart;

document.addEventListener('DOMContentLoaded', function () {
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

        // Update numbers
        document.getElementById('totalBuku').innerText     = data.total || 0;
        document.getElementById('belumKembali').innerText  = data.belum_kembali || 0;
        document.getElementById('sudahKembali').innerText  = data.sudah_kembali || 0;
        document.getElementById('terlambat').innerText     = data.terlambat || 0;

        // Donut center total
        document.getElementById('donutCenter').innerText = data.total || 0;

        // Alert
        const alertBox = document.getElementById('alertBelumKembali');
        if (data.belum_kembali > 0) {
            alertBox.classList.remove('d-none');
            document.getElementById('jumlahBelumKembali').innerText = data.belum_kembali;
            // Auto-dismiss after progress bar
            setTimeout(() => alertBox.classList.add('d-none'), 5200);
        }

        updateCharts(data);

    } catch (error) {
        console.error('Error:', error);
    }
}

function updateCharts(data) {
    // --- Area Chart ---
    const ctxArea = document.getElementById('myAreaChart').getContext('2d');
    if (areaChart) areaChart.destroy();
    areaChart = new Chart(ctxArea, {
        type: 'line',
        data: {
            labels: data.chart_labels || ['Jan','Feb','Mar','Apr','Mei','Jun'],
            datasets: [{
                label: 'Pinjaman',
                lineTension: 0.45,
                fill: true,
                backgroundColor: function(c) {
                    const g = c.chart.ctx.createLinearGradient(0, 0, 0, 300);
                    g.addColorStop(0, 'rgba(78,115,223,0.14)');
                    g.addColorStop(1, 'rgba(78,115,223,0)');
                    return g;
                },
                borderColor: 'rgba(78,115,223,1)',
                borderWidth: 2.5,
                pointRadius: 5,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: 'rgba(78,115,223,1)',
                pointBorderWidth: 2.5,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: 'rgba(78,115,223,1)',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2,
                pointHitRadius: 14,
                data: data.chart_data || [0,0,0,0,0,0],
            }],
        },
        options: {
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1, color: '#b0b5c8',
                        font: { family: 'Sora', size: 11, weight: '600' }, padding: 8
                    },
                    grid: { color: '#f1f3f9', drawBorder: false },
                    border: { display: false }
                },
                x: {
                    ticks: {
                        color: '#b0b5c8',
                        font: { family: 'Sora', size: 11, weight: '600' }, padding: 6
                    },
                    grid: { display: false },
                    border: { display: false }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a1d2e',
                    titleColor: '#fff',
                    bodyColor: 'rgba(255,255,255,0.6)',
                    borderColor: 'rgba(255,255,255,0.06)',
                    borderWidth: 1,
                    padding: { x: 16, y: 12 },
                    cornerRadius: 12,
                    titleFont: { family: 'Sora', weight: '700', size: 12 },
                    bodyFont: { family: 'JetBrains Mono', size: 12 },
                    displayColors: false,
                    callbacks: { label: c => `  ${c.parsed.y} peminjaman` }
                }
            }
        }
    });

    // --- Donut Chart ---
    const ctxPie = document.getElementById('myPieChart').getContext('2d');
    if (pieChart) pieChart.destroy();
    pieChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Kembali', 'Dipinjam', 'Terlambat'],
            datasets: [{
                data: [data.sudah_kembali || 0, data.belum_kembali || 0, data.terlambat || 0],
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderColor: '#ffffff',
                hoverOffset: 6,
            }],
        },
        options: {
            maintainAspectRatio: false,
            cutout: '78%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a1d2e',
                    titleColor: '#fff',
                    bodyColor: 'rgba(255,255,255,0.6)',
                    padding: { x: 14, y: 10 },
                    cornerRadius: 10,
                    titleFont: { family: 'Sora', weight: '700', size: 12 },
                    bodyFont: { family: 'JetBrains Mono', size: 12 },
                    displayColors: false,
                    callbacks: { label: c => `  ${c.parsed} buku` }
                }
            }
        }
    });
}
</script>

@endsection