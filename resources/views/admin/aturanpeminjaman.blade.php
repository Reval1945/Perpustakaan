@extends('layouts.admin')

@section('title', 'Aturan Peminjaman')

@section('content')

<div class="row">

    <!-- FORM ATURAN -->
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header font-weight-bold">
                Aturan Peminjaman
            </div>

            <div class="card-body">
                <form id="aturanForm">
                    <input type="hidden" id="aturanId">

                    <div class="form-group">
                        <label>Maksimal Lama Peminjaman (Hari)</label>
                        <input type="number" id="maksHari" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Denda Per Hari (Rp)</label>
                        <input type="number" id="denda" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Status Aturan</label>
                        <select id="aktif" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Non Aktif</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea id="keterangan" class="form-control"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Aturan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- KETERANGAN ATURAN -->
    <div class="col-md-6">
        <div class="card shadow border-left-success">
            <div class="card-header font-weight-bold text-success">
                Aturan Peminjaman Saat Ini
            </div>

            <div class="card-body">
                <ul class="mb-0">
                    <li>
                        Maksimal lama peminjaman:
                        <strong id="infoHari">-</strong>
                    </li>
                    <li>
                        Denda keterlambatan:
                        <strong id="infoDenda">-</strong>
                    </li>
                </ul>

                <hr>

                <p class="text-muted mb-0">
                    Buku harus dikembalikan paling lambat
                    <strong id="infoHariText">-</strong>
                    setelah tanggal peminjaman.
                    Jika terlambat, akan dikenakan denda
                    <strong id="infoDendaText">-</strong>
                </p>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    loadAturan();
});

const API_URL = 'http://127.0.0.1:8000/api/aturan-peminjaman';
const token = localStorage.getItem('token');

function loadAturan() {
    fetch(API_URL, {
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    })
    .then(res => res.json())
    .then(res => {
        if (!res.data || res.data.length === 0) return;

        const aturan = res.data[0];

        document.getElementById('aturanId').value = aturan.id;
        document.getElementById('maksHari').value = aturan.maks_hari_pinjam;
        document.getElementById('denda').value = parseInt(aturan.denda_per_hari);
        document.getElementById('aktif').value = aturan.aktif ? 1 : 0;
        document.getElementById('keterangan').value = aturan.keterangan ?? '';

        renderInfo(aturan);
    });
}

function renderInfo(aturan) {
    document.getElementById('infoHari').innerText =
        `${aturan.maks_hari_pinjam} hari`;

    document.getElementById('infoDenda').innerText =
        `Rp ${Number(aturan.denda_per_hari).toLocaleString('id-ID')} / hari`;

    document.getElementById('infoHariText').innerText =
        `${aturan.maks_hari_pinjam} hari`;

    document.getElementById('infoDendaText').innerText =
        `Rp ${Number(aturan.denda_per_hari).toLocaleString('id-ID')} per hari`;
}
</script>

<script>
document.getElementById('aturanForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('aturanId').value;

    const payload = {
        maks_hari_pinjam: document.getElementById('maksHari').value,
        denda_per_hari: document.getElementById('denda').value,
        aktif: Boolean(Number(document.getElementById('aktif').value)),
        keterangan: document.getElementById('keterangan').value
    };

    const url = id ? `${API_URL}/${id}` : API_URL;
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(payload)
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal simpan');
        return res.json();
    })
    .then(() => {
        alert('Aturan berhasil disimpan');
        loadAturan();
    })
    .catch(err => {
        console.error(err);
        alert('Gagal menyimpan aturan');
    });
});
</script>

@endsection
