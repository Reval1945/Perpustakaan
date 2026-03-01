@extends('layouts.admin')

@section('title', 'Laporan Denda')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0 text-gray-800">Laporan Denda</h1>
    <button class="btn btn-success" onclick="cetakLaporan()">
        <i class="fas fa-print"></i> Cetak Laporan
    </button>
</div>

<div class="card shadow">
        <table class="table table-bordered table-hover align-middle">
            <thead class="bg-primary text-white text-center">
                <tr>
                    <th width="50">No</th>
                    <th>Nama</th>
                    <th>Judul Buku</th>
                    <th>Tggl Pinjam</th>
                    <th>Tggl Kembali</th>
                    <th>Jenis Denda</th>
                    <th>Telat</th>
                    <th>Denda</th>
                    <th>Status Denda</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="table-denda">
                <tr>
                    <td colspan="10" class="text-center">Loading...</td>
                </tr>
            </tbody>
        </table>
</div>

<div class="modal fade" id="modalEditDenda" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Status Denda</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="denda_id">
                <div class="form-group">
                    <label>Status Denda</label>
                    <select id="status_denda" class="form-control">
                        <option value="belum_lunas">Belum Lunas</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="btnUpdateDenda">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
// Fungsi Format Tanggal Sesuai Permintaan
function formatTanggal(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
}

function loadDenda() {
    const token = localStorage.getItem("token");

    fetch("http://127.0.0.1:8000/api/denda/details", {
        method: "GET",
        headers: {
            "Accept": "application/json",
            "Authorization": "Bearer " + token,
            "X-Application": "perpus-admin"
        }
    })
    .then(res => res.json())
    .then(result => {
        window.dataDenda = result.data; 
        let tbody = document.getElementById("table-denda");
        tbody.innerHTML = "";

        if (!window.dataDenda || window.dataDenda.length === 0) {
            tbody.innerHTML = `<tr><td colspan="10" class="text-center">Tidak ada data denda</td></tr>`;
            return;
        }

        window.dataDenda.forEach((item, index) => {
            // Menggunakan fungsi formatTanggal
            let tglPinjam = formatTanggal(item.transaction?.tanggal_pinjam);
            let tglKembali = formatTanggal(item.tanggal_kembali);

            // Ambil jumlah hari telat dari data database (jika ada)
            let hariTelat = parseInt(item.jumlah_hari_telat) || 0;

            let telatBadge = (hariTelat > 0)
                ? `<span class="text-danger" style="font-weight: bold;">${hariTelat} hari</span>`
                : `<span class="text-success">Tepat waktu</span>`;

            let dendaFormatted = parseFloat(item.denda).toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });

            let statusBadge = item.status_denda === "lunas"
                ? `<span class="badge badge-success px-2">Lunas</span>`
                : `<span class="badge badge-danger px-2">Belum Lunas</span>`;

            tbody.innerHTML += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${item.transaction?.user?.name ?? '-'}</td>
                    <td>${item.judul_buku ?? "-"}</td>
                    <td class="text-center">${tglPinjam}</td>
                    <td class="text-center">${tglKembali}</td>
                    <td class="text-center">${item.jenis_denda ?? '-'}</td>
                    <td class="text-center">${telatBadge}</td>
                    <td class="text-center" style="font-weight: bold;">${dendaFormatted}</td>
                    <td class="text-center">${statusBadge}</td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm btn-edit-denda" data-id="${item.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-info btn-sm" onclick="cetakDendaSatu('${item.id}')" title="Cetak Baris Ini">
                            <i class="fas fa-print"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    })
    .catch(err => {
        console.error(err);
        document.getElementById("table-denda").innerHTML = `
            <tr><td colspan="10" class="text-center text-danger">Gagal memuat data</td></tr>
        `;
    });
}

// Event Delegation untuk tombol Edit
document.addEventListener("click", function(e) {
    const btn = e.target.closest(".btn-edit-denda");
    if (!btn) return;

    const id = btn.dataset.id;
    const item = window.dataDenda.find(d => d.id == id);

    document.getElementById("denda_id").value = id;
    document.getElementById("status_denda").value = item.status_denda;
    $("#modalEditDenda").modal("show");
});

// Update Status Denda
document.getElementById("btnUpdateDenda").addEventListener("click", function() {
    const id = document.getElementById("denda_id").value;
    const status_denda = document.getElementById("status_denda").value;
    const token = localStorage.getItem("token");

    fetch(`http://127.0.0.1:8000/api/denda/details/${id}`, {
        method: "PUT",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token,
            "X-Application": "perpus-admin"
        },
        body: JSON.stringify({ status_denda: status_denda })
    })
    .then(res => res.json())
    .then(result => {
        alert(result.message || "Berhasil diperbarui");
        $("#modalEditDenda").modal("hide");
        loadDenda();
    })
    .catch(err => alert("Terjadi kesalahan"));
});

// Cetak Laporan
function cetakLaporan() {
    fetch("http://127.0.0.1:8000/api/laporan/denda/excel", {
        headers: {
            "Authorization": "Bearer " + localStorage.getItem("token"),
        }
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "laporan-denda.xlsx";
        document.body.appendChild(a);
        a.click();
        a.remove();
    })
    .catch(err => console.error(err));
}

// Cetak Denda Per ID
function cetakDendaSatu(id) {
    const token = localStorage.getItem("token");
    
    if (!id) {
        alert("ID Denda tidak ditemukan");
        return;
    }

    fetch(`http://127.0.0.1:8000/api/laporan/denda/${id}`, {
        method: "GET",
        headers: {
            "Accept": "application/pdf",
            "Authorization": "Bearer " + token,
            "X-Application": "perpus-admin"
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Gagal mengunduh PDF");
        }
        return response.blob();
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.href = url;
        link.download = `laporan-denda-${id}.pdf`;
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(url);
    })
    .catch(err => {
        console.error(err);
        alert("Gagal mencetak denda: " + err.message);
    });
}

document.addEventListener("DOMContentLoaded", loadDenda);
</script>

@endsection