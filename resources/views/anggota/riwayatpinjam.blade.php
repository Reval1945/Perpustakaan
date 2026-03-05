@extends('layouts.anggota')

@section('title', 'Riwayat Peminjaman')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1" style="color: var(--dark); font-weight: 700;">Riwayat Peminjaman</h1>
        <p class="text-muted small mb-0">Daftar seluruh transaksi buku yang pernah Anda lakukan</p>
    </div>
    <div>
        <button class="btn btn-success shadow-sm px-4 btn-main" id="btn-cetak">
            <i class="fas fa-print mr-2"></i> Cetak Riwayat
        </button>
    </div>
</div>

<div class="card shadow mb-4" style="border: none; border-radius: 16px;">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white" style="border-color: var(--border); border-radius: 30px 0 0 30px;">
                            <i class="fas fa-search" style="color: var(--gray);"></i>
                        </span>
                    </div>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari judul buku..." style="border-color: var(--border); border-radius: 0 30px 30px 0; border-left: none;">
                </div>
            </div>
            <div class="col-md-5 mt-2 mt-md-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white" style="border-color: var(--border); border-radius: 30px 0 0 30px;">
                            <i class="fas fa-calendar-alt" style="color: var(--gray);"></i>
                        </span>
                    </div>
                    <input type="date" id="filterTanggal" class="form-control" style="border-color: var(--border); border-radius: 0 30px 30px 0; border-left: none;">
                </div>
            </div>
            <div class="col-md-2 text-md-right mt-2 mt-md-0">
                <button class="btn btn-light btn-block shadow-sm" id="btnRefreshRiwayat" onclick="loadRiwayat()" style="border-radius: 30px; border-color: var(--border);">
                    <i class="fas fa-sync-alt" id="iconRefreshRiwayat"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4" style="border: none; border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" width="100%" cellspacing="0">
                <thead style="background: var(--gray-light);">
                    <tr>
                        <th class="text-center" style="width: 5%; color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">No</th>
                        <th style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Judul Buku</th>
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Tgl Pinjam</th>
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Tgl Kembali</th>
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Durasi</th>
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Status</th>
                    </tr>
                </thead>
                <tbody id="riwayat-body">
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2 mb-0 text-muted">Memuat data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #2C5AA0;
    --primary-soft: #e8f0fe;
    --success: #10b981;
    --success-soft: #ecfdf5;
    --warning: #f59e0b;
    --warning-soft: #fffbeb;
    --danger: #ef4444;
    --danger-soft: #fef2f2;
    --dark: #1e293b;
    --gray: #64748b;
    --gray-light: #f8fafc;
    --border: #e2e8f0;
}

.btn-main { height: 45px; border-radius: 12px; border: none; font-weight: 600; font-size: 0.85rem; transition: all 0.3s; }
.table td { vertical-align: middle !important; border-color: var(--border); padding: 1rem 0.75rem; color: var(--dark); }
.badge { font-weight: 600; padding: 0.45rem 0.85rem; border-radius: 30px; font-size: 0.75rem; }
.form-control:focus { border-color: var(--primary); box-shadow: none; }

