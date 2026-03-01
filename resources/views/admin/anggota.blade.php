@extends('layouts.admin')

@section('title', 'Data Anggota')

@section('content')
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

    /* Content Header */
    .content-header {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border);
    }

    .content-header h4 {
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }

    /* Table */
    .table-responsive {
        background: white;
        border-radius: 16px;
        padding: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead tr {
        border-radius: 12px;
        overflow: hidden;
    }

    .table thead th {
        background: var(--primary) !important;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
        border: none;
        white-space: nowrap;
    }

    .table thead th:first-child {
        border-radius: 12px 0 0 12px;
    }

    .table thead th:last-child {
        border-radius: 0 12px 12px 0;
    }

    .table tbody tr {
        transition: all 0.2s;
    }

    .table tbody tr:hover {
        background: var(--primary-soft);
    }

    .table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        color: var(--dark);
        font-size: 0.9rem;
        border-bottom: 1px solid var(--border);
    }

    /* Badge */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-weight: 500;
        font-size: 0.75rem;
        letter-spacing: 0.3px;
    }

    .badge-success {
        background: var(--success);
        color: white;
    }

    /* Form Controls */
    .form-group {
        margin-bottom: 1.2rem;
    }

    .form-group label {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.85rem;
        margin-bottom: 0.4rem;
        display: block;
    }

    .form-group label i {
        color: var(--primary);
        font-size: 0.9rem;
    }

    .form-control {
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 0.7rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
        background: white;
    }

    .form-control:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(44,90,160,0.1);
    }

    .form-control-lg {
        padding: 0.9rem 1.2rem;
        font-size: 1rem;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1rem;
    }

    /* Input Group */
    .input-group {
        border-radius: 12px;
        overflow: hidden;
    }

    .input-group .form-control {
        border-right: none;
    }

    .input-group-append .btn {
        border: 1px solid var(--border);
        border-left: none;
        border-radius: 0 12px 12px 0;
        background: white;
        color: var(--gray);
        padding: 0.7rem 1rem;
    }

    .input-group-append .btn:hover {
        background: var(--gray-light);
        color: var(--primary);
    }

    /* Action Buttons Container */
    .action-buttons {
        display: flex;
        gap: 0.4rem;
        justify-content: center;
    }

    /* Form Row */
    .form-row {
        margin-left: -0.5rem;
        margin-right: -0.5rem;
    }

    .form-row > .col-md-6 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
</style>

<div class="content-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
    <div class="mb-3 mb-md-0">
        <h4 class="text-gray-800">
            <i class="fas fa-users mr-2" style="color: var(--primary);"></i>
            Daftar Anggota
        </h4>
    </div>
    
    <div class="d-flex flex-column flex-sm-row shadow-sm" style="border-radius: 30px; background: #f8fafc; padding: 5px; border: 1px solid var(--border);">
        <div class="input-group" style="width: 300px;">
            <div class="input-group-prepend">
                <span class="input-group-text border-0 bg-transparent">
                    <i class="fas fa-search text-gray"></i>
                </span>
            </div>
            <input type="text" id="searchInput" class="form-control border-0 bg-transparent" placeholder="Cari Nama, NISN, atau Kelas" style="box-shadow: none;">
        </div>
    </div>

    <div class="mt-3 mt-md-0">
        <button id="btnTambahAnggota" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahAnggota">
            <i class="fas fa-plus mr-1"></i> Tambah Anggota
        </button>
        <button id="btnCetakAnggota" class="btn btn-success">
            <i class="fas fa-print mr-1"></i> Cetak Anggota
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="table text-center">
        <thead>
            <tr>
                <th>Kode User</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>NISN</th>
                <th>Email</th>
                <th>No Telepon</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan diisi oleh JavaScript -->
        </tbody>
    </table>
</div>

