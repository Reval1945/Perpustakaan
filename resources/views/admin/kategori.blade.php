@extends('layouts.admin')

@section('title', 'Data Kategori')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" style="color: var(--dark); font-weight: 700;">
        Manajemen Kategori
    </h1>
    <button type="button" class="btn btn-primary btn-main shadow-sm" style="border-radius: 12px; font-weight: 600;" onclick="openModalTambah()">
        <i class="fas fa-plus fa-sm mr-2"></i> Tambah Kategori
    </button>
</div>

<div class="card shadow-sm" style="border: none; border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: #f1f5f9;">
                    <tr>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; width: 60px;">No</th>
                        <th class="py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Nama Kategori</th>
                        <th class="py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Deskripsi</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="kategoriTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="modalKategori" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border: none; border-radius: 16px;">
            <div class="modal-header text-white" style="background: #2C5AA0; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title font-weight-bold" id="modalTitle">Form Kategori</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formKategori">
                <div class="modal-body p-4">
                    <input type="hidden" id="cat_id">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-muted text-uppercase">Nama Kategori</label>
                        <input type="text" id="name" class="form-control" style="border-radius: 10px;" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold small text-muted text-uppercase">Deskripsi</label>
                        <textarea id="deskripsi" class="form-control" style="border-radius: 10px;" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                       <i class="fas fa-save mr-2"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
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
</style>

<script>
const API_URL = 'http://127.0.0.1:8000/api/categories';
const token = localStorage.getItem('token');

document.addEventListener('DOMContentLoaded', function() {
    fetchCategories();

    // Event Delegation untuk tombol Edit & Delete (Lebih Aman)
    document.getElementById('kategoriTableBody').addEventListener('click', function(e) {
        const target = e.target.closest('button');
        if (!target) return;

        if (target.classList.contains('btn-edit')) {
            const data = target.dataset;
            openModalEdit(data.id, data.name, data.deskripsi);
        }

        if (target.classList.contains('btn-delete')) {
            deleteCategory(target.dataset.id);
        }
    });

    // Handle Form Submit
    document.getElementById('formKategori').addEventListener('submit', handleFormSubmit);
});

async function fetchCategories() {
    const tbody = document.getElementById('kategoriTableBody');
    tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-primary">Memuat data...</td></tr>`;
    
    try {
        const res = await fetch(API_URL, {
            headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
        });
        const result = await res.json();
        renderTable(result.data);
    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger py-4">Gagal memuat data.</td></tr>`;
    }
}

function renderTable(categories) {
    const tbody = document.getElementById('kategoriTableBody');
    if (!categories || categories.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-muted">Data kosong.</td></tr>`;
        return;
    }

    tbody.innerHTML = categories.map((cat, index) => `
        <tr>
            <td class="text-center text-muted">${index + 1}</td>
            <td class="font-weight-bold" style="color: var(--dark);">${cat.name}</td>
            <td class="text-secondary">${cat.deskripsi || '-'}</td>
            <td class="text-center">
                <div class="d-flex justify-content-center">
                    <button class="btn btn-light btn-sm btn-edit mr-2" 
                        data-id="${cat.id}" data-name="${cat.name}" data-deskripsi="${cat.deskripsi || ''}"
                        style="border-radius: 8px; border: 1px solid var(--border);">
                        <i class="fas fa-edit text-warning"></i>
                    </button>
                    <button class="btn btn-sm btn-delete" 
                        data-id="${cat.id}"
                        style="background:#fee2e2; color:#ef4444; border-radius:10px; width:35px; height:35px; border:none;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function openModalTambah() {
    document.getElementById('formKategori').reset();
    document.getElementById('cat_id').value = '';
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus mr-2"></i>Tambah Kategori';
    $('#modalKategori').modal('show');
}

function openModalEdit(id, name, desc) {
    document.getElementById('cat_id').value = id;
    document.getElementById('name').value = name;
    document.getElementById('deskripsi').value = desc;
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit mr-2"></i>Edit Kategori';
    $('#modalKategori').modal('show');
}

async function handleFormSubmit(e) {
    e.preventDefault();
    const id = document.getElementById('cat_id').value;
    const payload = {
        name: document.getElementById('name').value,
        deskripsi: document.getElementById('deskripsi').value
    };

    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const method = id ? 'PUT' : 'POST';
        const url = id ? `${API_URL}/${id}` : API_URL;

        const res = await fetch(url, {
            method: method,
            headers: { 
                'Content-Type': 'application/json', 
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}` 
            },
            body: JSON.stringify(payload)
        });

        if (!res.ok) throw new Error();

        Swal.fire({ icon: 'success', title: 'Berhasil!', timer: 1000, showConfirmButton: false });
        $('#modalKategori').modal('hide');
        fetchCategories();
    } catch (err) {
        Swal.fire('Gagal', 'Terjadi kesalahan sistem.', 'error');
    }
}

async function deleteCategory(id) {
    const result = await Swal.fire({
        title: 'Hapus?',
        text: "Data tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) {
        try {
            const res = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (!res.ok) throw new Error();
            Swal.fire('Terhapus!', '', 'success');
            fetchCategories();
        } catch (err) {
            Swal.fire('Error', 'Gagal menghapus.', 'error');
        }
    }
}
</script>
@endsection