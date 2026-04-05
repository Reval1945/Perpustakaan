@extends('layouts.admin')

@section('title', 'Laporan Peminjaman')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" style="color: var(--dark); font-weight: 700;">
        Laporan Transaksi
    </h1>
    <div class="mt-3 mt-md-0">
        <button onclick="cetakLaporan()" class="btn btn-main btn-success shadow-sm px-4">
            <i class="fas fa-print fa-sm mr-2"></i> Cetak Laporan
        </button>
    </div>
</div>

<div class="card shadow-sm mb-4" style="border: none; border-radius: 16px;">
    <div class="card-body py-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="small font-weight-bold text-muted">Cari Data</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white" style="border-color: var(--border); border-radius: 30px 0 0 30px;">
                            <i class="fas fa-search" style="color: var(--gray);"></i>
                        </span>
                    </div>
                    <input type="text" id="searchInput" class="form-control" placeholder="Nama atau judul buku..." style="border-color: var(--border); border-radius: 0 30px 30px 0; border-left: none;">
                </div>
            </div>
            <div class="col-md-3">
                <label class="small font-weight-bold text-muted">Tgl Pinjam (Dari)</label>
                <input type="date" id="startDate" class="form-control" style="border-radius: 12px; border-color: var(--border);">
            </div>
            <div class="col-md-3">
                <label class="small font-weight-bold text-muted">Tgl Kembali (Sampai)</label>
                <input type="date" id="endDate" class="form-control" style="border-radius: 12px; border-color: var(--border);">
            </div>
            <div class="col-md-2">
                <button onclick="resetFilter()" class="btn btn-light w-100" style="border-radius: 12px; height: 45px; border: 1px solid var(--border);">
                    <i class="fas fa-undo mr-1"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm" style="border: none; border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: var(--gray-light);">
                    <tr>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none; width: 60px;">No</th>
                        <th class="py-3 px-4" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Nama Peminjam</th>
                        <th class="py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Judul Buku</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Tgl Pinjam</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Tgl Kembali</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Status</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Catatan</th>
                    </tr>
                </thead>
                <tbody id="laporan-body">
                    {{-- Data via JS --}}
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
    --success: #10b981;
    --success-soft: #ecfdf5;
    --warning: #f59e0b;
    --warning-soft: #fffbeb;
    --danger: #ef4444;
    --danger-soft: #fef2f2;
    --info: #0ea5e9;
    --dark: #1e293b;
    --gray: #64748b;
    --gray-light: #f8fafc;
    --border: #e2e8f0;
}

.badge-custom { 
    font-weight: 600; 
    padding: 0.45rem 0.85rem; 
    border-radius: 30px; 
    font-size: 0.75rem; 
    display: inline-block;
    min-width: 100px;
    text-align: center;
}

.btn-main { height: 45px; border-radius: 12px; border: none; font-weight: 600; font-size: 0.85rem; transition: all 0.3s; }
.form-control:focus { border-color: var(--primary); box-shadow: none; }
.table td { vertical-align: middle !important; border-color: var(--border); padding: 1rem 0.75rem; color: var(--dark); }

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
</style>

