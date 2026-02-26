@extends('layouts.anggota')

@section('title', 'Daftar Peminjaman')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="text-gray-800">Daftar Peminjaman</h4>
    <div>
        <button class="btn btn-success" id="btn-cetak">
            <i class="fas fa-print"></i> Cetak Peminjaman
        </button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="bg-primary text-white text-center">
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Sisa Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="peminjaman-body" class="text-center"></tbody>

        </table>
    </div>
</div>
<script>

let semuaTransaksi = [];


// ================= LOAD DATA =================
async function loadTransaksi() {
    try {
        const token = localStorage.getItem('token');

        const res = await fetch('/api/transaksi-me', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error(res.status);

        const result = await res.json();

        // FILTER STATUS PERMANEN
        semuaTransaksi = (result.data || []).map(trx => ({
            ...trx,
            details: trx.details.filter(d =>
                d.status === 'dipinjam' ||
                d.status === 'menunggu_verifikasi'
            )
        })).filter(trx => trx.details.length > 0);

        renderTabel(semuaTransaksi);

    } catch (err) {
        console.error('Gagal ambil transaksi', err);
    }
}


// ================= RENDER =================
function renderTabel(dataTransaksi){

    const tbody = document.querySelector('#peminjaman-body');
    tbody.innerHTML = '';

    if(!dataTransaksi.length){
        tbody.innerHTML=`<tr>
            <td colspan="7" class="text-center">Tidak ada data</td>
        </tr>`;
        return;
    }

    let no = 1;

    function formatTanggal(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    }

    dataTransaksi.forEach(trx=>{
        trx.details.forEach(detail=>{

            const today = new Date();
            const jatuhTempo = new Date(detail.tanggal_jatuh_tempo);
            const selisih = Math.ceil((jatuhTempo - today)/(1000*60*60*24));

            let badgeWaktu='';
            if(selisih>1)
                badgeWaktu=`<span class="badge badge-success">${selisih} hari lagi</span>`;
            else if(selisih===1)
                badgeWaktu=`<span class="badge badge-warning">Tinggal 1 hari</span>`;
            else
                badgeWaktu=`<span class="badge badge-danger">Telat ${Math.abs(selisih)} hari</span>`;

            const badgeStatus =
                detail.status==='dipinjam'
                ? `<span class="badge badge-primary">Dipinjam</span>`
                : `<span class="badge badge-warning">Menunggu Verifikasi</span>`;

            const tombol =
                detail.status==='dipinjam'
                ? `<button class="btn btn-sm btn-warning"
                        onclick="ajukanKembali('${trx.id}','${detail.id}')">
                        Kembalikan
                   </button>`
                : `<button class="btn btn-sm btn-secondary" disabled>
                        Menunggu Verifikasi
                   </button>`;

            tbody.innerHTML += `
            <tr>
                <td>${no++}</td>
                <td>${detail.judul_buku}</td>
                <td>${formatTanggal(trx.tanggal_pinjam)}</td>
                <td>${formatTanggal(detail.tanggal_jatuh_tempo)}</td>
                <td>${badgeWaktu}</td>
                <td>${badgeStatus}</td>
                <td>${tombol}</td>
            </tr>`;
        });
    });
}


// ================= FILTER (SEARCH + TANGGAL) =================
function applyFilter(){

    const keyword =
        document.getElementById('searchInput').value.toLowerCase();

    const tanggal =
        document.getElementById('filterTanggal').value;

    const hasil = semuaTransaksi.map(trx=>({
        ...trx,
        details: trx.details.filter(d=>{

            const cocokJudul =
                d.judul_buku.toLowerCase().includes(keyword);

            const cocokTanggal =
                !tanggal || trx.tanggal_pinjam.startsWith(tanggal);

            return cocokJudul && cocokTanggal;
        })
    })).filter(trx=>trx.details.length>0);

    renderTabel(hasil);
}



// ================= AJUKAN KEMBALI =================
async function ajukanKembali(id, detailId) {

    if(!confirm('Yakin ingin mengembalikan buku ini?')) return;

    const token = localStorage.getItem('token');

    try {
        const res = await fetch(`/api/transaksi-kembali/${id}`, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                detail_ids: [detailId]
            })
        });

        const data = await res.json();
        alert(data.message);

        loadTransaksi();

    } catch(err) {
        console.error(err);
    }
}

document.addEventListener('DOMContentLoaded',()=>{

    loadTransaksi();

    document.getElementById('searchInput')
        ?.addEventListener('input', applyFilter);

    document.getElementById('filterTanggal')
        ?.addEventListener('change', applyFilter);
});

</script>

<!-- Cetak -->
<script>
document.getElementById('btn-cetak').addEventListener('click', () => {
    const token = localStorage.getItem('token');

    if (!token) {
        alert('Silakan login terlebih dahulu');
        return;
    }

    fetch('/api/transaksi-me/export', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
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
        a.download = 'daftar_peminjaman.xlsx';
        document.body.appendChild(a);
        a.click();
        a.remove();
    })
    .catch(err => {
        console.error(err);
        alert('Gagal mencetak data');
    });
});
</script>
@endsection
