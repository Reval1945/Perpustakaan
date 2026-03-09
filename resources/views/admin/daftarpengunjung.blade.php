@extends('layouts.admin')

@section('title', 'Daftar Pengunjung')

@section('content')
<!-- Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" style="color: var(--dark); font-weight: 700;">
        Daftar Pengunjung
    </h1>
    <div class="mt-3 mt-md-0">
        <button id="btnCetakAnggota" class="btn btn-main btn-success shadow-sm" style="padding: 0.5rem 1.5rem;">
            <i class="fas fa-print fa-sm mr-2"></i> Cetak Pengunjung
        </button>
    </div>
</div>

<!-- Search -->
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
                <input type="date" id="dateFilter" class="form-control" style="border-radius: 12px; border-color: var(--border);">
            </div>
            <div class="col-md-2">
                <button onclick="resetFilter()" class="btn btn-light w-100" style="border-radius: 12px; height: 45px; border: 1px solid var(--border);">
                    <i class="fas fa-undo mr-1"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

<!--Table  -->
<div class="card shadow-sm" style="border: none; border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: var(--gray-light);">
                    <tr>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; width: 60px;">No</th>
                        <th class="py-3 px-4" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Nama Lengkap</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">NISN</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Kelas</th>
                        <th class="py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Keperluan</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pengunjung">
                    {{-- Data diisi via JS --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border: none; border-radius: 16px;">
            <div class="modal-header text-white" style="background: var(--primary); border-radius: 16px 16px 0 0;">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-edit mr-2"></i>Edit Data Kunjungan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="editId">
                <div class="form-group">
                    <label class="font-weight-bold text-dark"><i class="fas fa-comment-alt mr-1 text-primary"></i> Keperluan</label>
                    <textarea id="editKeperluan" class="form-control" rows="3" placeholder="Masukkan keperluan kunjungan"></textarea>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-dark"><i class="fas fa-calendar-alt mr-1 text-primary"></i> Tanggal Kunjungan</label>
                    <input type="date" id="editTanggal" class="form-control">
                </div>
            </div>
            <div class="modal-footer bg-light" style="border-radius: 0 0 16px 16px;">
                <button class="btn btn-secondary shadow-sm" data-dismiss="modal">Batal</button>
                <button id="btnUpdate" type="button" class="btn btn-primary px-4 shadow-sm">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #2C5AA0;
    --dark: #1e293b;
    --gray: #64748b;
    --gray-light: #f1f5f9;
    --border: #e2e8f0;
}

.btn-main {
    height: 45px;
    padding: 0 18px;
    border-radius: 12px;
    border: none;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.form-control { border-radius: 10px; border: 1px solid var(--border); padding: 0.6rem 1rem; }
.form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(44,90,160,0.1); }
.table td { vertical-align: middle; border-top: 1px solid var(--border); }
</style>

<script>
// Konfigurasi Global
const tbody = document.getElementById("pengunjung");
const token = localStorage.getItem("token");
const searchInput = document.getElementById('searchInput');
const dateFilter = document.getElementById('dateFilter');
let allData = []; // Menyimpan data asli dari server

document.addEventListener("DOMContentLoaded", loadData);

// --- 1. LOAD DATA DARI API ---
async function loadData() {
    tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5"><div class="spinner-border text-primary"></div></td></tr>`;
    
    if(!token) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5 text-danger">Sesi berakhir, silakan login kembali.</td></tr>`;
        return;
    }

    try {
        const res = await fetch("/api/pengunjung", {
            headers: { 
                "Authorization": `Bearer ${token}`, 
                "Accept": "application/json" 
            }
        });
        const json = await res.json();
        allData = json.data || [];
        renderTable(allData);
    } catch(err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger py-4">Gagal memuat data dari server.</td></tr>`;
    }
}

