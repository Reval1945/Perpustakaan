@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Edit Kategori</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="editKategoriForm" data-id="{{ $id }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text"
                        name="name"
                        class="form-control"
                        required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi"
                              class="form-control"
                              rows="3">
                    </textarea>
                </div>

                <div class="text-right">
                    <a href="{{ url('admin/kategori') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button class="btn btn-primary">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    loadKategori();
});

function loadKategori() {
    const id = document.getElementById('editKategoriForm').dataset.id;
    const token = localStorage.getItem('token');

    fetch(`http://127.0.0.1:8000/api/categories/${id}`, {
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    })
    .then(res => res.json())
    .then(res => {
        document.querySelector('[name="name"]').value = res.data.name;
        document.querySelector('[name="deskripsi"]').value = res.data.deskripsi ?? '';
    });
}

document.getElementById('editKategoriForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = this.dataset.id;
    const token = localStorage.getItem('token');

    const data = {
        name: this.name.value,
        description: this.deskripsi.value
    };

    fetch(`http://127.0.0.1:8000/api/categories/${id}`, {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(data)
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal update');
        return res.json();
    })
    .then(() => {
        alert('Kategori berhasil diupdate');
        window.location.href = '/admin/kategori';
    })
    .catch(err => {
        console.error(err);
        alert('Gagal update kategori');
    });
});
</script>

@endsection
