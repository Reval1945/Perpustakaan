@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<div class="dash-wrapper">

    {{-- ======= TOP HEADER ======= --}}
    <div class="dash-topbar mb-4">
        <div>
            <span class="dash-eyebrow">Perpustakaan &middot; Admin Panel</span>
            <h1 class="dash-headline">Dashboard</h1>
        </div>
        <div class="dash-meta">
            <div class="meta-chip">
                <span class="meta-dot pulse-dot"></span>
                <span id="currentDate" class="meta-text"></span>
            </div>
        </div>
    </div>

    {{-- ======= STAT CARDS ======= --}}
    <div class="row mb-4">

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stat-card h-100 py-3" style="border-left: 4px solid var(--blue);">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="stat-label">Total Anggota</div>
                            <div class="stat-value" id="countAnggota">0</div>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon-wrap" style="background: rgba(78,115,223,.1);">
                                <i class="fas fa-users fa-2x" style="color: var(--blue);"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <i class="fas fa-circle stat-dot" style="color:var(--blue);"></i> User Aktif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stat-card h-100 py-3" style="border-left: 4px solid var(--green);">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="stat-label">Koleksi Buku</div>
                            <div class="stat-value" id="countBuku">0</div>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon-wrap" style="background: rgba(28,200,138,.1);">
                                <i class="fas fa-book-open fa-2x" style="color: var(--green);"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <i class="fas fa-circle stat-dot" style="color:var(--green);"></i> Judul Terdaftar
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="stat-card h-100 py-3" style="border-left: 4px solid var(--amber);">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="stat-label">Peminjaman Aktif</div>
                            <div class="stat-value" id="countTransaksi">0</div>
                        </div>
                        <div class="col-auto">
                            <div class="stat-icon-wrap" style="background: rgba(246,194,62,.1);">
                                <i class="fas fa-exchange-alt fa-2x" style="color: var(--amber);"></i>
                            </div>
                        </div>
                    </div>
                    <div class="stat-footer" id="countTerlambat">
                        <i class="fas fa-clock stat-dot" style="color:var(--danger);"></i>
                        <span style="color:var(--danger);">0 Terlambat</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ======= CHART + BUKU ======= --}}
    <div class="row">

        <div class="col-8 mb-4">
            <div class="panel">
                <div class="panel-head">
                    <div>
                        <p class="panel-eyebrow">Statistik</p>
                        <h2 class="panel-title">Peminjaman 7 Hari Terakhir</h2>
                    </div>
                    <span class="panel-badge">Live</span>
                </div>
                <div class="panel-body">
                    <div class="chart-wrap">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4 mb-4">
            <div class="panel">
                <div class="panel-head">
                    <div>
                        <p class="panel-eyebrow">Koleksi</p>
                        <h2 class="panel-title">Buku Baru</h2>
                    </div>
                    <span class="panel-badge panel-badge-green">Terbaru</span>
                </div>
                <div id="bukuTerbaruList" class="buku-list">
                    @for($i = 0; $i < 5; $i++)
                    <div class="book-row">
                        <div class="sk sk-num"></div>
                        <div class="sk sk-icon"></div>
                        <div class="flex-grow-1">
                            <div class="sk sk-line sk-lg mb-1"></div>
                            <div class="sk sk-line sk-sm"></div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ================================================
     STYLES
================================================ --}}
<style>

/* --- Base ---------------------------------------- */
*, *::before, *::after { box-sizing: border-box; }

body, .card, .card-body, h1,h2,h3,h4,h5,h6,
p,span,div,a,li,td,th,button,input,select,textarea {
    font-family: 'Sora', sans-serif !important;
}

:root {
    --success:#1cc88a; --info:#36b9cc; --warning:#f6c23e; --danger:#e74a3b;
    --blue:#4e73df; --green:#1cc88a; --amber:#f6c23e;
    --surface:#ffffff; --surface2:#f7f8fc; --border:#eef0f6;
    --text-1:#1a1d2e; --text-2:#6b7280; --text-3:#b0b5c8;
    --r-xl:20px; --r-lg:14px; --r-md:10px;
}

.dash-wrapper { padding: 6px 2px 40px; }