// --- 2. RENDER TABLE KE HTML ---
function renderTable(data) {
    if(data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5 text-muted">Tidak ada data pengunjung ditemukan.</td></tr>`;
        return;
    }

    tbody.innerHTML = data.map((p, index) => `
        <tr>
            <td class="text-center align-middle">
                <span style="color: var(--gray); font-size: 0.9rem;">${index + 1}</span>
            </td>
            <td class="px-4 py-3 align-middle">
                <div class="font-weight-bold" style="color: var(--dark);">${p.nama}</div>
            </td>
            <td class="text-center align-middle">
                <span class="text-muted" style="font-size: 0.9rem; font-family: monospace;">${p.nisn ?? '-'}</span>
            </td>
            <td class="text-center align-middle">
                <span class="badge badge-pill border px-3 py-2" style="color: var(--gray); background: white;">
                    ${p.kelas}
                </span>
            </td>
            <td class="text-wrap align-middle" style="max-width: 200px;">
                <span class="text-dark" style="font-size: 0.9rem;">${p.keperluan}</span>
            </td>
            <td class="text-center align-middle">
                <div class="text-dark" style="font-weight: 500;">${formatTanggal(p.tanggal_kunjungan)}</div>
            </td>
            <td class="text-center align-middle">
                <div class="d-flex justify-content-center">
                    <button class="btn btn-sm btn-light mr-1" onclick="openEdit('${p.id}','${p.keperluan}','${p.tanggal_kunjungan}')" 
                        style="border-radius: 8px; border: 1px solid var(--border);">
                        <i class="fas fa-edit text-warning"></i>
                    </button>
                    <button class="btn btn-sm" onclick="hapusData('${p.id}')" 
                        style="background:#fee2e2;color:#ef4444;border-radius:10px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// --- 3. LOGIKA FILTER (SEARCH & DATE) ---
searchInput.addEventListener('input', applyFilters);
dateFilter.addEventListener('change', applyFilters);

function applyFilters() {
    const kw = searchInput.value.toLowerCase();
    const selectedDate = dateFilter.value; // YYYY-MM-DD

    const filtered = allData.filter(p => {
        // Cek kecocokan teks pada Nama atau Keperluan
        const matchesText = p.nama.toLowerCase().includes(kw) || 
                            p.keperluan.toLowerCase().includes(kw);
        
        // Cek kecocokan tanggal (jika input tanggal kosong, loloskan semua)
        const matchesDate = selectedDate ? (p.tanggal_kunjungan === selectedDate) : true;

        return matchesText && matchesDate;
    });

    renderTable(filtered);
}

// Fungsi Reset Filter (Panggil lewat tombol refresh)
function resetFilter() {
    // 1. Kosongkan nilai pada input pencarian nama
    const searchInput = document.getElementById('searchInput');
    if (searchInput) searchInput.value = "";

    // 2. Kosongkan nilai pada input filter tanggal
    const dateFilter = document.getElementById('dateFilter');
    if (dateFilter) dateFilter.value = "";

    // 3. Render ulang tabel menggunakan data asli (allData) tanpa filter
    renderTable(allData);
}

// --- 4. UPDATE DATA ---
function openEdit(id, keperluan, tanggal) {
    document.getElementById("editId").value = id;
    document.getElementById("editKeperluan").value = keperluan;
    document.getElementById("editTanggal").value = tanggal;
    $("#modalEdit").modal("show");
}

document.getElementById("btnUpdate").onclick = async function() {
    const id = document.getElementById("editId").value;
    const body = {
        keperluan: document.getElementById("editKeperluan").value,
        tanggal_kunjungan: document.getElementById("editTanggal").value
    };

    if(!body.keperluan || !body.tanggal_kunjungan) {
        return Swal.fire('Peringatan', 'Semua kolom harus diisi!', 'warning');
    }

    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const res = await fetch(`/api/pengunjung/${id}`, {
            method: "PUT",
            headers: { 
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json",
                "Accept": "application/json" 
            },
            body: JSON.stringify(body)
        });

        if(res.ok) {
            Swal.fire({ icon: 'success', title: 'Berhasil diupdate!', timer: 1500, showConfirmButton: false });
            $("#modalEdit").modal("hide");
            loadData(); // Refresh data utama
        } else {
            throw new Error();
        }
    } catch (err) {
        Swal.fire('Gagal', 'Terjadi kesalahan saat update data.', 'error');
    }
};

// --- 5. DELETE DATA ---
async function hapusData(id) {
    const result = await Swal.fire({
        title: 'Hapus Kunjungan?',
        text: "Data akan dihapus permanen dari sistem.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) {
        try {
            const res = await fetch(`/api/pengunjung/${id}`, {
                method: "DELETE",
                headers: { "Authorization": `Bearer ${token}` }
            });
            if(res.ok) {
                Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                loadData();
            } else {
                Swal.fire('Gagal', 'Anda tidak memiliki akses atau data tidak ditemukan.', 'error');
            }
        } catch (err) {
            Swal.fire('Gagal', 'Gagal menghubungi server.', 'error');
        }
    }
}

// --- 6. HELPER & EXPORT ---
function formatTanggal(tgl) {
    if(!tgl) return '-';
    return new Date(tgl).toLocaleDateString("id-ID", { 
        day: "2-digit", 
        month: "long", 
        year: "numeric" 
    });
}

document.getElementById("btnCetakAnggota").addEventListener("click", async () => {
    Swal.fire({ title: 'Menyiapkan File...', didOpen: () => Swal.showLoading() });
    try {
        const res = await fetch("/api/pengunjung/export", { 
            headers: { "Authorization": `Bearer ${token}` } 
        });
        const blob = await res.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        const timestamp = new Date().toISOString().split('T')[0];
        
        a.href = url;
        a.download = `laporan-pengunjung-${timestamp}.xlsx`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        Swal.close();
    } catch (err) {
        Swal.fire('Gagal Export', 'Terjadi kesalahan pada server saat mengunduh file.', 'error');
    }
});

</script>
@endsection