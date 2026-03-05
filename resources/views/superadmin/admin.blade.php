@extends('layouts.superadmin')

@section('title', 'Admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" style="color: var(--dark); font-weight: 700;">Daftar Admin</h1>
    <div>
        <button class="btn btn-primary btn-main shadow-sm" onclick="showTambahModal()">
            <i class="fas fa-plus fa-sm mr-2"></i> Tambah Admin
        </button>
        <button class="btn btn-success btn-main shadow-sm" id="btnExportAdmin">
            <i class="fas fa-print fa-sm mr-2"></i> Cetak
        </button>
    </div>
</div>

<!-- Search Bar -->
<div class="card shadow mb-4" style="border: none; border-radius: 16px;">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white" style="border-color: var(--border); border-radius: 30px 0 0 30px;">
                            <i class="fas fa-search" style="color: var(--gray);"></i>
                        </span>
                    </div>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari admin..." style="border-color: var(--border); border-radius: 0 30px 30px 0; border-left: none;">
                </div>
            </div>
            <div class="col-md-6 col-lg-8 text-md-right mt-3 mt-md-0">
                <span id="searchResultInfo" style="color: var(--gray); font-size: 0.9rem;">
                    <i class="fas fa-users mr-1"></i> <span id="totalAdminCount">0</span> admin ditemukan
                </span>
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
                        <th class="align-middle text-center" style="width: 5%; color: var(--gray); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; border-top: none;">No</th>
                        <th class="align-middle" style="color: var(--gray); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; border-top: none;">Nama Admin</th>
                        <th class="align-middle" style="color: var(--gray); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; border-top: none;">Email</th>
                        <th class="align-middle text-center" style="width: 12%; color: var(--gray); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; border-top: none;">Role</th>
                        <th class="align-middle text-center" style="width: 18%; color: var(--gray); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; border-top: none;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="admin-table-body">
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2 mb-0" style="color: var(--gray);">Memuat data admin...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Admin -->
<div class="modal fade" id="adminModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border: none; border-radius: 16px;">
      <div class="modal-header" style="background: var(--primary); border-bottom: none; border-radius: 16px 16px 0 0;">
        <h5 class="modal-title text-white" id="modalTitle">
          <i class="fas fa-user-shield mr-2"></i><span id="modalTitleText">Tambah Admin Baru</span>
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="adminId">
        
        <div class="form-group">
            <label for="adminName" class="font-weight-bold" style="color: var(--dark);">
                <i class="fas fa-user mr-1" style="color: var(--primary);"></i> Nama Lengkap
                <span class="text-danger">*</span>
            </label>
            <input type="text" id="adminName" class="form-control" placeholder="Masukkan nama lengkap admin">
            <div class="invalid-feedback">Nama tidak boleh kosong</div>
        </div>
        
        <div class="form-group">
            <label for="adminEmail" class="font-weight-bold" style="color: var(--dark);">
                <i class="fas fa-envelope mr-1" style="color: var(--primary);"></i> Alamat Email
                <span class="text-danger">*</span>
            </label>
            <input type="email" id="adminEmail" class="form-control" placeholder="contoh: admin@example.com">
            <div class="invalid-feedback">Email tidak valid</div>
            <small class="form-text" style="color: var(--gray);">
                <i class="fas fa-info-circle mr-1"></i>Email akan digunakan untuk login
            </small>
        </div>
        
        <div class="form-group" id="passwordWrapper">
            <label for="adminPassword" class="font-weight-bold" style="color: var(--dark);">
                <i class="fas fa-lock mr-1" style="color: var(--primary);"></i> Password
                <span id="passwordRequired" class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input type="password" id="adminPassword" class="form-control" placeholder="Masukkan password">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()" style="border-color: var(--border);">
                        <i class="fas fa-eye" id="passwordIcon"></i>
                    </button>
                </div>
            </div>
            <div class="invalid-feedback">Password minimal 6 karakter</div>
            <small class="form-text" style="color: var(--gray);" id="passwordHint">
                <i class="fas fa-info-circle mr-1"></i>
                <span>Minimal 6 karakter</span>
            </small>
        </div>
        
        <div class="form-group">
            <label for="adminRole" class="font-weight-bold" style="color: var(--dark);">
                <i class="fas fa-user-tag mr-1" style="color: var(--primary);"></i> Role
                <span class="text-danger">*</span>
            </label>
            <select id="adminRole" class="form-control">
                <option value="admin">Admin</option>
                <option value="superadmin">Super Admin</option>
            </select>
            <small class="form-text" style="color: var(--gray);">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Super Admin memiliki akses penuh ke semua fitur sistem
            </small>
        </div>
        
        <div id="editNote" class="alert d-none mt-3" style="background: var(--primary-soft); border: none; border-radius: 12px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-pencil-alt mr-3" style="color: var(--primary);"></i>
                <div>
                    <strong class="d-block" style="color: var(--dark);">Catatan:</strong>
                    <span style="color: var(--gray); font-size: 0.85rem;">Kosongkan password jika tidak ingin mengubahnya</span>
                </div>
            </div>
        </div>
        
      </div>
      <div class="modal-footer" style="border-top: 1px solid var(--border);">
        <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 12px;">
          <i class="fas fa-times mr-2"></i> Batal
        </button>
        <button type="button" class="btn btn-primary" onclick="submitAdmin()" id="submitBtn" style="border-radius: 12px;">
          <i class="fas fa-save mr-2"></i> Simpan
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
let semuaAdmin = [];
let filteredAdmins = [];

// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('adminPassword');
    const passwordIcon = document.getElementById('passwordIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        passwordIcon.className = 'fas fa-eye';
    }
}

