@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Tambah Kategori</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="createKategoriForm">
                @csrf

                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           required>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi"
                            class="form-control"
                            rows="3">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="text-right">
                    <a href="{{ url('admin/kategori') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
document.getElementById('createKategoriForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const token = localStorage.getItem('token');

    const data = {
        name: this.name.value,
        deskripsi: this.deskripsi.value
    };

    fetch('http://127.0.0.1:8000/api/categories', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(data)
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal menyimpan');
        return res.json();
    })
    .then(() => {
        alert('Kategori berhasil ditambahkan');
        window.location.href = '/admin/kategori';
    })
    .catch(err => {
        console.error(err);
        alert('Gagal menambahkan kategori');
    });
});
</script>

@endsection
