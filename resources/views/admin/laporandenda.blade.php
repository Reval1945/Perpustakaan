@extends('layouts.admin')

@section('title', 'Laporan Denda')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" style="color: var(--dark); font-weight: 700;">
        Laporan Denda
    </h1>
    <div class="mt-3 mt-md-0">
        <button onclick="cetakLaporan()" class="btn btn-main btn-success shadow-sm px-4">
            <i class="fas fa-print fa-sm mr-2"></i> Cetak Laporan Denda
        </button>
    </div>
</div>

<div class="card shadow-sm mb-4" style="border: none; border-radius: 16px;">
    <div class="card-body py-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="small font-weight-bold text-muted">Cari Peminjam/Buku</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white" style="border-color: var(--border); border-radius: 30px 0 0 30px;">
                            <i class="fas fa-search" style="color: var(--gray);"></i>
                        </span>
                    </div>
                    <input type="text" id="searchInput" class="form-control" placeholder="Ketik nama atau judul..." style="border-color: var(--border); border-radius: 0 30px 30px 0; border-left: none;">
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
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">No</th>
                        <th class="py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Peminjam</th>
                        <th class="py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Buku</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Tgl Pinjam</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Tgl Kembali</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Telat</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Jenis Denda</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Status Denda</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-denda">
                    {{-- Data via JS --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditDenda" tabindex="-1" style="border-radius: 16px;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px;">
            <div class="modal-header" style="border-bottom: 1px solid var(--gray-light);">
                <h5 class="modal-title font-weight-bold">Update Status Denda</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body py-4">
                <input type="hidden" id="denda_id">
                <div class="form-group">
                    <label class="small font-weight-bold text-muted">Status Pembayaran</label>
                    <select id="status_denda" class="form-control" style="border-radius: 12px; height: 50px;">
                        <option value="belum_lunas">Belum Lunas</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button class="btn btn-light px-4" data-dismiss="modal" style="border-radius: 10px;">Batal</button>
                <button class="btn btn-primary px-4" id="btnUpdateDenda" style="border-radius: 10px; background: var(--primary);">Simpan Perubahan</button>
            </div>
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
.form-control:focus { border-color: var(--primary); box-shadow: none; }
.table td { vertical-align: middle !important; border-top: 1px solid var(--border); padding: 1rem 0.75rem; color: var(--dark); font-size: 0.9rem; }

/* Badge Style Match */
.badge-custom { 
    font-weight: 600; 
    padding: 0.45rem 0.85rem; 
    border-radius: 30px; 
    font-size: 0.7rem; 
    display: inline-block;
    min-width: 95px;
    text-align: center;
    text-transform: uppercase;
}
</style>

<script>
let allDenda = [];
const token = localStorage.getItem("token");

// Helper: Format Tanggal
function formatTanggal(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
}

// Helper: Format Rupiah
function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
}

document.addEventListener("DOMContentLoaded", loadDenda);

async function loadDenda() {
    const tbody = document.getElementById("table-denda");
    tbody.innerHTML = `<tr><td colspan="9" class="text-center py-5"><div class="spinner-border text-primary"></div></td></tr>`;

    try {
        const res = await fetch("http://127.0.0.1:8000/api/denda/details", {
            headers: { 
                "Authorization": "Bearer " + token,
                "Accept": "application/json",
                "X-Application": "perpus-admin"
            }
        });
        const json = await res.json();
        allDenda = json.data || [];
        renderTable(allDenda);
    } catch(err) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-5 text-danger">Gagal memuat data</td></tr>`;
    }
}