<script>
    let allData = [];
    let currentPage = 1;
    const ITEMS_PER_PAGE = 10;
    const token = localStorage.getItem("token");
    const tbody = document.getElementById("laporan-body");

    function formatTanggal(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
    }

    document.addEventListener("DOMContentLoaded", loadData);

    async function loadData() {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></td></tr>`;
        
        try {
            const res = await fetch("http://127.0.0.1:8000/api/transaction-details", {
                headers: { "Authorization": "Bearer " + token, "Accept": "application/json" }
            });
            const json = await res.json();
            allData = json.data || [];
            applyFilter(); // Memanggil filter pertama kali untuk render awal
        } catch(err) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-danger">Gagal memuat data</td></tr>`;
        }
    }

    function renderTable(data) {
        if(data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted">Data tidak ditemukan</td></tr>`;
            renderPagination(0, 1);
            return;
        }

        const totalPages = Math.ceil(data.length / ITEMS_PER_PAGE);
        if (currentPage > totalPages) currentPage = totalPages;
        const start = (currentPage - 1) * ITEMS_PER_PAGE;
        const pageData = data.slice(start, start + ITEMS_PER_PAGE);

        tbody.innerHTML = pageData.map((item, index) => {
            const globalNo = start + index + 1;
            let nama = item.transaction?.user?.name ?? '-';
            let judul = item.judul_buku ?? '-';
            let tglPinjam = formatTanggal(item.transaction?.tanggal_pinjam);
            let tglKembali = item.tanggal_kembali ? formatTanggal(item.tanggal_kembali) : "-";
            
            let badgeHtml = '';
            if (item.status === 'dikembalikan') {
                badgeHtml = `<span class="badge-custom" style="background: var(--success-soft); color: var(--success);">Selesai</span>`;
            } else if (item.status === 'dipinjam') {
                badgeHtml = `<span class="badge-custom" style="background: var(--primary-soft); color: var(--primary);">Dipinjam</span>`;
            } else if (item.status === 'terlambat') {
                badgeHtml = `<span class="badge-custom" style="background: var(--danger-soft); color: var(--danger);">Terlambat</span>`;
            }   else if (item.status === 'rusak') {
                badgeHtml = `<span class="badge-custom" style="background: var(--danger-soft); color: var(--danger);">Rusak</span>`;
            } else if (item.status === 'hilang') {
                badgeHtml = `<span class="badge-custom" style="background: var(--danger-soft); color: var(--danger);">Hilang</span>`;
            } else if (item.status === 'ditolak') {
                badgeHtml = `<span class="badge-custom" style="background: var(--danger-soft); color: var(--danger);">Ditolak</span>`;
            } else {
                badgeHtml = `<span class="badge-custom" style="background: var(--warning-soft); color: var(--warning);">${item.status}</span>`;
            }

            return `
                <tr class="small">
                    <td class="text-center text-muted">${globalNo}</td>
                    <td class="px-4 font-weight-bold">${nama}</td>
                    <td>${judul}</td>
                    <td class="text-center">${tglPinjam}</td>
                    <td class="text-center">${tglKembali}</td>
                    <td class="text-center">${badgeHtml}</td>
                    <td class="text-center small" style="max-width: 200px;">${item.catatan ?? '-'}</td>
                </tr>
            `;
        }).join('');

        renderPagination(data.length, totalPages);
    }

    function renderPagination(totalItems, totalPages) {
        const wrapper = document.getElementById('paginationWrapper');
        const info    = document.getElementById('paginationInfo');
        const links   = document.getElementById('paginationLinks');

        // Sembunyikan jika data <= 10 (Sesuai file anggota)
        if (totalItems <= ITEMS_PER_PAGE) {
            wrapper.style.setProperty('display', 'none', 'important');
            return;
        }

        wrapper.style.setProperty('display', 'flex', 'important');
        const startPos = (currentPage - 1) * ITEMS_PER_PAGE + 1;
        const endPos   = Math.min(currentPage * ITEMS_PER_PAGE, totalItems);
        info.innerHTML = `Menampilkan <strong>${startPos}–${endPos}</strong> dari <strong>${totalItems}</strong> data`;

        const maxVisible = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let endPage   = Math.min(totalPages, startPage + maxVisible - 1);
        if (endPage - startPage < maxVisible - 1) startPage = Math.max(1, endPage - maxVisible + 1);

        let html = `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="goToPage(${currentPage - 1}); return false;"><i class="fas fa-chevron-left" style="font-size:0.7rem;"></i></a>
        </li>`;

        if (startPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(1); return false;">1</a></li>`;
            if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
        }
        for (let i = startPage; i <= endPage; i++) {
            html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" onclick="goToPage(${i}); return false;">${i}</a>
            </li>`;
        }
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) html += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
            html += `<li class="page-item"><a class="page-link" href="#" onclick="goToPage(${totalPages}); return false;">${totalPages}</a></li>`;
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

    function applyFilter() {
        const keyword = document.getElementById('searchInput').value.toLowerCase();
        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;

        const filtered = allData.filter(item => {
            const nama = (item.transaction?.user?.name ?? '').toLowerCase();
            const judul = (item.judul_buku ?? '').toLowerCase();
            const tglPinjam = (item.transaction?.tanggal_pinjam ?? '').substring(0, 10);
            const tglKembali = (item.tanggal_kembali ?? '').substring(0, 10);

            const matchKeyword = nama.includes(keyword) || judul.includes(keyword);
            
            let matchDate = true;
            if (start && end) {
                const matchStart = tglPinjam ? tglPinjam >= start : false;
                const matchEnd = tglKembali ? tglKembali <= end : true;
                matchDate = matchStart && matchEnd;
            } else if (start) {
                matchDate = tglPinjam === start;
            } else if (end) {
                matchDate = tglKembali === end;
            }

            return matchKeyword && matchDate;
        });

        renderTable(filtered);
    }

    document.getElementById('searchInput').addEventListener('input', () => { currentPage = 1; applyFilter(); });
    document.getElementById('startDate').addEventListener('change', () => { currentPage = 1; applyFilter(); });
    document.getElementById('endDate').addEventListener('change', () => { currentPage = 1; applyFilter(); });

    function resetFilter() {
        document.getElementById('searchInput').value = "";
        document.getElementById('startDate').value = "";
        document.getElementById('endDate').value = "";
        currentPage = 1;
        applyFilter();
    }

    async function cetakLaporan() {
        Swal.fire({ title: 'Menyiapkan File...', didOpen: () => Swal.showLoading() });
        try {
            const res = await fetch("http://127.0.0.1:8000/api/laporan/peminjaman/excel", {
                headers: { "Authorization": "Bearer " + token }
            });
            if (!res.ok) throw new Error();
            const blob = await res.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = `Laporan-Peminjaman-${new Date().getTime()}.xlsx`;
            a.click();
            Swal.close();
        } catch (err) {
            Swal.fire('Gagal', 'Gagal mengunduh laporan excel', 'error');
        }
    }
</script>
@endsection