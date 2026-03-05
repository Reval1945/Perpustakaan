@extends('layouts.admin')

@section('title', 'Aturan Peminjaman')

@section('content')

<!-- Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" style="color: var(--dark); font-weight: 700;">
        Aturan Peminjamanan
    </h1>
</div>

<div class="row">
    <!-- Form -->
    <div class="col-lg-7">
        <div class="card shadow-sm mb-4" style="border: none; border-radius: 16px;">
            <div class="card-header bg-white py-3" style="border-radius: 16px 16px 0 0; border-bottom: 1px solid var(--gray-light);">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit mr-1"></i> Formulir Pengaturan
                </h6>
            </div>
            <div class="card-body p-4">
                <form id="aturanForm">
                    <input type="hidden" id="aturanId">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Maksimal Pinjam (Hari)</label>
                                <div class="input-group">
                                    <input type="number" id="maksHari" class="form-control" placeholder="7" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-light border-left-0">Hari</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Denda Per Hari</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0">Rp</span>
                                    </div>
                                    <input type="number" id="denda" class="form-control" placeholder="1000" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Status Aturan</label>
                        <select id="aktif" class="form-control custom-select-lg">
                            <option value="1">🟢 Aktif (Gunakan Aturan Ini)</option>
                            <option value="0">🔴 Non-Aktif (Tangguhkan)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Keterangan / Catatan Tambahan</label>
                        <textarea id="keterangan" class="form-control" rows="4" placeholder="Contoh: Aturan ini berlaku untuk semua kategori buku..."></textarea>
                    </div>

                    <hr class="my-4">

                    <button type="submit" class="btn btn-primary btn-block shadow-sm" style="height: 50px; border-radius: 12px; font-weight: 600;">
                        <i class="fas fa-save mr-2"></i> Perbarui Aturan Peminjaman
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview Info -->
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-primary-soft mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; border-radius: 20px;">
                        <i class="fas fa-shield-alt fa-2x text-primary"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark">Ringkasan Aturan</h5>
                    <p class="text-muted small">Detail parameter peminjaman aktif saat ini</p>
                </div>

                <div class="p-3 bg-white mb-3 shadow-sm" style="border-radius: 12px; border-left: 4px solid #2C5AA0;">
                    <small class="text-uppercase text-gray font-weight-bold" style="letter-spacing: 1px; font-size: 0.7rem;">Durasi Maksimal</small>
                    <h3 class="font-weight-bold text-primary mb-0" id="infoHari">-</h3>
                </div>

                <div class="p-3 bg-white mb-4 shadow-sm" style="border-radius: 12px; border-left: 4px solid #1cc88a;">
                    <small class="text-uppercase text-gray font-weight-bold" style="letter-spacing: 1px; font-size: 0.7rem;">Biaya Denda</small>
                    <h3 class="font-weight-bold text-success mb-0" id="infoDenda">-</h3>
                </div>

                <div class="alert alert-info border-0 p-3" style="border-radius: 12px; background: rgba(54, 185, 204, 0.1);">
                    <div class="d-flex">
                        <i class="fas fa-info-circle mr-3 mt-1"></i>
                        <p class="small mb-0 text-dark-50" id="infoAturanText">
                            Memuat narasi aturan...
                        </p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6 class="font-weight-bold text-dark small mb-2 text-uppercase">Log Terakhir:</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock text-gray mr-2"></i>
                        <span class="text-muted small">Diperbarui sistem pada: {{ date('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-soft: #e8f0fe;
    --dark: #1e293b;
    --gray: #64748b;
    --gray-light: #f1f5f9;
    --border: #e2e8f0;
}

.form-control { border-radius: 10px; border: 1px solid var(--border); padding: 0.6rem 1rem; }
.form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(44,90,160,0.1); }
.input-group-text { border: 1px solid var(--border); border-radius: 10px; }
.custom-select-lg { height: calc(2.5rem + 2px); }
.bg-primary-soft { background-color: var(--primary-soft); }
</style>


<script>
const API_URL = 'http://127.0.0.1:8000/api/aturan-peminjaman';
const token = localStorage.getItem('token');

document.addEventListener('DOMContentLoaded', loadAturan);

// --- LOAD DATA ---
async function loadAturan() {
    try {
        const res = await fetch(API_URL, {
            headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
        });
        const result = await res.json();
        
        if (!result.data || result.data.length === 0) return;
        const aturan = result.data[0];

        // Fill Form
        document.getElementById('aturanId').value = aturan.id;
        document.getElementById('maksHari').value = aturan.maks_hari_pinjam;
        document.getElementById('denda').value = parseInt(aturan.denda_per_hari);
        document.getElementById('aktif').value = aturan.aktif ? 1 : 0;
        document.getElementById('keterangan').value = aturan.keterangan ?? '';

        renderInfo(aturan);
    } catch (err) {
        console.error("Gagal memuat aturan:", err);
    }
}

// --- RENDER PREVIEW ---
function renderInfo(aturan) {
    const dendaFormatted = `Rp ${Number(aturan.denda_per_hari).toLocaleString('id-ID')}`;
    
    document.getElementById('infoHari').innerText = `${aturan.maks_hari_pinjam} Hari`;
    document.getElementById('infoDenda').innerText = `${dendaFormatted} / Hari`;
    
    document.getElementById('infoAturanText').innerHTML = `
        Sesuai kebijakan yang aktif, buku harus dikembalikan paling lambat 
        <strong>${aturan.maks_hari_pinjam} hari</strong> setelah peminjaman. 
        Keterlambatan akan dikenakan sanksi denda sebesar 
        <strong>${dendaFormatted}</strong> untuk setiap hari keterlambatan.
    `;
}

// --- SUBMIT DATA ---
document.getElementById('aturanForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id = document.getElementById('aturanId').value;
    const payload = {
        maks_hari_pinjam: document.getElementById('maksHari').value,
        denda_per_hari: document.getElementById('denda').value,
        aktif: Boolean(Number(document.getElementById('aktif').value)),
        keterangan: document.getElementById('keterangan').value
    };

    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const url = id ? `${API_URL}/${id}` : API_URL;
        const method = id ? 'PUT' : 'POST';

        const res = await fetch(url, {
            method,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(payload)
        });

        if (!res.ok) throw new Error('Gagal simpan');

        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Aturan peminjaman telah diperbarui.', timer: 2000, showConfirmButton: false });
        loadAturan();
    } catch (err) {
        Swal.fire('Error', 'Gagal menyimpan perubahan aturan.', 'error');
    }
});
</script>
@endsection