/* --- Header -------------------------------------- */
.dash-topbar {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.dash-eyebrow {
    display: block;
    font-size: .67rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--text-3);
    margin-bottom: 5px;
}
.dash-headline {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-1);
    letter-spacing: -.8px;
    margin: 0;
    line-height: 1;
}
.meta-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 50px;
    padding: 8px 18px;
}
.meta-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--green);
    flex-shrink: 0;
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
.meta-text {
    font-size: .77rem;
    font-weight: 600;
    color: var(--text-2);
    white-space: nowrap;
}

/* --- Stat Cards (style superadmin) --------------- */
.stat-card {
    background: var(--surface);
    border: none;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(0,0,0,.04);
    transition: transform .2s ease, box-shadow .2s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,.07);
}
.stat-card .card-body {
    padding: 1.25rem 1.5rem;
}
.stat-label {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--text-2);
    margin-bottom: 6px;
}
.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-1);
    line-height: 1;
    margin-bottom: 10px;
    min-height: 36px;
}
.stat-icon-wrap {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.stat-footer {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .71rem;
    font-weight: 600;
    color: var(--text-2);
}
.stat-dot { font-size: .38rem; }


/* --- Panels -------------------------------------- */
.panel {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r-xl);
    overflow: hidden;
    height: 100%;
}
.panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 20px 14px;
    border-bottom: 1px solid var(--border);
}
.panel-eyebrow {
    font-size: .64rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--text-3);
    margin: 0 0 2px;
}
.panel-title {
    font-size: .95rem;
    font-weight: 700;
    color: var(--text-1);
    margin: 0;
    letter-spacing: -.3px;
}
.panel-badge {
    font-size: .64rem;
    font-weight: 700;
    letter-spacing: .8px;
    text-transform: uppercase;
    padding: 5px 13px;
    border-radius: 50px;
    background: rgba(78,115,223,.08);
    color: var(--blue);
    border: 1px solid rgba(78,115,223,.14);
    white-space: nowrap;
}
.panel-badge-green {
    background: rgba(28,200,138,.08);
    color: var(--green);
    border-color: rgba(28,200,138,.14);
}
.panel-body { padding: 18px 20px; }

/* --- Chart -------------------------------------- */
.chart-wrap { position: relative; height: 250px; }

/* --- Buku List ---------------------------------- */
.buku-list { padding: 0; }
.book-row {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    border-bottom: 1px solid var(--border);
    transition: background .18s;
    cursor: default;
}
.book-row:last-child { border-bottom: none; }
.book-row:hover { background: var(--surface2); }
.book-num {
    font-family: 'JetBrains Mono', monospace !important;
    font-size: .63rem;
    font-weight: 600;
    color: var(--text-3);
    width: 22px;
    flex-shrink: 0;
    margin-right: 12px;
}
.book-icon-wrap {
    width: 34px;
    height: 34px;
    border-radius: var(--r-md);
    background: rgba(78,115,223,.07);
    border: 1px solid rgba(78,115,223,.12);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-right: 12px;
    font-size: .8rem;
    color: var(--blue);
}
.book-title {
    font-size: .82rem;
    font-weight: 700;
    color: var(--text-1);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
    line-height: 1.3;
}
.book-sub {
    font-size: .67rem;
    font-weight: 500;
    color: var(--text-3);
    display: block;
    margin-top: 2px;
}

