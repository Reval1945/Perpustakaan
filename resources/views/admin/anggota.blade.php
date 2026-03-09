@extends('layouts.admin')

@section('title', 'Data Anggota')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" style="color: var(--dark); font-weight: 700;">
        Daftar Anggota
    </h1>
    <div class="mt-3 mt-md-0">
        <button id="btnTambahAnggota" class="btn btn-main btn-primary shadow-sm" style="padding: 0.5rem 1.5rem;">
            <i class="fas fa-plus fa-sm mr-2"></i> Tambah Anggota
        </button>
        <button id="btnCetakAnggota" class="btn btn-main btn-success shadow-sm" style="padding: 0.5rem 1.5rem;">
            <i class="fas fa-print fa-sm mr-2"></i> Cetak Daftar Anggota
        </button>
    </div>
</div>

<div class="card shadow-sm mb-4" style="border: none; border-radius: 16px;">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white" style="border-color: var(--border); border-radius: 30px 0 0 30px;">
                            <i class="fas fa-search" style="color: var(--gray);"></i>
                        </span>
                    </div>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari Nama, NISN, atau Kelas..." style="border-color: var(--border); border-radius: 0 30px 30px 0; border-left: none;">
                </div>
            </div>
            <div class="col-md-6 col-lg-8 text-md-right mt-3 mt-md-0">
                <span id="searchResultInfo" style="color: var(--gray); font-size: 0.9rem;">
                    <i class="fas fa-users mr-1"></i> <span id="userCount">0</span> anggota ditemukan
                </span>
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
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; width: 50px;">No</th>
                        <th class="py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Kode</th>
                        <th class="py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Nama Lengkap</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">NISN</th> 
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Kelas</th>
                        <th class="py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Kontak</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="user-table-body">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Anggota -->
<div class="modal fade" id="modalTambahAnggota" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 18px; overflow: hidden;">
            
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <h5 class="modal-title font-weight-bold text-gray-800" id="modalTitle">
                    <i class="fas fa-user-edit text-primary mr-2"></i> Formulir Anggota
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4 pt-2">
                <form id="formAnggota">
                    <input type="hidden" id="user_id">

                    <div class="form-group mb-3">
                        <label class="text-overline text-primary d-block mb-1 font-weight-bold" style="font-size: 0.85rem;">
                            <i class="fas fa-user mr-1"></i> Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="name" class="form-control rounded-pill border-0 bg-light" placeholder="Masukkan nama lengkap" required style="height: 40px;">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-overline text-primary d-block mb-1 font-weight-bold" style="font-size: 0.85rem;">
                                    <i class="fas fa-id-card mr-1"></i> NISN <span class="optional-badge">opsional</span></label>
                                </label>
                                <input type="number" id="nisn" class="form-control rounded-pill border-0 bg-light" placeholder="10 digit NISN" style="height: 40px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-overline text-primary d-block mb-1 font-weight-bold" style="font-size: 0.85rem;">
                                    <i class="fas fa-list-ol mr-1"></i> No Absen <span class="optional-badge">opsional</span></label>
                                </label>
                                <input type="number" id="roll_number" class="form-control rounded-pill border-0 bg-light" placeholder="Contoh: 01" style="height: 40px;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-overline text-primary d-block mb-1 font-weight-bold" style="font-size: 0.85rem;">
                                    <i class="fas fa-school mr-1"></i> Kelas <span class="optional-badge">opsional</span></label>
                                </label>
                                <input type="text" id="class" class="form-control rounded-pill border-0 bg-light" placeholder="Contoh: XI RPL 1" style="height: 40px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-overline text-primary d-block mb-1 font-weight-bold" style="font-size: 0.85rem;">
                                    <i class="fas fa-user-tag mr-1"></i> Role
                                </label>
                                <select id="role" class="form-control rounded-pill border-0 bg-light" style="height: 40px; appearance: none;">
                                    <option value="user" selected>Anggota</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-overline text-primary d-block mb-1 font-weight-bold" style="font-size: 0.85rem;">
                                    <i class="fas fa-phone mr-1"></i> No Telepon
                                </label>
                                <input type="text" id="phone" class="form-control rounded-pill border-0 bg-light" placeholder="08xxxxxxxxxx" style="height: 40px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="text-overline text-primary d-block mb-1 font-weight-bold" style="font-size: 0.85rem;">
                                    <i class="fas fa-envelope mr-1"></i> Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" id="email" class="form-control rounded-pill border-0 bg-light" placeholder="alamat@email.com" required style="height: 40px;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="text-overline text-primary d-block mb-1 font-weight-bold" style="font-size: 0.85rem;">
                            <i class="fas fa-lock mr-1"></i> Password
                        </label>
                        <div class="input-group">
                            <input type="password" id="passwordInput" class="form-control border-0 bg-light shadow-none" placeholder="Masukkan password" style="height: 40px; border-top-left-radius: 50px; border-bottom-left-radius: 50px;">
                            <div class="input-group-append">
                                <button class="btn btn-light border-0 px-3 shadow-none" type="button" id="togglePassword" onclick="togglePwd()" style="height: 40px; border-top-right-radius: 50px; border-bottom-right-radius: 50px; background-color: #f8f9fa;">
                                    <i class="fas fa-eye-slash text-muted" id="iconPassword"></i>
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted ml-3 mt-2" style="font-size: 0.75rem;">
                            * Kosongkan jika tidak ingin mengubah password saat edit.
                        </small>
                    </div>

                </form>
            </div>

            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-secondary rounded px-3 shadow-sm" data-dismiss="modal" style="height: 38px;">
                    Batal
                </button>
                <button type="button" id="btnSimpanUser" class="btn btn-primary rounded px-4 shadow-sm" style="height: 38px;">
                    <i class="fas fa-save mr-1"></i> Simpan Data
                </button>
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
    --danger: #ef4444;
}