// Update search result info
function updateSearchInfo() {
    const totalCount = semuaAdmin.length;
    const filteredCount = filteredAdmins.length;
    const searchInput = document.getElementById('searchInput').value;
    
    let infoText = '';
    if (searchInput.trim() === '') {
        infoText = `<i class="fas fa-users mr-1"></i> Total <span id="totalAdminCount">${totalCount}</span> admin`;
    } else {
        infoText = `<i class="fas fa-search mr-1"></i> Menampilkan <span id="totalAdminCount">${filteredCount}</span> dari ${totalCount} admin`;
    }
    
    document.getElementById('searchResultInfo').innerHTML = infoText;
}

// Filter admin berdasarkan search
function filterAdmin(searchTerm) {
    if (!searchTerm.trim()) {
        filteredAdmins = [...semuaAdmin];
    } else {
        const keyword = searchTerm.toLowerCase().trim();
        filteredAdmins = semuaAdmin.filter(admin => {
            return admin.name.toLowerCase().includes(keyword) ||
                   admin.email.toLowerCase().includes(keyword) ||
                   (admin.role && admin.role.toLowerCase().includes(keyword));
        });
    }
    
    renderTabelAdmin(filteredAdmins);
    updateSearchInfo();
}

// Load semua admin dari API
async function loadAdmin() {
    const tbody = document.getElementById('admin-table-body');
    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p class="mt-2 mb-0" style="color: var(--gray);">Memuat data admin...</p>
    </td></tr>`;

    try {
        const token = localStorage.getItem('token');
        if (!token) throw new Error('Token tidak ditemukan');

        const res = await fetch('/api/admins', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            const text = await res.text();
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-danger">Error ${res.status}: ${text}</td></tr>`;
            return;
        }

        const result = await res.json();
        semuaAdmin = result.data || [];
        
        // Filter untuk menampilkan hanya admin dan superadmin
        semuaAdmin = semuaAdmin.filter(a => ['admin', 'superadmin'].includes(a.role.toLowerCase()));
        
        // Reset filter
        filteredAdmins = [...semuaAdmin];
        
        if (semuaAdmin.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4" style="color: var(--gray);">Data tidak ditemukan</td></tr>';
            updateSearchInfo();
            return;
        }

        renderTabelAdmin(semuaAdmin);
        updateSearchInfo();

    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-danger">Terjadi kesalahan: ${err.message}</td></tr>`;
    }
}