<!-- Modal Tambah Anggota -->
<div class="modal fade" id="modalTambahAnggota" tabindex="-1" role="dialog" aria-labelledby="modalTambahAnggotaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="modalTambahAnggotaLabel">
                    <i class="fas fa-user-plus mr-2"></i> Tambah/Edit Anggota
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span class="text-white">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <form>
                    <input type="hidden" id="user_id">

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-user mr-1 text-primary"></i> Nama Lengkap
                        </label>
                        <input id="name" type="text" class="form-control form-control-lg" placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="form-row">
                        <!-- NISN -->
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">
                                <i class="fas fa-id-card mr-1 text-primary"></i> NISN
                            </label>
                            <input id="nisn" type="number" class="form-control" placeholder="Masukkan NISN">
                            <small class="text-muted">Opsional</small>
                        </div>

                        <!-- No Absen -->
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">
                                <i class="fas fa-list-ol mr-1 text-primary"></i> No Absen
                            </label>
                            <input id="roll_number" type="number" class="form-control" placeholder="Nomor absen">
                            <small class="text-muted">Opsional</small>
                        </div>
                    </div>

                    <!-- Kelas -->
                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-school mr-1 text-primary"></i> Kelas
                        </label>
                        <input id="class" type="text" class="form-control" placeholder="Contoh: XI RPL 1">
                        <small class="text-muted">Opsional</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-user-tag mr-1 text-primary"></i> Role
                        </label>
                        <select id="role" class="form-control">
                            <option value="">-- Pilih Role --</option>
                            <option value="user">Anggota</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-phone mr-1 text-primary"></i> No Telepon
                        </label>
                        <input id="phone" type="text" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-envelope mr-1 text-primary"></i> Email
                        </label>
                        <input id="email" type="email" class="form-control" placeholder="contoh@email.com">
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-lock mr-1 text-primary"></i> Password
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="passwordInput" placeholder="Masukkan password">
                            <div class="input-group-append">
                                <button class="btn" type="button" id="togglePassword">
                                    <i class="fas fa-eye-slash" id="iconPassword"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button id="btnSimpanUser" type="button" class="btn btn-primary px-4">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>

        </div>
    </div>
</div>

<!-- FETCH DATA USER -->
<script>
let semuaUsers = [];

document.addEventListener('DOMContentLoaded', () => {
    fetchUsers();

    const searchInputAnggota = document.getElementById('searchInput');
    if(searchInputAnggota){
        searchInputAnggota.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            const filteredUsers = semuaUsers.filter(user =>
                user.name.toLowerCase().includes(keyword) ||
                (user.roll_number ?? '').toString().includes(keyword) ||
                (user.nisn ?? '').toString().includes(keyword) ||
                (user.kode_user ?? '').toLowerCase().includes(keyword) ||
                (user.class ?? '').toLowerCase().includes(keyword) ||
                (user.email ?? '').toLowerCase().includes(keyword)
            );
            renderTable(filteredUsers);
        });
    }
});

function fetchUsers() {
    const token = localStorage.getItem('token');
    fetch('http://127.0.0.1:8000/api/users', {
        headers: { 
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    })
    .then(res => res.json())
    .then(res => {
        semuaUsers = res.data.filter(u => u.role === 'user'); // hanya user
        renderTable(semuaUsers);
    });
}

document.getElementById('searchInput')?.addEventListener('input', function() {
    const keyword = this.value.toLowerCase();

    const filteredUsers = semuaUsers.filter(user =>
        user.name.toLowerCase().includes(keyword) ||
        (user.roll_number ?? '').toString().includes(keyword) ||
        (user.kode_user ?? '').toLowerCase().includes(keyword) ||
        (user.class ?? '').toLowerCase().includes(keyword) ||
        (user.email ?? '').toLowerCase().includes(keyword)
    );

    renderTable(filteredUsers);
});

function renderTable(users) {
    const tbody = document.querySelector('tbody');
    tbody.innerHTML = '';

    users
        .filter(user => user.role === 'user') // hanya role user
        .forEach(user => {
            tbody.innerHTML += `
                <tr>
                    <td><span style="font-weight: 600; color: var(--primary);">${user.kode_user}</span></td>
                    <td>${user.name}</td>
                    <td>${user.class ?? '-'}</td>
                    <td>${user.nisn ?? '-'}</td>
                    <td>${user.email}</td>
                    <td>${user.phone ?? '-'}</td>
                    <td>
                        <span class="badge badge-success">
                            ${user.role}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button 
                                type="button"
                                class="btn btn-warning btn-sm btn-edit"
                                data-id="${user.id}"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button 
                                type="button"
                                class="btn btn-danger btn-sm btn-delete"
                                data-id="${user.id}"
                                title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
}

</script>

<!-- FETCH CRUD USER -->
<!-- UPDATE -->
<script>
    document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-edit');
    if (!btn) return;

    const id = btn.dataset.id;
    const token = localStorage.getItem('token');

    fetch(`http://127.0.0.1:8000/api/users/${id}`, {
        headers: {
            'Authorization': `Bearer ${token}`
        }
    })
    .then(res => res.json())
    .then(res => {
        const user = res.data;

        document.getElementById('user_id').value = user.id;
        document.getElementById('name').value = user.name;
        document.getElementById('email').value = user.email;
        document.getElementById('role').value = user.role;
        document.getElementById('class').value = user.class ?? '';
        document.getElementById('nisn').value = user.nisn;
        document.getElementById('roll_number').value = user.roll_number ?? '';
        document.getElementById('phone').value = user.phone ?? '';

        $('#modalTambahAnggota').modal('show');
    });
});

