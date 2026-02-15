@extends('layouts.admin')

@section('title', 'Laporan Peminjaman')

@section('content')

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 text-gray-800">Laporan Peminjaman</h1>
        <button class="btn btn-primary" onclick="cetakLaporan()">
            <i class="fas fa-print"></i> Cetak Laporan PDF
        </button>
    </div>

    <!-- Table -->
    <div class="card shadow">
            <table class="table table-bordered table-hover">
                <thead class="bg-primary text-white text-center">
                    <tr>
                        <th>No</th>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("http://localhost:8000/api/transaction-details", {
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("token"),
                    "Accept": "application/json"
                }
            })

                .then(res => res.json())
                .then(data => {
                    console.log(data);

                    let tbody = document.getElementById("laporan-body");
                    tbody.innerHTML = "";

                    data.data.forEach((item, index) => {

                        let nama = item.transaction.user.name;
                        let judul = item.judul_buku;
                        let tglPinjam = formatDate(item.transaction.tanggal_pinjam);
                        let tglKembali = item.tanggal_kembali 
                            ? formatDate(item.tanggal_kembali) 
                            : "-";

                        let status = item.status;
                        let badge = "";

                        if (status === "dikembalikan") {
                            badge = `<span class="badge badge-success">Dikembalikan</span>`;
                        } else if (status === "dipinjam") {
                            badge = `<span class="badge badge-warning">Dipinjam</span>`;
                        } else if (status === "terlambat") {
                            badge = `<span class="badge badge-danger">Terlambat</span>`;
                        } else if (status === "menunggu_verifikasi") {
                            badge = `<span class="badge badge-success">Menunggu verifikasi</span>`;
                        }

                        tbody.innerHTML += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${nama}</td>
                                <td>${judul}</td>
                                <td>${tglPinjam}</td>
                                <td>${tglKembali}</td>
                                <td>${badge}</td>
                            </tr>
                        `;
                    });
                })
                .catch(err => {
                    console.error(err);
                });

            function formatDate(dateStr) {
                let d = new Date(dateStr);
                return d.toLocaleDateString("id-ID");
            }
        });
    </script>

    <script>
    function cetakLaporan() {
        fetch("http://localhost:8000/api/laporan/transaction-detail", {
            headers: {
                "Authorization": "Bearer " + localStorage.getItem("token"),
                "Accept": "application/pdf"
            }
        })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "laporan-peminjaman.pdf";
            document.body.appendChild(a);
            a.click();
            a.remove();
        })
        .catch(err => console.error(err));
    }
    </script>


@endsection