// Render table admin
function renderTabelAdmin(admins) {
    const tbody = document.getElementById('admin-table-body');
    tbody.innerHTML = '';

    if (admins.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4" style="color: var(--gray);">Data tidak ditemukan</td></tr>';
        return;
    }

    admins.forEach((admin, index) => {
        const roleBadge = admin.role.toLowerCase() === 'superadmin'
            ? '<span class="badge badge-danger" style="font-weight: 500; padding: 0.35rem 0.8rem; border-radius: 30px;">Super Admin</span>'
            : '<span class="badge badge-primary" style="background: var(--primary-soft); color: var(--primary); font-weight: 500; padding: 0.35rem 0.8rem; border-radius: 30px;">Admin</span>';

        tbody.innerHTML += `<tr>
            <td class="align-middle text-center" style="color: var(--gray);">${index + 1}</td>
            <td class="align-middle font-weight-bold" style="color: var(--dark);">${admin.name}</td>
            <td class="align-middle" style="color: var(--gray);">${admin.email}</td>
            <td class="align-middle text-center">${roleBadge}</td>
            <td class="align-middle text-center">
                <button class="btn btn-sm" onclick="showEditModal('${admin.id}')" style="background: #fef3c7; color: #d97706; border: none; border-radius: 30px; padding: 0.25rem 1rem; margin-right: 0.3rem;">
                    <i class="fas fa-edit mr-1"></i>
                </button>
                <button class="btn btn-sm" onclick="hapusAdmin('${admin.id}')" style="background: #fee2e2; color: var(--danger); border: none; border-radius: 30px; padding: 0.25rem 1rem;">
                    <i class="fas fa-trash mr-1"></i>
                </button>
            </td>
        </tr>`;
    });
}

// Search bar dengan debounce untuk performa lebih baik
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        filterAdmin(e.target.value);
    }, 300); // Delay 300ms untuk mengurangi frekuensi filter
});

// Tampilkan modal Tambah
function showTambahModal() {
    $('#modalTitleText').text('Tambah Admin Baru');
    $('#submitBtn').html('<i class="fas fa-plus mr-2"></i> Tambah');
    $('#adminId').val('');
    $('#adminName').val('').focus();
    $('#adminEmail').val('');
    $('#adminPassword').val('');
    $('#passwordWrapper').show();
    $('#passwordRequired').show();
    $('#passwordHint').html('<i class="fas fa-info-circle mr-1"></i><span>Minimal 6 karakter</span>');
    $('#adminRole').val('admin');
    $('#editNote').addClass('d-none');
    
    // Reset validasi
    $('.form-control').removeClass('is-invalid');
    $('.form-control').removeClass('is-valid');
    
    // Reset eye icon
    $('#passwordIcon').removeClass('fa-eye-slash').addClass('fa-eye');
    $('#adminPassword').attr('type', 'password');
    
    $('#adminModal').modal('show');
}

// Tampilkan modal Edit
function showEditModal(id) {
    const admin = semuaAdmin.find(a => a.id === id);
    if (!admin) {
        alert('Data admin tidak ditemukan');
        return;
    }

    $('#modalTitleText').text('Edit Data Admin');
    $('#submitBtn').html('<i class="fas fa-save mr-2"></i> Simpan Perubahan');
    $('#adminId').val(admin.id);
    $('#adminName').val(admin.name).focus();
    $('#adminEmail').val(admin.email);
    $('#adminPassword').val('');
    $('#passwordWrapper').show();
    $('#passwordRequired').hide();
    $('#passwordHint').html('<i class="fas fa-info-circle mr-1"></i><span>Kosongkan jika tidak ingin mengubah password</span>');
    $('#adminRole').val(admin.role);
    $('#editNote').removeClass('d-none');
    
    // Reset validasi
    $('.form-control').removeClass('is-invalid');
    $('.form-control').removeClass('is-valid');
    
    // Reset eye icon
    $('#passwordIcon').removeClass('fa-eye-slash').addClass('fa-eye');
    $('#adminPassword').attr('type', 'password');
    
    $('#adminModal').modal('show');
}

