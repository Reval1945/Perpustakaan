@extends('layouts.admin')

@section('title', 'Data Anggota')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="text-gray-800">Daftar Anggota</h4>
    <div>
        <button id="btnTambahAnggota" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahAnggota">
            <i class="fas fa-plus"></i> Tambah Anggota
        </button>
        <button id="btnCetakAnggota" class="btn btn-success">
            <i class="fas fa-print"></i> Cetak Anggota
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered text-center">
        <thead class="bg-primary text-white text-center">
            <tr>
                <th>Kode User</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>NISN</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <button 
                        type="button"
                        class="btn btn-warning btn-sm btn-edit"
                        data-id="${user.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button 
                        type="button"
                        class="btn btn-warning btn-sm"
                        data-id="${user.id}">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button 
                        class="btn btn-danger btn-sm btn-delete"
                        data-id="${user.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Anggota -->
<div class="modal fade" id="modalTambahAnggota" tabindex="-1" role="dialog" aria-labelledby="modalTambahAnggotaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="modalTambahAnggotaLabel">
                    <i class="fas fa-user-plus mr-2"></i> Tambah Anggota
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
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
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">
                                <i class="fas fa-id-card mr-1 text-primary"></i> NISN
                            </label>
                            <input id="nisn" type="number" class="form-control" placeholder="Masukkan NISN">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">
                                <i class="fas fa-list-ol mr-1 text-primary"></i> No Absen
                            </label>
                            <input id="roll_number" type="number" class="form-control" placeholder="Nomor absen">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-school mr-1 text-primary"></i> Kelas
                        </label>
                        <input id="class" type="text" class="form-control" placeholder="Contoh: XI RPL 1">
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

                    <!-- Password dengan Icon Mata -->
                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-lock mr-1 text-primary"></i> Password
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="passwordInput" placeholder="Masukkan password">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
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
                    <td>${user.kode_user}</td>
                    <td>${user.name}</td>
                    <td>${user.class ?? '-'}</td>
                    <td>${user.nisn}</td>
                    <td>${user.email}</td>
                    <td>
                        <span class="badge badge-success">
                            ${user.role}
                        </span>
                    </td>
                    <td>
                        <button 
                            type="button"
                            class="btn btn-warning btn-sm btn-edit"
                            data-id="${user.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button 
                            type="button"
                            class="btn btn-danger btn-sm btn-delete"
                            data-id="${user.id}">
                            <i class="fas fa-trash"></i>
                        </button>
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
</script>

<!-- CETAK -->
<script>
document.getElementById('btnCetakAnggota').addEventListener('click', () => {
    const token = localStorage.getItem('token');

    fetch('http://127.0.0.1:8000/api/users/export/excel', {
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
