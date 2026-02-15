@extends('layouts.superadmin')

@section('title', 'Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="text-gray-800">Daftar Admin</h4>
    <div>
        <button class="btn btn-success mr-2" id="btnExportAdmin">
            <i class="fas fa-print"></i> Cetak Daftar Admin
        </button>
        <button class="btn btn-primary" onclick="showTambahModal()">
            <i class="fas fa-plus"></i> Tambah Admin
        </button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="bg-primary text-white">
                <tr>
                    <th>No</th>
                    <th>Nama Admin</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody id="admin-table-body">
                <tr><td colspan="5">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Admin -->
<div class="modal fade" id="adminModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTitle">
          <i class="fas fa-user-shield mr-2"></i><span id="modalTitleText">Tambah Admin Baru</span>
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="adminId">
        
        <div class="form-group">
            <label for="adminName" class="font-weight-bold">
                <i class="fas fa-user mr-1"></i> Nama Lengkap
                <span class="text-danger">*</span>
            </label>
            <input type="text" id="adminName" class="form-control" placeholder="Masukkan nama lengkap admin">
            <div class="invalid-feedback">Nama tidak boleh kosong</div>
        </div>
        
        <div class="form-group">
            <label for="adminEmail" class="font-weight-bold">
                <i class="fas fa-envelope mr-1"></i> Alamat Email
                <span class="text-danger">*</span>
            </label>
            <input type="email" id="adminEmail" class="form-control" placeholder="contoh: admin@example.com">
            <div class="invalid-feedback">Email tidak valid</div>
            <small class="form-text text-muted">Email akan digunakan untuk login</small>
        </div>
        
        <div class="form-group" id="passwordWrapper">
            <label for="adminPassword" class="font-weight-bold">
                <i class="fas fa-lock mr-1"></i> Password
                <span id="passwordRequired" class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input type="password" id="adminPassword" class="form-control" placeholder="Masukkan password">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                        <i class="fas fa-eye" id="passwordIcon"></i>
                    </button>
                </div>
            </div>
            <div class="invalid-feedback">Password minimal 6 karakter</div>
            <small class="form-text text-muted" id="passwordHint">
                <i class="fas fa-info-circle mr-1"></i>
                <span>Minimal 6 karakter</span>
            </small>
        </div>
        
        <div class="form-group">
            <label for="adminRole" class="font-weight-bold">
                <i class="fas fa-user-tag mr-1"></i> Role
                <span class="text-danger">*</span>
            </label>
            <select id="adminRole" class="form-control">
                <option value="admin">Admin</option>
                <option value="superadmin">Super Admin</option>
            </select>
            <small class="form-text text-muted">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Super Admin memiliki akses penuh ke semua fitur sistem
            </small>
        </div>
        
        <div id="editNote" class="alert alert-info d-none mt-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-pencil-alt mr-2"></i>
                <div>
                    <strong class="d-block">Catatan:</strong>
                    <span class="small">Kosongkan password jika tidak ingin mengubahnya</span>
                </div>
            </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-2"></i> Batal
        </button>
        <button type="button" class="btn btn-primary" onclick="submitAdmin()" id="submitBtn">
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

// Load semua admin
// Load semua admin dari API
async function loadAdmin(searchTerm = '') {
    const tbody = document.getElementById('admin-table-body');
    tbody.innerHTML = '<tr><td colspan="5">Loading...</td></tr>';

    try {
        const token = localStorage.getItem('token');
        if (!token) throw new Error('Token tidak ditemukan');

        let url = '/api/admins';
        if (searchTerm) url += '?search=' + encodeURIComponent(searchTerm);

        const res = await fetch(url, {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            const text = await res.text();
            tbody.innerHTML = `<tr><td colspan="5">Error ${res.status}: ${text}</td></tr>`;
            return;
        }

        const result = await res.json();
        const admins = result.data || [];

        // Simpan ke semuaAdmin agar search filter bisa digunakan
        semuaAdmin = admins;

        if (admins.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5">Data tidak ditemukan</td></tr>';
            return;
        }

        renderTabelAdmin(admins);

    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="5">Terjadi kesalahan: ${err.message}</td></tr>`;
    }
}

// Render table admin
function renderTabelAdmin(admins) {
    const tbody = document.getElementById('admin-table-body');
    tbody.innerHTML = '';

    if (admins.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5">Data tidak ditemukan</td></tr>';
        return;
    }

    admins.forEach((admin, index) => {
        tbody.innerHTML += `<tr>
            <td>${index + 1}</td>
            <td>${admin.name}</td>
            <td>${admin.email}</td>
            <td>${admin.role}</td>
            <td>
                <button class="btn btn-warning btn-sm" onclick="showEditModal('${admin.id}')">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="hapusAdmin('${admin.id}')">Hapus</button>
            </td>
        </tr>`;
    });
}

// Search bar filter front-end
document.addEventListener('input', function(e) {
    if (e.target.id === 'searchInput') {
        const keyword = e.target.value.toLowerCase();
        const hasilFilter = semuaAdmin.filter(admin => {
            return admin.name.toLowerCase().includes(keyword)
                || admin.email.toLowerCase().includes(keyword)
                || (admin.kode_user && admin.kode_user.toLowerCase().includes(keyword));
        });
        renderTabelAdmin(hasilFilter);
    }
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
    $('#passwordHint').html('<i class="fas fa-info-circle mr-1"></i><span>Password minimal 6 karakter</span>');
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
    
    // Validasi
    if (!validateForm(name, email, password, isEdit)) {
        return;
    }

    // Loading state
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
    submitBtn.disabled = true;

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
            const errorMessage = responseData.message || 'Terjadi kesalahan saat menyimpan admin';
            
            // Tampilkan error di form
            if (responseData.errors) {
                Object.keys(responseData.errors).forEach(field => {
                    const fieldId = `admin${field.charAt(0).toUpperCase() + field.slice(1)}`;
                    $(`#${fieldId}`).addClass('is-invalid');
                });
                alert(responseData.errors[Object.keys(responseData.errors)[0]][0]);
            } else {
                alert(errorMessage);
            }
            
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return;
        }

        // Sukses
        $('#adminModal').modal('hide');
        loadAdmin();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;

    } catch(err) {
        console.error('Terjadi kesalahan saat submit admin', err);
        alert('Terjadi kesalahan jaringan. Periksa koneksi internet Anda.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

// Hapus admin
async function hapusAdmin(id) {
    const admin = semuaAdmin.find(a => a.id === id);
    if (!admin) return;
    
    if (!confirm(`Yakin ingin menghapus admin "${admin.name}"?`)) return;
    
    const token = localStorage.getItem('token');

    try {
        const res = await fetch('/api/admins/' + id, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            const errorData = await res.json();
            alert(errorData.message || 'Terjadi kesalahan saat menghapus admin');
            return;
        }

        await res.json();
        loadAdmin();
    } catch(err) {
        console.error('Gagal hapus admin', err);
        alert('Terjadi kesalahan saat menghapus admin');
    }
}

// Panggil load saat halaman siap
$(document).ready(function() {
    loadAdmin();
});
</script>
<script>
$(document).ready(function() {
    // Pastikan tombol ada
    const btn = document.getElementById('btnExportAdmin');
    if (!btn) return;

    btn.addEventListener('click', async function() {
        const token = localStorage.getItem('token');
        if (!token) {
            alert('Token tidak ditemukan. Silakan login ulang.');
            return;
        }

        try {
            const res = await fetch('/api/admins/export', {
                method: 'GET',
                headers: {
                    'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Authorization': 'Bearer ' + token
                }
            });

            if (!res.ok) {
                const errorText = await res.text();
                alert('Gagal export: ' + errorText);
                return;
            }

            const blob = await res.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'daftar_admin.xlsx';
            document.body.appendChild(a);
            a.click();
            a.remove();

        } catch (err) {
            console.error(err);
            alert('Terjadi kesalahan jaringan.');
        }
    });
});

</script>
@endsection