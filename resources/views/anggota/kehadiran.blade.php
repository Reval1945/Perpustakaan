@extends('layouts.anggota')

@section('title', 'Kehadiran')

@section('content')

<h4 class="text-gray-800 mb-4">Kehadiran Pengunjung</h4>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <strong>Form Kehadiran Perpustakaan</strong>
            </div>

            <div class="card-body">
                <form id="formKehadiran">
                    <!-- Tanggal -->
                    <div class="form-group">
                        <label>Tanggal Kehadiran</label>
                        <input type="text"
                            class="form-control"
                            value="{{ now()->format('d F Y') }}"
                            readonly>
                    </div>

                    <!-- hidden tanggal (now) -->
                    <input type="hidden"
                        id="tanggal_kunjungan"
                        value="{{ now()->toDateString() }}">

                    <!-- Kegiatan -->
                    <div class="form-group">
                        <label>Kegiatan di Perpustakaan</label>
                        <textarea name="keperluan"
                                class="form-control"
                                rows="3"
                                placeholder="Contoh: Membaca buku, mengerjakan tugas, meminjam buku"
                                required></textarea>
                    </div>

                    <!-- BUTTON -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Hadir
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
document.getElementById('formKehadiran').addEventListener('submit', function (e) {
    e.preventDefault();

    const token = localStorage.getItem('token');

    const data = {
        keperluan: document.querySelector('[name="keperluan"]').value,
        tanggal_kunjungan: document.getElementById('tanggal_kunjungan').value
    };

    fetch('http://127.0.0.1:8000/api/pengunjung', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        alert('Kehadiran berhasil dicatat');
        document.getElementById('formKehadiran').reset();
    })
    .catch(err => {
        console.error(err);
        alert('Gagal mencatat kehadiran');
    });
});
</script>


@endsection