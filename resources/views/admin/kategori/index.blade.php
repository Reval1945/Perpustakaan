@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Data Kategori</h1>

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-end">
            <a href="{{ url('admin/kategori/create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="bg-primary text-white" >
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="kategoriTableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- FETCH DATA -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    fetchCategories();
});

function fetchCategories() {
    const token = localStorage.getItem('token');

    fetch('http://127.0.0.1:8000/api/categories', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal fetch kategori');
        return res.json();
    })
    .then(res => {
        renderKategoriTable(res.data);
    })
    .catch(err => {
        console.error(err);
        alert('Gagal mengambil data kategori');
    });
}

function renderKategoriTable(categories) {
    const tbody = document.getElementById('kategoriTableBody');
    tbody.innerHTML = '';

    categories.forEach((cat, index) => {
        tbody.innerHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${cat.name}</td>
                <td>${cat.deskripsi ?? '-'}</td>
                <td class="text-center">
                    <a href="/admin/kategori/${cat.id}/edit" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="/admin/kategori/${cat.id}/edit" class="btn btn-warning btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button 
                        class="btn btn-danger btn-sm btn-delete"
                        data-id="${cat.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
}
</script>

<!-- DELETE -->
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-delete');
    if (!btn) return;

    const id = btn.dataset.id;
    const token = localStorage.getItem('token');

    if (!confirm('Yakin ingin menghapus kategori ini?')) return;

    fetch(`http://127.0.0.1:8000/api/categories/${id}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal hapus');
        fetchCategories();
    })
    .catch(err => {
        console.error(err);
        alert('Gagal menghapus kategori');
    });
});
</script>

@endsection