/* Animasi Putar */
.fa-spin-custom {
    animation: spin 1s infinite linear;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
let semuaRiwayat = [];

// ================= LOAD DATA =================
async function loadRiwayat() {
    const btn = document.getElementById('btnRefreshRiwayat');
    const icon = document.getElementById('iconRefreshRiwayat');
    const tbody = document.getElementById('riwayat-body');

    // Aktifkan animasi
    icon.classList.add('fa-spin-custom');
    btn.disabled = true;

    try {
        const token = localStorage.getItem('token');
        const res = await fetch('/api/transaksi-me', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error(res.status);

        const result = await res.json();
        semuaRiwayat = result.data || [];

        renderRiwayat(semuaRiwayat);

    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-danger">Gagal memuat data</td></tr>`;
    } finally {
        // Matikan animasi setelah selesai (dengan sedikit delay agar terlihat smooth)
        setTimeout(() => {
            icon.classList.remove('fa-spin-custom');
            btn.disabled = false;
        }, 600);
    }
}

// ================= RENDER (LOGIKA ASLI) =================
function renderRiwayat(data) {
    const tbody = document.getElementById('riwayat-body');
    tbody.innerHTML = '';

    let no = 1;

    function formatTanggal(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    }

    data.forEach(trx => {
        const detailRiwayat = trx.details.filter(d =>
            ['menunggu_verifikasi_kembali', 'dikembalikan', 'terlambat', 'dipinjam']
            .includes(d.status)
        );

        detailRiwayat.forEach(detail => {
            let lama = '-';
            if (detail.tanggal_kembali) {
                const pinjam = new Date(trx.tanggal_pinjam);
                const kembali = new Date(detail.tanggal_kembali);
                const selisih = Math.ceil((kembali - pinjam) / (1000 * 60 * 60 * 24));
                lama = selisih + ' hari';
            }

            let badge = '';
            if (detail.status === 'dikembalikan')
                badge = `<span class="badge" style="background: var(--success-soft); color: var(--success);">Dikembalikan</span>`;
            else if (detail.status === 'terlambat')
                badge = `<span class="badge" style="background: var(--danger-soft); color: var(--danger);">Terlambat</span>`;
            else if (detail.status === 'dipinjam')
                badge = `<span class="badge" style="background: var(--primary-soft); color: var(--primary);">Dipinjam</span>`;
            else
                badge = `<span class="badge" style="background: var(--warning-soft); color: var(--warning);">Verifikasi...</span>`;

            tbody.innerHTML += `
            <tr>
                <td class="text-center text-muted small">${no++}</td>
                <td class="font-weight-bold">${detail.judul_buku}</td>
                <td class="text-center">${formatTanggal(trx.tanggal_pinjam)}</td>
                <td class="text-center">${detail.tanggal_kembali ? formatTanggal(detail.tanggal_kembali) : '<span class="text-muted">-</span>'}</td>
                <td class="text-center">${lama}</td>
                <td class="text-center">${badge}</td>
            </tr>`;
        });
    });

    if (tbody.innerHTML === '') {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted">Tidak ditemukan data</td></tr>`;
    }
}

// ================= FILTER =================
function applyFilter() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const tanggal = document.getElementById('filterTanggal').value;

    const hasil = semuaRiwayat.map(trx => ({
        ...trx,
        details: trx.details.filter(d => {
            const cocokJudul = d.judul_buku.toLowerCase().includes(keyword);
            const cocokTanggal = !tanggal || trx.tanggal_pinjam.startsWith(tanggal);
            const statusValid = ['menunggu_verifikasi_kembali', 'dikembalikan', 'terlambat', 'dipinjam'].includes(d.status);
            return cocokJudul && cocokTanggal && statusValid;
        })
    })).filter(trx => trx.details.length > 0);

    renderRiwayat(hasil);
}

// ================= EXCEL EXPORT (LOGIKA ASLI + SWEETALERT) =================
document.getElementById('btn-cetak').addEventListener('click', function() {
    const btn = this;
    const originalContent = btn.innerHTML;
    const token = localStorage.getItem('token');

    if (!token) {
        Swal.fire('Error', 'Silakan login terlebih dahulu', 'error');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm mr-2"></span> Memproses...`;

    fetch('/api/transaksi-me/export', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal export');
        return res.blob();
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Riwayat_Peminjaman.xlsx`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data riwayat berhasil diexport',
            timer: 2000,
            showConfirmButton: false
        });
    })
    .catch(err => {
        Swal.fire('Gagal', 'Gagal mencetak data', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalContent;
    });
});

document.addEventListener('DOMContentLoaded', () => {
    loadRiwayat();
    document.getElementById('searchInput').addEventListener('input', applyFilter);
    document.getElementById('filterTanggal').addEventListener('change', applyFilter);
});
</script>
@endsection