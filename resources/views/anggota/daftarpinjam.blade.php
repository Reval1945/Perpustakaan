@extends('layouts.anggota')

@section('title', 'Daftar Peminjaman')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1" style="color: var(--dark); font-weight: 700;">Daftar Peminjaman</h1>
        <p class="text-muted small mb-0">Pantau koleksi buku yang sedang Anda baca</p>
    </div>
    <div class="text-right">
        <span class="badge badge-pill shadow-sm py-2 px-3" style="background: var(--primary-soft); color: var(--primary); font-size: 0.9rem;">
            <i class="fas fa-book-open mr-1"></i> <span id="totalPinjamCount">0</span> Buku Dipinjam
        </span>
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
                <button class="btn btn-light btn-block shadow-sm" id="btnRefresh" onclick="loadTransaksi()" style="border-radius: 30px; border-color: var(--border); transition: all 0.3s;">
                    <i class="fas fa-sync-alt" id="iconRefresh"></i>
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
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Pinjam</th>
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Tempo</th>
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Sisa Waktu</th>
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Status</th>
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="peminjaman-body">
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2 mb-0 text-muted">Memuat data transaksi...</p>
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

.table td { vertical-align: middle !important; border-color: var(--border); padding: 1rem 0.75rem; color: var(--dark); }
.badge { font-weight: 600; padding: 0.45rem 0.85rem; border-radius: 30px; font-size: 0.75rem; }
.form-control { height: auto; padding: 0.6rem 1rem; }
.form-control:focus { border-color: var(--primary); box-shadow: none; }

.btn-kembali { 
    background: #fef3c7; 
    color: #d97706; 
    border: none; 
    border-radius: 30px; 
    padding: 0.4rem 1.2rem; 
    font-weight: 600;
    transition: 0.2s;
}
.btn-kembali:hover { background: #fde68a; color: #b45309; text-decoration: none; }

.fa-spin-custom {
    animation: spin 1s infinite linear;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
let semuaTransaksi = [];

function formatTanggal(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

async function loadTransaksi() {
    const btnRefresh = document.getElementById('btnRefresh');
    const iconRefresh = document.getElementById('iconRefresh');
    const tbody = document.getElementById('peminjaman-body');

    iconRefresh.classList.add('fa-spin-custom');
    btnRefresh.disabled = true;

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

        // LOGIKA ASLI TETAP SAMA
        semuaTransaksi = (result.data || []).map(trx => ({
            ...trx,
            details: trx.details.filter(d =>
                d.status === 'dipinjam' ||
                d.status === 'menunggu_verifikasi'
            )
        })).filter(trx => trx.details.length > 0);

        renderTabel(semuaTransaksi);
        
        let count = 0;
        semuaTransaksi.forEach(t => count += t.details.length);
        document.getElementById('totalPinjamCount').innerText = count;

    } catch (err) {
        console.error('Gagal ambil transaksi', err);
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5 text-danger">Gagal memuat data.</td></tr>`;
    } finally {
        setTimeout(() => {
            iconRefresh.classList.remove('fa-spin-custom');
            btnRefresh.disabled = false;
        }, 500);
    }
}

function renderTabel(dataTransaksi) {
    const tbody = document.querySelector('#peminjaman-body');
    tbody.innerHTML = '';

    if (!dataTransaksi.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5 text-muted">Tidak ada peminjaman aktif</td></tr>`;
        return;
    }

    let no = 1;
    dataTransaksi.forEach(trx => {
        trx.details.forEach(detail => {
            const today = new Date();
            const jatuhTempo = new Date(detail.tanggal_jatuh_tempo);
            const selisih = Math.ceil((jatuhTempo - today) / (1000 * 60 * 60 * 24));

            let badgeWaktu = '';
            if (selisih > 1)
                badgeWaktu = `<span class="badge" style="background: var(--success-soft); color: var(--success);">${selisih} hari lagi</span>`;
            else if (selisih === 1)
                badgeWaktu = `<span class="badge" style="background: var(--warning-soft); color: var(--warning);">Besok!</span>`;
            else
                badgeWaktu = `<span class="badge" style="background: var(--danger-soft); color: var(--danger);">Telat ${Math.abs(selisih)} hari</span>`;

            const badgeStatus = detail.status === 'dipinjam'
                ? `<span class="badge" style="background: var(--primary-soft); color: var(--primary);">Dipinjam</span>`
                : `<span class="badge" style="background: var(--warning-soft); color: var(--warning);">Verifikasi...</span>`;

            const tombol = detail.status === 'dipinjam'
                ? `<button class="btn-kembali shadow-sm" onclick="ajukanKembali('${trx.id}','${detail.id}', '${detail.judul_buku.replace(/'/g, "\\'")}')">
                    <i class="fas fa-undo-alt mr-1"></i> Kembalikan
                   </button>`
                : `<button class="btn btn-sm btn-light" disabled style="border-radius:30px; opacity:0.6">
                    <i class="fas fa-hourglass-half"></i> Diproses
                   </button>`;

            tbody.innerHTML += `
            <tr>
                <td class="text-center text-muted small">${no++}</td>
                <td class="font-weight-bold">${detail.judul_buku}</td>
                <td class="text-center small">${formatTanggal(trx.tanggal_pinjam)}</td>
                <td class="text-center small">${formatTanggal(detail.tanggal_jatuh_tempo)}</td>
                <td class="text-center">${badgeWaktu}</td>
                <td class="text-center">${badgeStatus}</td>
                <td class="text-center">${tombol}</td>
            </tr>`;
        });
    });
}

function applyFilter() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const tanggal = document.getElementById('filterTanggal').value;

    const hasil = semuaTransaksi.map(trx => ({
        ...trx,
        details: trx.details.filter(d => {
            const cocokJudul = d.judul_buku.toLowerCase().includes(keyword);
            const cocokTanggal = !tanggal || trx.tanggal_pinjam.startsWith(tanggal);
            return cocokJudul && cocokTanggal;
        })
    })).filter(trx => trx.details.length > 0);

    renderTabel(hasil);
}

async function ajukanKembali(id, detailId, judulBuku) {
    const result = await Swal.fire({
        title: 'Kembalikan Buku?',
        text: `Konfirmasi pengembalian untuk buku: ${judulBuku}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'var(--primary)',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Kembalikan',
        cancelButtonText: 'Batal',
        borderRadius: '16px'
    });

    if (!result.isConfirmed) return;

    Swal.fire({
        title: 'Sedang memproses...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    try {
        const token = localStorage.getItem('token');
        const res = await fetch(`/api/transaksi-kembali/${id}`, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ detail_ids: [detailId] })
        });

        const data = await res.json();
        
        if(res.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
            loadTransaksi();
        } else {
            throw new Error(data.message);
        }
    } catch (err) {
        Swal.fire('Gagal', err.message || 'Terjadi kesalahan sistem', 'error');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadTransaksi();
    document.getElementById('searchInput')?.addEventListener('input', applyFilter);
    document.getElementById('filterTanggal')?.addEventListener('change', applyFilter);
});
</script>
@endsection