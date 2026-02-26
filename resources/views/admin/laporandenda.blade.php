@extends('layouts.admin')

@section('title', 'Laporan Denda')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 text-gray-800">Laporan Denda</h1>
        <button class="btn btn-primary">
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
                    <th width="130">Tggl Pinjam</th>
                    <th width="130">Tggl Kembali</th>
                    <th width="110">Jenis Denda</th>
                    <th width="110">Telat</th>
                    <th width="110">Denda</th>
                    <th width="130">Status Denda</th>
                    <th width="110">Aksi</th>
                </tr>
            </thead>

            <tbody id="table-denda">
                <tr>
                    <td colspan="9" class="text-center">Loading...</td>
                </tr>
            </tbody>
        </table>

        <!-- MODEL EDIT DENDA -->
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

</div>

<script>
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
        window.dataDenda = result.data; // simpan global

        let tbody = document.getElementById("table-denda");
        tbody.innerHTML = "";

        if (!window.dataDenda || window.dataDenda.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data denda</td>
                </tr>
            `;
            return;
        }

        window.dataDenda.forEach((item, index) => {

            let judul = item.judul_buku ?? "-";

            let tglPinjam = item.transaction?.tanggal_pinjam
                ? new Date(item.transaction.tanggal_pinjam).toLocaleDateString('id-ID')
                : "-";

            let tglKembali = item.tanggal_kembali
                ? new Date(item.tanggal_kembali).toLocaleDateString('id-ID')
                : "-";

            let telat = item.jumlah_hari_telat > 0
                ? `${item.jumlah_hari_telat} hari`
                : "Tidak terlambat";

            let denda = parseFloat(item.denda).toLocaleString('id-ID', {
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
                    <td>${judul}</td>
                    <td class="text-center">${tglPinjam}</td>
                    <td class="text-center">${tglKembali}</td>
                    <td class="text-center">${item.jenis_denda ?? '-'}</td>
                    <td class="text-center text-danger">${telat}</td>
                    <td class="text-center">${denda}</td>
                    <td class="text-center">${statusBadge}</td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm btn-edit-denda" data-id="${item.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    })
    .catch(err => {
        console.error(err);
        document.getElementById("table-denda").innerHTML = `
            <tr>
                <td colspan="9" class="text-center text-danger">
                    Gagal memuat data denda
                </td>
            </tr>
        `;
    });
}

document.addEventListener("DOMContentLoaded", loadDenda);
</script>

<script>
document.addEventListener("click", function(e) {
    const btn = e.target.closest(".btn-edit-denda");
    if (!btn) return;

    const id = btn.dataset.id;

    const item = window.dataDenda.find(d => d.id == id);

    document.getElementById("denda_id").value = id;
    document.getElementById("status_denda").value = item.status_denda;

    $("#modalEditDenda").modal("show");
});
</script>

<script>
document.getElementById("btnUpdateDenda").addEventListener("click", function() {
    const id = document.getElementById("denda_id").value;
    const status_denda = document.getElementById("status_denda").value;
    const token = localStorage.getItem("token");

    if (!id) {
        alert("ID denda tidak ditemukan!");
        return;
    }

    fetch(`http://127.0.0.1:8000/api/denda/details/${id}`, {
        method: "PUT",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token,
            "X-Application": "perpus-admin"
        },
        body: JSON.stringify({
            status_denda: status_denda
        })
    })
    .then(res => res.json())
    .then(result => {
        if (result.message) {
            alert(result.message);
            $("#modalEditDenda").modal("hide");
            loadDenda(); // reload tabel
        } else {
            alert("Update gagal!");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi kesalahan saat update denda");
    });
});
</script>



@endsection
