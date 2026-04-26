@extends('layouts.anggota')

@section('title', 'Riwayat Peminjaman')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1" style="color: var(--dark); font-weight: 700;">Riwayat Peminjaman</h1>
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
                <button id="btnRefreshRiwayat" onclick="resetFilter()" class="btn btn-light w-100" style="border-radius: 12px; height: 45px; border: 1px solid var(--border);">
                    <i class="fas fa-undo mr-1" id="iconRefreshRiwayat"></i> Reset
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
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Status Buku</th>
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Denda</th>
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Status Denda</th>
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Catatan</th>
                        <th class="text-center" style="color: var(--gray); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border: none;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="riwayat-body">
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2 mb-0 text-muted">Memuat data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex align-items-center justify-content-between mt-3 px-1" id="paginationWrapper" style="display:none!important;">
    <div class="text-muted small" id="paginationInfo"></div>
    <nav>
        <ul class="pagination pagination-sm mb-0" id="paginationLinks" style="gap: 4px;"></ul>
    </nav>
</div>

<style>
:root {
    --primary: #2C5AA0;
    --primary-soft: #e8f0fe;
    --warning: #f59e0b;
    --dark: #1e293b;
    --gray: #64748b;
    --gray-light: #f8fafc;
    --border: #e2e8f0;
}

.btn-main { height: 45px; border-radius: 12px; border: none; font-weight: 600; font-size: 0.85rem; transition: all 0.3s; }
.table td { vertical-align: middle !important; border-color: var(--border); padding: 1rem 0.75rem; color: var(--dark); }
.badge { font-weight: 600; padding: 0.45rem 0.85rem; border-radius: 30px; font-size: 0.75rem; }
.form-control:focus { border-color: var(--primary); box-shadow: none; }

.badge-custom { 
    font-weight: 600; 
    padding: 0.45rem 0.85rem; 
    border-radius: 30px; 
    font-size: 0.7rem; 
    display: inline-block;
    min-width: 95px;
    text-align: center;
}

/* Pagination Styling Identik Anggota */
#paginationLinks .page-item .page-link {
    border-radius: 8px !important;
    border: 1px solid var(--border);
    color: var(--primary);
    font-weight: 600;
    font-size: 0.8rem;
    padding: 0.35rem 0.65rem;
    transition: all 0.2s;
}
#paginationLinks .page-item.active .page-link {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
}
#paginationLinks .page-item.disabled .page-link {
    color: var(--gray);
    pointer-events: none;
}

.fa-spin-custom { animation: spin 1s infinite linear; }
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>

<script>
let semuaRiwayatRaw = []; // Data mentah dari API
let riwayatFlattened = []; // Data yang sudah di-breakdown per buku
let currentPage = 1;
const ITEMS_PER_PAGE = 10;

// ================= LOAD DATA =================
async function loadRiwayat() {
    const btn = document.getElementById('btnRefreshRiwayat');
    const icon = document.getElementById('iconRefreshRiwayat');
    const tbody = document.getElementById('riwayat-body');

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
        semuaRiwayatRaw = result.data || [];
        
        // Flatten data agar pagination menghitung per buku, bukan per transaksi
        flattenRiwayat();
        applyFilter();

    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-danger">Gagal memuat data</td></tr>`;
    } finally {
        setTimeout(() => {
            icon.classList.remove('fa-spin-custom');
            btn.disabled = false;
        }, 600);
    }
}

function flattenRiwayat() {
    riwayatFlattened = [];
    semuaRiwayatRaw.forEach(trx => {
        trx.details.forEach(detail => {
            riwayatFlattened.push({
                ...detail,
                tanggal_pinjam: trx.tanggal_pinjam
            });
        });
    });
}