// Validasi form
function validateForm(name, email, password, isEdit) {
    let isValid = true;
    
    // Reset semua error
    $('.form-control').removeClass('is-invalid');
    
    // Validasi nama
    if (!name.trim()) {
        $('#adminName').addClass('is-invalid');
        isValid = false;
    }
    
    // Validasi email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.trim() || !emailRegex.test(email)) {
        $('#adminEmail').addClass('is-invalid');
        isValid = false;
    }
    
    // Validasi password untuk tambah baru
    if (!isEdit && (!password || password.length < 6)) {
        $('#adminPassword').addClass('is-invalid');
        isValid = false;
    }
    
    return isValid;
}

// Submit Tambah / Edit
async function submitAdmin() {
    const id = $('#adminId').val();
    const name = $('#adminName').val();
    const email = $('#adminEmail').val();
    const password = $('#adminPassword').val();
    const role = $('#adminRole').val();
    
    const isEdit = !!id;
    
    // 1. Validasi Frontend
    if (!validateForm(name, email, password, isEdit)) {
        return; // Validasi internal akan memberikan class is-invalid
    }

    // Tampilkan loading SweetAlert
    Swal.fire({
        title: 'Mohon Tunggu',
        text: isEdit ? 'Sedang memperbarui data...' : 'Sedang mendaftarkan admin baru...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const token = localStorage.getItem('token');
    let url = '/api/admins';
    let method = 'POST';
    let body = { name, email, role };

    if (id) {
        url += '/' + id;
        method = 'PUT';
        if (password) body.password = password;
    } else {
        body.password = password;
    }

    try {
        const res = await fetch(url, {
            method,
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(body)
        });

        const responseData = await res.json();

        if (!res.ok) {
            // Jika ada error validasi dari server (misal email sudah terdaftar)
            if (responseData.errors) {
                let errorMessages = Object.values(responseData.errors).flat().join('<br>');
                
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: errorMessages,
                    confirmButtonColor: 'var(--primary)'
                });

                // Tandai field yang error
                Object.keys(responseData.errors).forEach(field => {
                    const fieldId = `admin${field.charAt(0).toUpperCase() + field.slice(1)}`;
                    $(`#${fieldId}`).addClass('is-invalid');
                });
            } else {
                throw new Error(responseData.message || 'Terjadi kesalahan sistem');
            }
            return;
        }

        // 2. Sukses
        Swal.fire({
            icon: 'success',
            title: isEdit ? 'Berhasil Diperbarui' : 'Berhasil Ditambahkan',
            text: `Data admin ${name} telah tersimpan.`,
            timer: 2000,
            showConfirmButton: false
        });

        $('#adminModal').modal('hide');
        await loadAdmin(); // Reload tabel
        
    } catch(err) {
        console.error('Submit error:', err);
        Swal.fire({
            icon: 'error',
            title: 'Ups!',
            text: err.message || 'Terjadi kesalahan jaringan.',
            confirmButtonColor: 'var(--primary)'
        });
    }
}

// Hapus admin
async function hapusAdmin(id) {
    const admin = semuaAdmin.find(a => a.id === id);
    if (!admin) return;
    
    // Konfirmasi modern
    const result = await Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Admin "${admin.name}" akan dihapus permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'var(--danger)',
        cancelButtonColor: 'var(--gray)',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) {
        // Tampilkan loading saat proses hapus
        Swal.fire({
            title: 'Menghapus...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const token = localStorage.getItem('token');
            const res = await fetch('/api/admins/' + id, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            });

            if (!res.ok) throw new Error('Gagal menghapus data');

            Swal.fire({
                icon: 'success',
                title: 'Terhapus!',
                text: 'Admin telah berhasil dihapus.',
                timer: 1500,
                showConfirmButton: false
            });

            await loadAdmin();
        } catch(err) {
            Swal.fire('Error', 'Gagal menghapus data admin.', 'error');
        }
    }
}