</script>

<!-- DELETE -->
<script>
document.getElementById('btnTambahAnggota').addEventListener('click', () => {
    document.getElementById('user_id').value = '';
    document.querySelector('#modalTambahAnggota form').reset();
    $('#modalTambahAnggota').modal('show');
});
document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete');
        if (!btn) return;

        console.log('DELETE CLICKED');

        const id = btn.dataset.id;
        const token = localStorage.getItem('token');

        if (!confirm('Yakin ingin menghapus user ini?')) return;

        fetch(`http://127.0.0.1:8000/api/users/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        })
        .then(res => {
            console.log('STATUS:', res.status);
            if (!res.ok) throw new Error('Gagal hapus');
            fetchUsers();
        })
        .catch(err => {
            console.error(err);
            alert('Gagal menghapus user');
        });
    });

});
</script>

<!-- CREATE AND SAVE -->
<script>
    document.getElementById('btnSimpanUser').addEventListener('click', () => {
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
        password: document.getElementById('passwordInput').value
    };

    const url = id
        ? `http://127.0.0.1:8000/api/users/${id}`
        : `http://127.0.0.1:8000/api/users`;

    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(data)
    })
    .then(() => {
        $('#modalTambahAnggota').modal('hide');
        fetchUsers();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi toggle password untuk modal
    initPasswordToggle();
    
    // Re-inisialisasi ketika modal dibuka (untuk mengatasi jika DOM berubah)
    $('#modalTambahAnggota').on('shown.bs.modal', function() {
        initPasswordToggle();
    });
});

function initPasswordToggle() {
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#passwordInput');
    const iconPassword = document.querySelector('#iconPassword');
    
    if (togglePassword && passwordInput && iconPassword) {
        // Hapus event listener lama dengan mengganti elemen baru
        const newTogglePassword = togglePassword.cloneNode(true);
        togglePassword.parentNode.replaceChild(newTogglePassword, togglePassword);
        
        // Dapatkan referensi baru
        const newToggle = document.querySelector('#togglePassword');
        const newIcon = document.querySelector('#iconPassword');
        const newInput = document.querySelector('#passwordInput');
        
        // Tambah event listener baru
        newToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (newInput.type === 'password') {
                newInput.type = 'text';
                newIcon.classList.remove('fa-eye-slash');
                newIcon.classList.add('fa-eye');
            } else {
                newInput.type = 'password';
                newIcon.classList.remove('fa-eye');
                newIcon.classList.add('fa-eye-slash');
            }
        });
    }
}
</script>

<!-- CETAK -->
<script>
// hanya cetak anggota dengan role "user"
document.getElementById('btnCetakAnggota').addEventListener('click', () => {
    const token = localStorage.getItem('token');
    // tambahkan query param role=user supaya backend hanya mengeluarkan data role user
    const url = 'http://127.0.0.1:8000/api/users/export/excel?role=user';

    fetch(url, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`
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
        a.download = 'data-anggota.xlsx';
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
    })
    .catch(err => {
        console.error(err);
        alert('Gagal mencetak data anggota');
    });
});
</script>
@endsection