function renderTable(data) {
    const tbody = document.getElementById("table-denda");
    if(data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-5 text-muted">Tidak ada riwayat denda ditemukan</td></tr>`;
        return;
    }

    tbody.innerHTML = data.map((item, index) => {
        let tglPinjam = formatTanggal(item.transaction?.tanggal_pinjam);
        let tglKembali = formatTanggal(item.tanggal_kembali);
        let hariTelat = parseInt(item.jumlah_hari_telat) || 0;
        
        // Status Badge Logic
        let statusHtml = item.status_denda === "lunas" 
            ? `<span class="badge-custom" style="background: var(--success-soft); color: var(--success);">Lunas</span>`
            : `<span class="badge-custom" style="background: var(--danger-soft); color: var(--danger);">Belum Lunas</span>`;

        return `
            <tr>
                <td class="text-center text-muted small">${index + 1}</td>
                <td class="font-weight-bold">${item.transaction?.user?.name ?? '-'}</td>
                <td>${item.judul_buku ?? "-"}</td>
                <td class="text-center small">${tglPinjam}</td>
                <td class="text-center small">${tglKembali}</td>
                <td class="text-center">
                    <span class="badge badge-light text-dark" style="border-radius: 6px;">${hariTelat} Hari</span>
                </td>
               <td class="text-center font-weight-bold text-primary">
                    ${item.jenis_denda.charAt(0).toUpperCase() + item.jenis_denda.slice(1)}
                </td>
                <td class="text-center">${statusHtml}</td>
                <td class="text-center">
                    <div class="btn-group">
                        <button class="btn btn-sm btn-light border btn-edit-denda" data-id="${item.id}" style="border-radius: 8px 0 0 8px;">
                            <i class="fas fa-edit text-warning"></i>
                        </button>
                        <button class="btn btn-sm btn-light border" onclick="cetakDendaSatu('${item.id}')" style="border-radius: 0 8px 8px 0;">
                            <i class="fas fa-file-pdf text-danger"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Fitur Filter & Search
function applyFilter() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;

    const filtered = allDenda.filter(item => {
        const nama = (item.transaction?.user?.name ?? '').toLowerCase();
        const judul = (item.judul_buku ?? '').toLowerCase();
        const tglPinjam = item.transaction?.tanggal_pinjam;

        const matchKeyword = nama.includes(keyword) || judul.includes(keyword);
        
        let matchDate = true;
        if (start && end) matchDate = tglPinjam >= start && tglPinjam <= end;
        else if (start) matchDate = tglPinjam >= start;
        else if (end) matchDate = tglPinjam <= end;

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
    renderTable(allDenda);
}

// Modal & Update Logic
document.addEventListener("click", function(e) {
    const btn = e.target.closest(".btn-edit-denda");
    if (!btn) return;
    const id = btn.dataset.id;
    const item = allDenda.find(d => d.id == id);
    document.getElementById("denda_id").value = id;
    document.getElementById("status_denda").value = item.status_denda;
    $("#modalEditDenda").modal("show");
});

document.getElementById("btnUpdateDenda").addEventListener("click", async function() {
    const id = document.getElementById("denda_id").value;
    const status = document.getElementById("status_denda").value;
    
    try {
        const res = await fetch(`http://127.0.0.1:8000/api/denda/details/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            },
            body: JSON.stringify({ status_denda: status })
        });

        if(res.ok) {
            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Status denda diperbarui!', timer: 1500, showConfirmButton: false });
            $("#modalEditDenda").modal("hide");
            loadDenda();
        }
    } catch (err) {
        Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
    }
});

// Cetak Laporan (Excel)
async function cetakLaporan() {
    Swal.fire({ title: 'Menyiapkan File...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    try {
        const res = await fetch("http://127.0.0.1:8000/api/laporan/denda/excel", {
            headers: { "Authorization": "Bearer " + token }
        });
        const blob = await res.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = `Laporan_Denda_${new Date().getTime()}.xlsx`;
        a.click();
        Swal.close();
    } catch (err) {
        Swal.fire('Gagal', 'Gagal mengunduh laporan', 'error');
    }
}

// Cetak Satu (PDF)
async function cetakDendaSatu(id) {
    Swal.fire({ title: 'Menghasilkan PDF...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    try {
        const res = await fetch(`http://127.0.0.1:8000/api/laporan/denda/${id}`, {
            headers: { "Authorization": "Bearer " + token }
        });
        if (!res.ok) throw new Error();
        const blob = await res.blob();
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.href = url;
        link.download = `Invoice_Denda_${id}.pdf`;
        link.click();
        Swal.close();
    } catch (err) {
        Swal.fire('Gagal', 'Gagal mencetak dokumen', 'error');
    }
}
</script>
@endsection