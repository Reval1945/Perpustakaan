@extends('layouts.admin')

@section('title', 'Daftar Pengunjung')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="text-gray-800">Daftar Pengunjung</h4>
    <div>
        <button id="btnCetakAnggota" class="btn btn-success">
            <i class="fas fa-print"></i> Cetak Pengunjung
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered text-center">
        <thead class="bg-primary text-white text-center">
            <tr>
                <th>Nama</th>
                <th>Kelas</th>
                <th>NISN</th>
                <th>Keperluan</th>
                <th>Tanggal Kunjungan</th>
            </tr>
        </thead>
        <tbody id="pengunjung"></tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {

    const tbody = document.getElementById("pengunjung");
    const token = localStorage.getItem("token");

    if(!token){
        tbody.innerHTML = `<tr><td colspan="5">Silakan login terlebih dahulu</td></tr>`;
        return;
    }

    try{

        const res = await fetch("/api/pengunjung", {
            method: "GET",
            headers: {
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            }
        });

        if(!res.ok) throw new Error("Gagal mengambil data");

        const json = await res.json();
        const data = json.data;

        if(!data || data.length === 0){
            tbody.innerHTML = `<tr><td colspan="5">Belum ada data pengunjung</td></tr>`;
            return;
        }

        let rows = "";

        data.forEach(item => {
            rows += `
                <tr>
                    <td>${item.nama}</td>
                    <td>${item.kelas}</td>
                    <td>${item.nisn}</td>
                    <td>${item.keperluan}</td>
                    <td>${formatTanggal(item.tanggal_kunjungan)}</td>
                </tr>
            `;
        });

        tbody.innerHTML = rows;

    }catch(err){
        tbody.innerHTML = `<tr><td colspan="5">${err.message}</td></tr>`;
    }
});


function formatTanggal(tgl){
    const d = new Date(tgl);
    return d.toLocaleDateString("id-ID", {
        day:"2-digit",
        month:"long",
        year:"numeric"
    });
}


// tombol print
document.getElementById("btnCetakAnggota")
.addEventListener("click", () => window.print());
</script>

@endsection