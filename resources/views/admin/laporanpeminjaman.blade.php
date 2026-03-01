@extends('layouts.admin')

@section('title', 'Laporan Peminjaman')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 text-gray-800">Laporan Transaksi</h1>
        <button class="btn btn-success" onclick="cetakLaporan()">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="bg-primary text-white text-center">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="laporan-body" class="text-center">
                    <tr>
                        <td colspan="6">Loading data...</td>
                    </tr>
                </tbody>
            </table>
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

        document.addEventListener("DOMContentLoaded", function () {
            fetch("http://127.0.0.1:8000/api/transaction-details", {
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("token"),
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                let tbody = document.getElementById("laporan-body");
                tbody.innerHTML = "";

                if (!data.data || data.data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="6">Tidak ada data transaksi</td></tr>`;
                    return;
                }

                data.data.forEach((item, index) => {
                    let nama = item.transaction?.user?.name ?? '-';
                    let judul = item.judul_buku ?? '-';
                    
                    // Menggunakan fungsi formatTanggal baru
                    let tglPinjam = formatTanggal(item.transaction?.tanggal_pinjam);
                    let tglKembali = item.tanggal_kembali ? formatTanggal(item.tanggal_kembali) : "-";

                    let status = item.status;
                    let badge = "";

                    if (status === "dikembalikan") {
                        badge = `<span class="badge badge-success px-2">Dikembalikan</span>`;
                    } else if (status === "dipinjam") {
                        badge = `<span class="badge badge-warning px-2">Dipinjam</span>`;
                    } else if (status === "terlambat") {
                        badge = `<span class="badge badge-danger px-2">Terlambat</span>`;
                    } else if (status === "menunggu_verifikasi") {
                        badge = `<span class="badge badge-warning px-2">Menunggu Verifikasi</span>`;
                    }

                    tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td class="text-left">${nama}</td>
                            <td class="text-left">${judul}</td>
                            <td>${tglPinjam}</td>
                            <td>${tglKembali}</td>
                            <td>${badge}</td>
                        </tr>
                    `;
                });
            })
            .catch(err => {
                console.error(err);
                document.getElementById("laporan-body").innerHTML = `
                    <tr><td colspan="6" class="text-danger">Gagal memuat data</td></tr>
                `;
            });
        });

        function cetakLaporan() {
            fetch("http://127.0.0.1:8000/api/laporan/peminjaman/excel", {
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("token"),
                }
            })
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = "laporan-peminjaman.xlsx";
                document.body.appendChild(a);
                a.click();
                a.remove();
            })
            .catch(err => console.error(err));
        }
    </script>

@endsection