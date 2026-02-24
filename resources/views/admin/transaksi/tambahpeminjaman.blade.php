@extends('layouts.admin')

@section('title', 'Tambah Peminjaman')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <button class="btn btn-primary mr-2" data-toggle="modal" data-target="#modalBuku">
            <i class="fas fa-plus"></i> Tambah Peminjaman
        </button>

        <button class="btn btn-success mr-2" id="btnReset">
            <i class="fas fa-undo"></i> Reset
        </button>

        <button class="btn btn-warning" id="btnAjukan">
            <i class="fas fa-paper-plane"></i> Ajukan Peminjaman
        </button>
    </div>

    <button class="btn btn-info" data-toggle="modal" data-target="#modalAnggota">
        <i class="fas fa-user-plus"></i> Tambah Anggota
    </button>
</div>

<div class="row">

    <!-- ================= CARD BUKU CONTAINER ================= -->
    <div id="bookContainer" class="col-lg-8"></div>

    <!-- ================= TEMPLATE CARD BUKU (HIDDEN) ================= -->
    <div class="card shadow-sm mb-4 book-card d-none" id="bookTemplate">

        <div class="d-flex align-items-center p-3">
            <img id="modalCover"
                width="80" class="rounded mr-3">

            <div>
                <h5 class="judulText">Judul Buku</h5>
                <small class="text-muted penerbitText">Penerbit</small>
            </div>
        </div>

        <div class="p-3 bg-light border-top">
            <div class="row text-sm">

                <div class="col-md-6">
                    <label>Rak</label>
                    <input type="text" class="form-control rak" readonly>
                </div>

                <div class="col-md-6">
                    <label>Stok</label>
                    <input type="text" class="form-control stok" readonly>
                </div>

                <div class="col-md-6 mt-2">
                    <label>Tanggal Pinjam</label>
                    <input type="date" class="form-control tgl_pinjam" readonly>
                </div>

                <div class="col-md-6 mt-2">
                    <label>Tanggal Jatuh Tempo</label>
                    <div class="d-flex align-items-center">
                        <input type="date" class="form-control mr-2 tgl_kembali" readonly>
                        <button type="button" class="btn btn-danger btn-hapus-buku">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- ================= CARD ANGGOTA ================= -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">

            <div class="p-3 bg-primary text-white rounded-top">
                <h5 class="mb-0"><i class="fas fa-user mr-1"></i> Form Peminjam</h5>
            </div>

            <div class="p-3">
                <div class="form-group">
                    <label>Nama Anggota</label>
                    <input type="text" id="namaAnggota" class="form-control" placeholder="Nama anggota">
                </div>

                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" id="kelasAnggota" class="form-control" placeholder="Kelas">
                </div>

                <div class="form-group">
                    <label>NISN</label>
                    <input type="text" id="nisnAnggota" class="form-control" placeholder="NISN">
                </div>
            </div>
        </div>
    </div>

</div>

<!-- INPUT AJUKAN PEMINJAMAN -->
<input type="hidden" id="user_id">
<input type="hidden" id="book_ids">

<!-- ================= MODAL PILIH BUKU ================= -->
<div class="modal fade" id="modalBuku" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Buku Peminjaman</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <input type="text" id="searchBuku" class="form-control mb-4" placeholder="Cari Judul Buku...">

                <div class="row" id="listBuku">
                    <div class="col-12 text-center text-muted">Loading buku...</div>
                </div>

                <!-- INFO & PAGINATION -->
                <div class="d-flex justify-content-between align-items-center mt-3 px-2">
                    <small class="text-muted">Menampilkan 1 sampai 4 dari 4 buku</small>
                    <div>
                        <button class="btn btn-light btn-sm"><i class="fas fa-chevron-left"></i></button>
                        <span class="mx-2">Halaman 1 dari 1</span>
                        <button class="btn btn-light btn-sm"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>

            </div>

            <div class="modal-footer d-flex justify-content-end">
                <div>
                    <button class="btn btn-secondary mr-1" data-dismiss="modal">Batal</button>\
                </div>

            </div>

        </div>
    </div>
