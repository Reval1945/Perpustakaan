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
                <label class="small font-weight-bold text-muted">Dari Tanggal</label>
                <input type="date" id="startDate" class="form-control" style="border-radius: 12px; border-color: var(--border);">
            </div>
            <div class="col-md-3">
                <label class="small font-weight-bold text-muted">Sampai Tanggal</label>
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
                    </tr>
                </thead>
                <tbody id="laporan-body">
                    {{-- Data via JS --}}
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

/* Base Badge Style */
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
</style>

<script>
    let allData = [];
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
            renderTable(allData);
        } catch(err) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-danger">Gagal memuat data</td></tr>`;
        }
    }

    function renderTable(data) {
        if(data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted">Data tidak ditemukan</td></tr>`;
            return;
        }

        tbody.innerHTML = data.map((item, index) => {
            let nama = item.transaction?.user?.name ?? '-';
            let judul = item.judul_buku ?? '-';
            let tglPinjam = formatTanggal(item.transaction?.tanggal_pinjam);
            let tglKembali = item.tanggal_kembali ? formatTanggal(item.tanggal_kembali) : "-";
            
            // Logika Badge Modern
            let badgeHtml = '';
            if (item.status === 'dikembalikan') {
                badgeHtml = `<span class="badge-custom" style="background: var(--success-soft); color: var(--success);">Selesai</span>`;
            } else if (item.status === 'dipinjam') {
                badgeHtml = `<span class="badge-custom" style="background: var(--primary-soft); color: var(--primary);">Dipinjam</span>`;
            } else if (item.status === 'diperpanjang') {
                badgeHtml = `<span class="badge-custom" style="background: #e1f5fe; color: var(--info);">Diperpanjang</span>`;
            } else if (item.status === 'terlambat') {
                badgeHtml = `<span class="badge-custom" style="background: var(--danger-soft); color: var(--danger);">Terlambat</span>`;
            } else if (item.status === 'rusak') {
                badgeHtml = `<span class="badge-custom" style="background: var(--danger-soft); color: var(--danger);">Rusak</span>`;
            } else if (item.status === 'hilang') {
                badgeHtml = `<span class="badge-custom" style="background: var(--danger-soft); color: var(--danger);">Hilang</span>`;
            } else {
                badgeHtml = `<span class="badge-custom" style="background: var(--warning-soft); color: var(--warning);">Verifikasi...</span>`;
            }

            return `
                <tr>
                    <td class="text-center text-muted">${index + 1}</td>
                    <td class="px-4 font-weight-bold">${nama}</td>
                    <td>${judul}</td>
                    <td class="text-center">${tglPinjam}</td>
                    <td class="text-center">${tglKembali}</td>
                    <td class="text-center">${badgeHtml}</td>
                </tr>
            `;
        }).join('');
    }

    function applyFilter() {
        const keyword = document.getElementById('searchInput').value.toLowerCase();
        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;

        const filtered = allData.filter(item => {
            const nama = (item.transaction?.user?.name ?? '').toLowerCase();
            const judul = (item.judul_buku ?? '').toLowerCase();
            const tglPinjam = item.transaction?.tanggal_pinjam;

            const matchKeyword = nama.includes(keyword) || judul.includes(keyword);
            
            let matchDate = true;
            if (start && end) {
                matchDate = tglPinjam >= start && tglPinjam <= end;
            } else if (start) {
                matchDate = tglPinjam >= start;
            } else if (end) {
                matchDate = tglPinjam <= end;
            }

            return matchKeyword && matchDate;
        });

        renderTable(filtered);
    }

    document.getElementById('searchInput').addEventListener('input', applyFilter);
    document.getElementById('startDate').addEventListener('change', applyFilter);
    document.getElementById('endDate').addEventListener('change', applyFilter);

    function resetFilter() {
        document.getElementById('searchInput').value = "";
        document.getElementById('startDate').value = "";
        document.getElementById('endDate').value = "";
        renderTable(allData);
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