// Panggil load saat halaman siap
$(document).ready(function() {
    loadAdmin();
});
</script>
<script>
$(document).ready(function() {
    const btn = document.getElementById('btnExportAdmin');
    if (!btn) return;

    btn.addEventListener('click', async function() {
        const token = localStorage.getItem('token');
        
        if (!token) {
            Swal.fire({
                icon: 'error',
                title: 'Sesi Berakhir',
                text: 'Silakan login ulang untuk melanjutkan.',
                confirmButtonColor: 'var(--primary)'
            });
            return;
        }

        // Tampilkan Loading State
        Swal.fire({
            title: 'Memproses Data',
            text: 'Mohon tunggu sebentar, file sedang disiapkan...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const res = await fetch('/api/admins/export', {
                method: 'GET',
                headers: {
                    'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Authorization': 'Bearer ' + token
                }
            });

            if (!res.ok) {
                throw new Error('Gagal mengunduh file dari server.');
            }

            const blob = await res.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'daftar_admin_' + new Date().toISOString().slice(0,10) + '.xlsx';
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);

            // Tutup loading dan tampilkan sukses
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data admin telah berhasil diekspor ke Excel.',
                timer: 2000,
                showConfirmButton: false
            });

        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Export Gagal',
                text: 'Terjadi kesalahan saat mengekspor data. Pastikan koneksi stabil.',
                confirmButtonColor: 'var(--primary)'
            });
        }
    });
});

</script>

<style>
:root {
    --primary: #2C5AA0;
    --primary-light: #4A7BC8;
    --primary-soft: #e8f0fe;
    --success: #10b981;
    --danger: #ef4444;
    --dark: #1e293b;
    --gray: #64748b;
    --gray-light: #f1f5f9;
    --border: #e2e8f0;
}

/* Form Control Styles */
.form-control {
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 0.6rem 1rem;
    font-size: 0.95rem;
    height: auto;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(44,90,160,0.1);
}

.form-control.is-invalid {
    border-color: var(--danger);
}

.form-control.is-invalid:focus {
    box-shadow: 0 0 0 0.2rem rgba(239,68,68,0.1);
}

.invalid-feedback {
    color: var(--danger);
    font-size: 0.8rem;
}

/* Input Group */
.input-group .input-group-text {
    border-color: var(--border);
    background: white;
}

.input-group .btn-outline-secondary {
    border-color: var(--border);
    background: white;
}

.input-group .btn-outline-secondary:hover {
    background: var(--gray-light);
    border-color: var(--border);
}

.input-group .btn-outline-secondary:focus {
    box-shadow: none;
}

/* Table Styles */
.table {
    color: var(--dark);
}

.table th {
    border-top: none;
    font-weight: 600;
}

.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-color: var(--border);
}

.table-hover tbody tr:hover {
    background: var(--gray-light);
}

.btn-primary:focus {
    box-shadow: 0 0 0 0.2rem rgba(44,90,160,0.3);
}

.btn-main { height: 45px; border-radius: 12px; border: none; font-weight: 600; font-size: 0.85rem; transition: all 0.3s; }

.btn-light {
    background: white;
    border: 1px solid var(--border);
    border-radius: 30px;
    padding: 0.5rem 1.5rem;
    color: var(--gray);
}

.btn-light:hover {
    background: var(--gray-light);
}

/* Badge Styles */
.badge-primary {
    background: var(--primary-soft) !important;
    color: var(--primary) !important;
}

.badge-danger {
    background: #fee2e2 !important;
    color: var(--danger) !important;
}

/* Alert Styles */
.alert {
    background: var(--primary-soft);
    border: none;
    border-radius: 12px;
    padding: 1rem;
}

/* Card Shadow */
.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.05) !important;
}

/* Text Colors */
.text-primary {
    color: var(--primary) !important;
}

.text-danger {
    color: var(--danger) !important;
}

/* Spacing Utilities */
.mr-1 { margin-right: 0.25rem; }
.mr-2 { margin-right: 0.5rem; }
.mr-3 { margin-right: 1rem; }
.mb-0 { margin-bottom: 0; }

/* Font Weights */
.font-weight-bold {
    font-weight: 600 !important;
}

/* Animation */
@keyframes spin {
    to { transform: rotate(360deg); }
}

.fa-spin {
    animation: spin 1s linear infinite;
}

/* Search Info Animation */
#searchResultInfo {
    transition: all 0.3s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .d-sm-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem;
    }
    
    .btn {
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
    }
    
    .input-group {
        width: 100%;
    }
}
</style>
@endsection