.btn-main{
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

.optional-badge {
    color: var(--gray);
    font-size: 10px;
    font-weight: 400;
    margin-left: 6px;
    background: var(--gray-light);
    padding: 2px 6px;
    border-radius: 4px;
    text-transform: lowercase;
}

body { background-color: #f8fafc; }
.form-control { border-radius: 10px; border: 1px solid var(--border); padding: 0.6rem 1rem; }
.form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(44,90,160,0.1); }
.table thead th { border: none; font-weight: 600; }
.table td { vertical-align: middle; border-top: 1px solid var(--border); }
.badge { font-weight: 600; }
.card { transition: transform 0.2s; }
</style>

<script>
let semuaUsers = [];
const API_URL = 'http://127.0.0.1:8000/api/users';

// --- 1. AMBIL DATA (FETCH) ---
async function fetchUsers() {
    const tbody = document.getElementById('user-table-body');
    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-gray">Sinkronisasi data...</p></td></tr>`;
    
    try {
        const token = localStorage.getItem('token');
        const res = await fetch(API_URL, {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const result = await res.json();
        semuaUsers = (result.data || []).filter(u => u.role === 'user');
        renderTable(semuaUsers);
    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger py-4">Gagal memuat data anggota.</td></tr>`;
    }
}

// --- 2. RENDER TABEL ---
function renderTable(users) {
    const tbody = document.getElementById('user-table-body');
    tbody.innerHTML = '';
    document.getElementById('userCount').innerText = users.length;

    if (users.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5 text-gray">Tidak ada data anggota.</td></tr>`;
        return;
    }

    // Tambahkan parameter index di sini
    users.forEach((user, index) => {
        tbody.innerHTML += `
            <tr>
                <td class="text-center align-middle text-muted" style="font-size: 0.9rem;">${index + 1}</td>
                <td class="align-middle">
                    <span class="badge badge-light text-primary p-2" style="font-weight: 700;">${user.kode_user}</span>
                </td>
                <td class="align-middle text-dark font-weight-bold">${user.name}</td>
                <td class="text-center align-middle">
                    <span class="text-muted" style="font-size: 0.9rem; font-family: monospace;" >${user.nisn ?? '-'}</span>
                </td>
                <td class="text-center align-middle">
                    <span class="badge badge-pill border px-3" style="color: var(--gray);">${user.class ?? '-'}</span>
                </td>
                <td class="align-middle">
                    <small>
                        <i class="fas fa-envelope mr-1 text-gray"></i>${user.email}<br>
                        <i class="fas fa-phone mr-1 text-gray"></i>${user.phone ?? '-'}
                    </small>
                </td>
                <td class="text-center align-middle">
                    <button class="btn btn-sm btn-light mr-1 btn-edit" data-id="${user.id}" style="border-radius: 8px; border: 1px solid var(--border);"><i class="fas fa-edit text-warning"></i></button>
                    <button class="btn btn-sm btn-delete" data-id="${user.id}" style="background:#fee2e2;color:#ef4444;border-radius:10px;"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `;
    });
}

// --- 3. SIMPAN & UPDATE ---
document.getElementById('btnSimpanUser').addEventListener('click', async () => {
    const id = document.getElementById('user_id').value;
    const token = localStorage.getItem('token');
    
    const data = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        role: document.getElementById('role').value,
        class: document.getElementById('class').value,
        nisn: document.getElementById('nisn').value,
        roll_number: document.getElementById('roll_number').value,
        phone: document.getElementById('phone').value,
    };

    const pwd = document.getElementById('passwordInput').value;
    if (pwd) data.password = pwd;

    if (!data.name || !data.email) {
        return Swal.fire('Oops!', 'Nama dan Email wajib diisi.', 'warning');
    }

    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const method = id ? 'PUT' : 'POST';
        const url = id ? `${API_URL}/${id}` : API_URL;

        const res = await fetch(url, {
            method: method,
            headers: { 
                'Content-Type': 'application/json', 
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const resData = await res.json();
        if (!res.ok) throw new Error(resData.message || 'Gagal menyimpan data');

        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data telah disimpan.', timer: 1500, showConfirmButton: false });
        $('#modalTambahAnggota').modal('hide');
        fetchUsers();
    } catch (err) {
        Swal.fire('Gagal', err.message, 'error');
    }
});

// --- 4. AMBIL DATA UNTUK EDIT ---
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.btn-edit');
    if (!btn) return;

    const id = btn.dataset.id;
    const token = localStorage.getItem('token');
    
    Swal.fire({ title: 'Memuat data...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const res = await fetch(`${API_URL}/${id}`, { 
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' } 
        });
        const result = await res.json();
        const user = result.data;

        document.getElementById('user_id').value = user.id;
        document.getElementById('name').value = user.name;
        document.getElementById('email').value = user.email;
        document.getElementById('class').value = user.class ?? '';
        document.getElementById('nisn').value = user.nisn ?? '';
        document.getElementById('roll_number').value = user.roll_number ?? '';
        document.getElementById('phone').value = user.phone ?? '';
        document.getElementById('passwordInput').value = ''; 
        
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit mr-2"></i>Edit Data Anggota';
        Swal.close();
        $('#modalTambahAnggota').modal('show');
    } catch (err) {
        Swal.fire('Error', 'Gagal mengambil data anggota', 'error');
    }
});

// --- 5. HAPUS DATA ---
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.btn-delete');
    if (!btn) return;

    const id = btn.dataset.id;
    const token = localStorage.getItem('token');

    const result = await Swal.fire({
        title: 'Hapus Anggota?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) {
        Swal.fire({ title: 'Menghapus...', didOpen: () => Swal.showLoading() });
        try {
            const res = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (!res.ok) throw new Error();
            Swal.fire('Terhapus!', 'Data anggota berhasil dihapus.', 'success');
            fetchUsers();
        } catch (err) { 
            Swal.fire('Gagal', 'Gagal menghapus data.', 'error'); 
        }
    }
});