/* --- Skeleton ----------------------------------- */
@keyframes skshim {
    from { background-position: -600px 0; }
    to   { background-position:  600px 0; }
}
.sk {
    border-radius: 6px;
    background: linear-gradient(90deg,#eef0f7 25%,#f5f6fc 50%,#eef0f7 75%);
    background-size: 600px 100%;
    animation: skshim 1.4s infinite linear;
}
.sk-num  { width: 18px; height: 10px; flex-shrink: 0; margin-right: 12px; }
.sk-icon { width: 34px !important; height: 34px !important; border-radius: 10px; flex-shrink: 0; margin-right: 12px; }
.sk-line { height: 9px; }
.sk-lg   { width: 70%; }
.sk-sm   { width: 42%; }

/* --- Animations --------------------------------- */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.dash-topbar         { animation: fadeUp .4s ease both; }
.row > *:nth-child(1) .stat-card { animation: fadeUp .4s .08s ease both; }
.row > *:nth-child(2) .stat-card { animation: fadeUp .4s .16s ease both; }
.row > *:nth-child(3) .stat-card { animation: fadeUp .4s .24s ease both; }
.panel               { animation: fadeUp .4s .32s ease both; }

/* --- Compat overrides --------------------------- */
.badge-primary-soft { background: rgba(78,115,223,.1); color: var(--blue); }
.badge-success-soft { background: rgba(28,200,138,.1); color: var(--green); }
.badge-warning-soft { background: rgba(246,194,62,.1);  color: var(--amber); }
.badge-danger-soft  { background: rgba(231,74,59,.1);  color: var(--danger); }

.table thead th {
    background:var(--surface2);
    text-transform:uppercase;
    font-size:.68rem;
    letter-spacing:.6px;
    border:none;
    font-weight:700;
    color:var(--text-2);
}

/* ============================================
   RESPONSIVE — TABLET (768px – 991px)
============================================ */
@media (min-width: 768px) and (max-width: 991.98px) {
    .dash-headline { font-size: 1.6rem; }
    .kpi-value { font-size: 1.8rem; letter-spacing: -1px; }
    .kpi-card { padding: 16px 14px 13px; }
    .kpi-icon { width: 36px; height: 36px; font-size: .85rem; }
    .kpi-label { font-size: .62rem; }
    .kpi-tag { font-size: .66rem; padding: 3px 9px; }
    .chart-wrap { height: 210px; }
    .panel-head { padding: 14px 16px 12px; }
    .panel-body { padding: 14px 16px; }
    .book-row { padding: 10px 14px; }
    .book-title { font-size: .76rem; }
    .meta-chip { padding: 6px 13px; }
    .meta-text { font-size: .72rem; }
}

/* ============================================
   RESPONSIVE — MOBILE (< 768px)
   Layout kolom TETAP SAMA seperti desktop.
   Hanya ukuran elemen yang dikecilkan.
============================================ */
@media (max-width: 767.98px) {
    .dash-wrapper { padding: 4px 0 24px; }

    /* Header — tetap flex row tapi lebih compact */
    .dash-topbar { gap: 8px; margin-bottom: 0.75rem !important; }
    .dash-eyebrow { font-size: .58rem; letter-spacing: 1.5px; }
    .dash-headline { font-size: 1.15rem; letter-spacing: -.5px; }
    .meta-chip { padding: 5px 10px; gap: 6px; }
    .meta-text { font-size: .65rem; }
    .meta-dot { width: 6px; height: 6px; }

    /* KPI cards — 3 kolom tetap, konten diperkecil */
    .kpi-card {
        padding: 10px 10px 9px;
        border-radius: 10px;
        border-top-width: 2px;
    }
    .kpi-top { margin-bottom: 6px; }
    .kpi-label { font-size: .54rem; letter-spacing: 1px; }
    .kpi-icon { width: 28px; height: 28px; border-radius: 7px; font-size: .7rem; }
    .kpi-value { font-size: 1.3rem; letter-spacing: -.5px; min-height: 28px; margin-bottom: 7px; }
    .kpi-tag { font-size: .58rem; padding: 3px 7px; gap: 4px; }
    .kpi-skeleton { width: 60px; height: 26px; }

    /* Panels */
    .panel { border-radius: 10px; }
    .panel-head { padding: 10px 12px 9px; flex-wrap: wrap; gap: 6px; }
    .panel-eyebrow { font-size: .56rem; letter-spacing: 1.5px; }
    .panel-title { font-size: .78rem; }
    .panel-badge { font-size: .55rem; padding: 3px 9px; }
    .panel-body { padding: 10px 12px; }

    /* Chart compact */
    .chart-wrap { height: 160px; }

    /* Book list compact */
    .book-row { padding: 8px 12px; }
    .book-num { font-size: .55rem; width: 16px; margin-right: 8px; }
    .book-icon-wrap { width: 26px; height: 26px; border-radius: 7px; font-size: .65rem; margin-right: 8px; }
    .book-title { font-size: .68rem; }
    .book-sub { font-size: .58rem; }
}

/* ============================================
   RESPONSIVE — SMALL MOBILE (< 420px)
============================================ */
@media (max-width: 419.98px) {
    .dash-headline { font-size: 1rem; }
    .kpi-value { font-size: 1.1rem; }
    .kpi-icon { display: none; } /* sembunyikan icon agar angka lebih lebar */
    .chart-wrap { height: 140px; }
    .book-icon-wrap { display: none; }
    .book-num { display: none; }
}
</style>

{{-- ================================================
     SCRIPTS
================================================ --}}
<script>
document.getElementById('currentDate').textContent = new Date().toLocaleDateString('id-ID',{
    weekday:'long', year:'numeric', month:'long', day:'numeric'
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem('token');
    const ctx   = document.getElementById('myAreaChart').getContext('2d');

    let myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Peminjaman',
                lineTension: 0.45,
                fill: true,
                backgroundColor: function(c){
                    const g = c.chart.ctx.createLinearGradient(0,0,0,270);
                    g.addColorStop(0,'rgba(78,115,223,0.14)');
                    g.addColorStop(1,'rgba(78,115,223,0)');
                    return g;
                },
                borderColor: 'rgba(78,115,223,1)',
                borderWidth: 2.5,
                pointRadius: 4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: 'rgba(78,115,223,1)',
                pointBorderWidth: 2.5,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: 'rgba(78,115,223,1)',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2,
                pointHitRadius: 14,
                data: [],
            }],
        },
        options: {
            maintainAspectRatio: false,
            interaction: { mode:'index', intersect:false },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color:'#b0b5c8',
                        font:{ family:'Sora', size:10, weight:'600' },
                        padding:6,
                        maxTicksLimit: 6,
                    },
                    grid: { color:'#f1f3f9', drawBorder:false },
                    border: { display:false }
                },
                x: {
                    ticks: {
                        color:'#b0b5c8',
                        font:{ family:'Sora', size:10, weight:'600' },
                        padding:4,
                        maxRotation: 0,
                    },
                    grid: { display:false },
                    border: { display:false }
                }
            },
            plugins: {
                legend: { display:false },
                tooltip: {
                    backgroundColor:'#1a1d2e',
                    titleColor:'#ffffff',
                    bodyColor:'rgba(255,255,255,0.6)',
                    borderColor:'rgba(255,255,255,0.06)',
                    borderWidth:1,
                    padding:{ x:14, y:10 },
                    cornerRadius:10,
                    titleFont:{ family:'Sora', weight:'700', size:11 },
                    bodyFont:{ family:'JetBrains Mono', size:11 },
                    displayColors:false,
                    callbacks:{ label: c => `  ${c.parsed.y} peminjaman` }
                }
            }
        }
    });

    fetch('/api/dashboard/statsadmin', {
        method: 'GET',
        headers: { 'Accept':'application/json', 'Authorization':`Bearer ${token}` }
    })
    .then(r => {
        if (!r.ok) throw new Error('Gagal mengambil data dari server');
        return r.json();
    })
    .then(data => {
        // 1. Cards
        document.getElementById('countAnggota').innerText   = data.total_anggota;
        document.getElementById('countBuku').innerText      = data.total_buku;
        document.getElementById('countTransaksi').innerText = data.peminjaman_aktif;
        document.getElementById('countTerlambat').innerHTML =
            `<i class="fas fa-clock stat-dot" style="color:var(--danger);"></i>
             <span style="color:var(--danger);">${data.terlambat} Terlambat</span>`;

        // 2. Chart
        myChart.data.labels           = data.chart_labels;
        myChart.data.datasets[0].data = data.chart_data;
        myChart.update();

        // 3. Buku Terbaru
        const c = document.getElementById('bukuTerbaruList');
        c.innerHTML = '';
        data.buku_terbaru.forEach((buku, i) => {
            c.innerHTML += `
            <div class="book-row">
                <span class="book-num">${String(i+1).padStart(2,'0')}</span>
                <div class="book-icon-wrap"><i class="fas fa-book"></i></div>
                <div class="overflow-hidden">
                    <span class="book-title">${buku.judul}</span>
                    <span class="book-sub">Baru saja ditambahkan</span>
                </div>
            </div>`;
        });
    })
    .catch(err => {
        console.error('Error fetching dashboard stats:', err);
        document.getElementById('countAnggota').innerText   = '0';
        document.getElementById('countBuku').innerText      = '0';
        document.getElementById('countTransaksi').innerText = '0';
    });
});
</script>

@endsection