</div>

<!-- ================= MODAL PILIH ANGGOTA ================= -->
<div class="modal fade" id="modalAnggota">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Pilih Anggota</h5>
                <button class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="text" class="form-control mb-3" placeholder="Cari Nama / NISN...">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>No</th>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th>Pilih</th>
                            </tr>
                        </thead>
                        <tbody id="listAnggota">
                            <tr>
                                <td colspan="6">Loading anggota...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const API_BASE = "http://127.0.0.1:8000/api";
    const token = localStorage.getItem("token");
    let selectedBooks = [];
    let allBooks = []; // Menampung data buku global untuk filter

    /* ================= FETCH HELPER ================= */
    async function getJSON(url){
        const res = await fetch(API_BASE + url,{
            headers:{
                "Accept":"application/json",
                "Authorization":"Bearer "+token
            }
        });

        if(!res.ok) throw new Error(res.status);
        return await res.json();
    }

    /* ================= ATURAN PINJAM ================= */
    async function getMaxHari(){
        try{
            let json = await getJSON("/aturan-peminjaman");
            let aktif = json.data.find(a=>a.aktif === true);
            return aktif ? aktif.maks_hari_pinjam : 3;
        }catch{
            return 3;
        }
    }

    /* ================= LOAD & RENDER BUKU ================= */
    async function loadBooks(){
        try{
            let json = await getJSON("/books");
            allBooks = json.data;
            renderBooks(allBooks);
        }catch(err){
            console.error(err);
            document.getElementById("listBuku").innerHTML =
                `<div class="col-12 text-danger text-center">Gagal load buku</div>`;
        }
    }

    function renderBooks(data){
        const container = document.getElementById("listBuku");
        
        if(data.length === 0){
            container.innerHTML = `<div class="col-12 text-center text-muted">Buku tidak ditemukan</div>`;
            return;
        }

        let html = data.map(book => {
            const stok = book.available_stock ?? 0;
            const image = book.image 
                ? book.image 
                : 'https://via.placeholder.com/300x400?text=No+Image';

            return `
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="${image}" class="card-img-top" style="height:200px; object-fit:cover">
                        <div class="card-body d-flex flex-column p-2">
                            <h6 class="font-weight-bold mb-1" style="font-size: 0.9rem;">${book.judul}</h6>
                            <p class="small mb-1 text-muted">Penerbit: ${book.penerbit}</p>
                            <div class="mb-2">
                                <span class="badge badge-info">${book.kategori || 'Umum'}</span>
                                <span class="badge ${stok > 0 ? 'badge-light' : 'badge-danger'}">Stok: ${stok}</span>
                            </div>
                            <div class="mt-auto">
                                <button class="btn btn-success btn-sm btn-block" 
                                    onclick="addSelectedBook({
                                        id: '${book.id}',
                                        judul: '${book.judul.replace(/'/g, "\\'")}',
                                        penerbit: '${book.penerbit.replace(/'/g, "\\'")}',
                                        rak: '${book.rak}-${book.nomor_rak}',
                                        stok: ${stok},
                                        image: '${image}'
                                    })">
                                    Pilih
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join("");

        container.innerHTML = html;
    }

    /* ================= LOGIKA PILIH BUKU (PINDAH KE FORM) ================= */
    // Fungsi ini dipanggil dari atribut onclick di renderBooks
    window.addSelectedBook = async function(book) {
        if(selectedBooks.includes(book.id))
            return alert("Buku ini sudah masuk dalam daftar pinjam");

        if(book.stok <= 0)
            return alert("Maaf, stok buku sedang habis");

        let maxHari = await getMaxHari();
        let today = new Date();
        let kembali = new Date(today);
        kembali.setDate(today.getDate() + maxHari);

        selectedBooks.push(book.id);

        let template = document.getElementById("bookTemplate");
        let clone = template.cloneNode(true);

        clone.classList.remove("d-none");
        clone.removeAttribute("id");

        clone.querySelector("#modalCover").src = book.image;
        clone.querySelector(".judulText").innerText = book.judul;
        clone.querySelector(".penerbitText").innerText = "Penerbit: " + book.penerbit;
        clone.querySelector(".rak").value = book.rak;
        clone.querySelector(".stok").value = book.stok + " Buku";
        clone.querySelector(".tgl_pinjam").value = today.toISOString().split('T')[0];
        clone.querySelector(".tgl_kembali").value = kembali.toISOString().split('T')[0];

        clone.querySelector(".btn-hapus-buku").onclick = () => {
            clone.remove();
            selectedBooks = selectedBooks.filter(id => id !== book.id);
        };

        document.getElementById("bookContainer").appendChild(clone);
        $('#modalBuku').modal('hide');
    };

    /* ================= SEARCHING ================= */
    document.getElementById("searchBuku").addEventListener("input", function(){
        let keyword = this.value.toLowerCase();
        let filtered = allBooks.filter(book =>
            book.judul.toLowerCase().includes(keyword) ||
            book.penerbit.toLowerCase().includes(keyword) ||
            (book.rak && book.rak.toLowerCase().includes(keyword))
        );
        renderBooks(filtered);
    });

    /* ================= LOAD USERS ================= */
    async function loadUsers(){
        try{
            let json = await getJSON("/users");

            let html = json.data
            .filter(u => u.role === "user")
            .map((u, i) => `
                <tr data-id="${u.id}" data-nama="${u.name}" data-kelas="${u.class}" data-nisn="${u.nisn}">
                    <td>${i+1}</td>
                    <td>${u.nisn ?? '-'}</td>
                    <td>${u.name}</td>
                    <td>${u.class ?? '-'}</td>
                    <td><span class="badge badge-success">Aktif</span></td>
                    <td><button class="btn btn-warning btn-sm pilihAnggota">Pilih</button></td>
                </tr>
            `).join("");

            document.getElementById("listAnggota").innerHTML = html;
        } catch {
            document.getElementById("listAnggota").innerHTML =
                `<tr><td colspan="6" class="text-center text-danger">Gagal load anggota</td></tr>`;
        }
    }

    /* ================= EVENT PILIH ANGGOTA ================= */
    $(document).on("click", ".pilihAnggota", function(){
        let row = $(this).closest("tr");
        $("#namaAnggota").val(row.data("nama"));
        $("#kelasAnggota").val(row.data("kelas"));
        $("#nisnAnggota").val(row.data("nisn"));
        $("#user_id").val(row.data("id"));
        $("#modalAnggota").modal("hide");
    });

    /* ================= AJUKAN PINJAM ================= */
    document.getElementById("btnAjukan").addEventListener("click", async function(){
        let userId = document.getElementById("user_id").value;

        if(!userId) return alert("Pilih anggota terlebih dahulu");
        if(selectedBooks.length === 0) return alert("Pilih minimal 1 buku");

        try {
            let res = await fetch(API_BASE + "/transactions", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Authorization": "Bearer " + token
                },
                body: JSON.stringify({
                    user_id: userId,
                    book_ids: selectedBooks
                })
            });

            let json = await res.json();
            if(!res.ok) return alert(json.message || "Gagal transaksi");

            alert("Peminjaman berhasil");
            resetForm();
        } catch {
            alert("Terjadi kesalahan server");
        }
    });

    /* ================= RESET ================= */
    function resetForm(){
        selectedBooks = [];
        document.getElementById("bookContainer").innerHTML = "";
        $("#namaAnggota, #kelasAnggota, #nisnAnggota, #user_id").val("");
    }

    document.getElementById("btnReset").onclick = resetForm;

    /* ================= MODAL INITIALIZATION ================= */
    $('#modalBuku').on('show.bs.modal', loadBooks);
    $('#modalAnggota').on('show.bs.modal', loadUsers);

});
</script>
@endsection