// --- 7. FUNGSI CETAK EXCEL ---
document.getElementById('btnCetakAnggota').addEventListener('click', async () => {
    const token = localStorage.getItem('token');
    
    Swal.fire({ title: 'Menyiapkan file...', text: 'Mohon tunggu sebentar', didOpen: () => Swal.showLoading() });

    try {
        const res = await fetch('http://127.0.0.1:8000/api/users/export/excel?role=user', {
            method: 'GET',
            headers: { 'Authorization': `Bearer ${token}` }
        });

        if (!res.ok) throw new Error('Gagal export');

        const blob = await res.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `data-anggota-${new Date().getTime()}.xlsx`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
        Swal.close();
    } catch (err) {
        Swal.fire('Gagal Cetak', 'Terjadi kesalahan saat mengekspor data.', 'error');
    }
});

// --- 8. UI EVENT LAINNYA ---
document.getElementById('btnTambahAnggota').addEventListener('click', () => {
    document.getElementById('user_id').value = '';
    document.getElementById('formAnggota').reset();
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus mr-2"></i>Tambah Anggota Baru';
    $('#modalTambahAnggota').modal('show');
});

document.getElementById('searchInput').addEventListener('input', function() {
    const kw = this.value.toLowerCase();
    const filtered = semuaUsers.filter(u => 
        u.name.toLowerCase().includes(kw) || 
        (u.nisn ?? '').toString().includes(kw) ||
        (u.class ?? '').toLowerCase().includes(kw)
    );
    renderTable(filtered);
});

// Jalankan Fetch saat halaman siap
document.addEventListener('DOMContentLoaded', fetchUsers);
</script>
@endsection