// ================= RENDER =================
function renderRiwayat(data) {
    const tbody = document.getElementById('riwayat-body');
    tbody.innerHTML = '';

    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-5 text-muted">Tidak ditemukan data</td></tr>`;
        renderPagination(0, 1);
        return;
    }

    const totalPages = Math.ceil(data.length / ITEMS_PER_PAGE);
    if (currentPage > totalPages) currentPage = totalPages;
    const startIdx = (currentPage - 1) * ITEMS_PER_PAGE;
    const pageData = data.slice(startIdx, startIdx + ITEMS_PER_PAGE);

    function formatTanggal(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
    }

    pageData.forEach((item, index) => {
        const globalNo = startIdx + index + 1;

        // 1. Badge Status Buku
        let badgeBuku = '';
        const s = item.status;
        if (s === 'dikembalikan') badgeBuku = `<span class="badge-custom" style="background: var(--success-soft); color: var(--success);">Selesai</span>`;
        else if (s === 'terlambat') badgeBuku = `<span class="badge-custom" style="background: var(--danger-soft); color: var(--danger);">Terlambat</span>`;
        else if (s === 'rusak') badgeBuku = `<span class="badge-custom" style="background: var(--warning-soft); color: var(--warning);">Rusak</span>`;
        else if (s === 'hilang') badgeBuku = `<span class="badge-custom" style="background: var(--danger-soft); color: var(--danger);">Hilang</span>`;
        else if (s === 'ditolak') badgeBuku = `<span class="badge-custom" style="background: var(--danger-soft); color: var(--danger);">Ditolak</span>`;
        else if (s === 'dipinjam') badgeBuku = `<span class="badge-custom" style="background: var(--primary-soft); color: var(--primary);">Dipinjam</span>`;
        else if (s === 'diperpanjang') badgeBuku = `<span class="badge-custom" style="background: var(--info-soft); color: var(--info);">Diperpanjang</span>`;
        else badgeBuku = `<span class="badge-custom" style="background: var(--warning-soft); color: var(--warning);">Proses...</span>`;

        let infoDenda = item.denda > 0 ? `Rp ${new Intl.NumberFormat('id-ID').format(item.denda)}` : '-';

        let badgeDenda = '-';
        if (item.denda > 0 || ['rusak', 'hilang'].includes(item.status)) {
            badgeDenda = item.status_denda === 'lunas' 
                ? `<span class="badge-custom" style="background: var(--success-soft); color: var(--success);">Lunas</span>`
                : `<span class="badge-custom" style="background: var(--danger-soft); color: var(--danger);">Belum Lunas</span>`;
        }

        tbody.innerHTML += `
        <tr class="small">
            <td class="text-center text-muted small">${globalNo}</td>
            <td class="font-weight-bold" style="max-width: 200px;">${item.judul_buku}</td>
            <td class="text-center small">${formatTanggal(item.tanggal_pinjam)}</td>
            <td class="text-center small">${formatTanggal(item.tanggal_kembali)}</td>
            <td class="text-center">${badgeBuku}</td>
            <td class="text-center font-weight-bold text-dark small">${infoDenda}</td>
            <td class="text-center small">${badgeDenda}</td>
            <td class="text-center italic small" style="max-width: 200px;">${item.catatan ?? '-'}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-light" onclick="cetakRiwayatSatu('${item.id}')" style="border-radius: 8px; border: 1px solid var(--border);">
                    <i class="fas fa-file-pdf text-danger"></i>
                </button>
            </td>
        </tr>`;
    });

    renderPagination(data.length, totalPages);
}

function renderPagination(totalItems, totalPages) {
    const wrapper = document.getElementById('paginationWrapper');
    const info    = document.getElementById('paginationInfo');
    const links   = document.getElementById('paginationLinks');

    if (totalItems <= ITEMS_PER_PAGE) {
        wrapper.style.setProperty('display', 'none', 'important');
        return;
    }

    wrapper.style.setProperty('display', 'flex', 'important');
    const startPos = (currentPage - 1) * ITEMS_PER_PAGE + 1;
    const endPos   = Math.min(currentPage * ITEMS_PER_PAGE, totalItems);

    let html = `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="goToPage(${currentPage - 1}); return false;"><i class="fas fa-chevron-left" style="font-size:0.7rem;"></i></a>
    </li>`;

    for (let i = 1; i <= totalPages; i++) {
        // Logika sederhana pagination (bisa dikembangkan dengan elipsis jika halaman > 10)
        html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="#" onclick="goToPage(${i}); return false;">${i}</a>
        </li>`;
    }

    html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="goToPage(${currentPage + 1}); return false;"><i class="fas fa-chevron-right" style="font-size:0.7rem;"></i></a>
    </li>`;

    links.innerHTML = html;
}

function goToPage(page) {
    currentPage = page;
    applyFilter();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ================= FILTER =================
function applyFilter() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const tanggal = document.getElementById('filterTanggal').value;

    const hasil = riwayatFlattened.filter(item => {
        const cocokJudul = item.judul_buku.toLowerCase().includes(keyword);
        const cocokTanggal = !tanggal || item.tanggal_pinjam.startsWith(tanggal);
        return cocokJudul && cocokTanggal;
    });

    renderRiwayat(hasil);
}

function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterTanggal').value = '';
    currentPage = 1;
    loadRiwayat();
}

// ================= EXCEL EXPORT =================
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

async function cetakRiwayatSatu(id) {
    Swal.fire({ title: 'Menghasilkan PDF...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    try {
        const token = localStorage.getItem('token');
        const res = await fetch(`/api/laporan/transaksi/${id}`, { 
            headers: { 'Authorization': 'Bearer ' + token } 
        });
        const blob = await res.blob();
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url; 
        link.download = `Invoice_Riwayat_${id}.pdf`; 
        link.click();
        Swal.close();
    } catch (err) { Swal.fire('Gagal', 'Gagal mencetak dokumen', 'error'); }
}

document.addEventListener('DOMContentLoaded', () => {
    loadRiwayat();
    document.getElementById('searchInput').addEventListener('input', () => { currentPage = 1; applyFilter(); });
    document.getElementById('filterTanggal').addEventListener('change', () => { currentPage = 1; applyFilter(); });
});
</script>
@endsection