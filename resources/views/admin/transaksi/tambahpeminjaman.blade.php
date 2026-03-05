@extends('layouts.admin')

@section('title', 'Tambah Peminjaman')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" style="color: var(--dark); font-weight: 700;">Tambah Peminjaman</h1>
    <div>
        <div class="button-group-wrapper d-flex align-items-center">
            <button class="btn-main btn-white text-primary" data-toggle="modal" data-target="#modalBuku">
                <i class="fas fa-plus"></i> <span>Tambah Buku</span>
            </button>
            <button class="btn-main btn-white text-danger shadow-none border" id="btnReset">
                <i class="fas fa-undo"></i> <span>Reset</span>
            </button>
            <button class="btn-main btn-primary shadow-sm" id="btnAjukan">
                <i class="fas fa-paper-plane"></i> <span>Ajukan Pinjaman</span>
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0 font-weight-bold" style="color: var(--dark);">Daftar Buku yang Dipilih</h5>
        </div>

        <div id="bookContainer">
            <div id="emptyState" class="card shadow-sm border-0 text-center py-5" style="border-radius: 16px;">
                <div class="card-body">
                    <i class="fas fa-book fa-3x mb-3 text-light"></i>
                    <p class="text-muted">Belum ada buku yang dipilih.<br>Silahkan cari dan pilih buku dari katalog.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="mb-0 font-weight-bold" style="color: var(--dark);">Informasi Peminjam</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="text-center mb-4">
                    <div class="avatar-placeholder mx-auto mb-3 bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border-radius: 50%;">
                        <i class="fas fa-user text-muted fa-2x"></i>
                    </div>
                    <button class="btn btn-sm btn-light border px-3 shadow-sm" data-toggle="modal" data-target="#modalAnggota" style="border-radius: 20px;">
                        <i class="fas fa-search mr-1"></i> Cari Anggota
                    </button>
                </div>

                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-muted text-uppercase">Nama Anggota</label>
                    <input type="text" id="namaAnggota" class="form-control bg-light border-0" placeholder="Pilih anggota..." readonly style="border-radius: 10px;">
                </div>

                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-muted text-uppercase">Kelas</label>
                    <input type="text" id="kelasAnggota" class="form-control bg-light border-0" placeholder="-" readonly style="border-radius: 10px;">
                </div>

                <div class="form-group mb-0">
                    <label class="small font-weight-bold text-muted text-uppercase">NISN</label>
                    <input type="text" id="nisnAnggota" class="form-control bg-light border-0" placeholder="-" readonly style="border-radius: 10px;">
                </div>
                
                <input type="hidden" id="user_id">
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3 book-card d-none" id="bookTemplate" style="border-radius: 16px;">
    <div class="card-body p-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <img id="modalCover" width="70" height="100" class="rounded shadow-sm" style="object-fit: cover;">
            </div>
            <div class="col">
                <h6 class="judulText mb-1 font-weight-bold text-dark">Judul Buku</h6>
                <small class="text-muted d-block mb-2 penerbitText">Penerbit</small>
                <div class="row no-gutters">
                    <div class="col-md-5 mr-2">
                        <label class="small font-weight-bold text-muted mb-0">Rak</label>
                        <input type="text" class="form-control form-control-sm bg-light border-0 rak" readonly>
                    </div>
                    <div class="col-md-3 mr-2">
                        <label class="small font-weight-bold text-muted mb-0">Stok</label>
                        <input type="text" class="form-control form-control-sm bg-light border-0 stok" readonly>
                    </div>
                </div>
                <div class="row no-gutters mt-2">
                    <div class="col-md-5 mr-2">
                        <label class="small font-weight-bold text-muted mb-0">Tgl Pinjam</label>
                        <input type="date" class="form-control form-control-sm tgl_pinjam" readonly>
                    </div>
                    <div class="col-md-5">
                        <label class="small font-weight-bold text-muted mb-0">Jatuh Tempo</label>
                        <input type="date" class="form-control form-control-sm tgl_kembali" readonly>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-outline-danger btn-sm border-0 btn-hapus-buku" style="border-radius: 10px; padding: 10px;">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBuku" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title font-weight-bold">Katalog Buku</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body px-4">
                <div class="input-group mb-4 shadow-sm" style="border-radius: 30px; overflow: hidden;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-0 pl-4"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="searchBuku" class="form-control border-0 py-4" placeholder="Cari judul buku, penerbit atau kategori...">
                </div>
                
                <div class="row" id="listBuku" style="max-height: 500px; overflow-y: auto;">
                    <div class="col-12 text-center text-muted py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2">Memuat buku...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 pr-4">
                <button class="btn btn-light px-4" data-dismiss="modal" style="border-radius: 10px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAnggota" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 px-4 pt-4 bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title font-weight-bold">Daftar Anggota</h5>
                <button class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div class="input-group mb-3 mt-2 shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-0"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="text" id="searchUser" class="form-control border-0" placeholder="Cari Nama atau NISN...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="bg-light">
                            <tr class="small text-muted text-uppercase">
                                <th class="border-0">Anggota</th>
                                <th class="border-0">NISN</th>
                                <th class="border-0">Kelas</th>
                                <th class="border-0 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="listAnggota"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #2C5AA0;
    --dark: #1e293b;
    --gray: #64748b;
    --gray-light: #f8fafc;
    --border: #e2e8f0;
}

 .button-group-wrapper {
    display: flex;
    gap: 10px;
    justify-content: space-between;
    align-items: center;
}
.btn-white { background: white; border: 1px solid #e3e6f0; }
.btn-main {
        height: 45px; padding: 0 20px; border-radius: 12px; border: none;
        display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.85rem;
        transition: all 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
.form-control:focus { border-color: var(--primary); box-shadow: none; }
.book-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important; }

/* Scrollbar Style */
#listBuku::-webkit-scrollbar { width: 6px; }
#listBuku::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const API_BASE = "http://127.0.0.1:8000/api";
    const token = localStorage.getItem("token");
    let selectedBooks = [];
    let allBooks = [];
    let allUsers = [];

    /* ================= FETCH HELPER ================= */
    async function getJSON(url){
        const res = await fetch(API_BASE + url, {
            headers: {
                "Accept": "application/json",
                "Authorization": "Bearer " + token
            }
        });
        if(!res.ok) throw new Error(res.status);
        return await res.json();
    }

    /* ================= ATURAN PINJAM ================= */
    async function getMaxHari(){
        try {
            let json = await getJSON("/aturan-peminjaman/aktif");
            return json.data ? json.data.maks_hari_pinjam : 3;
        } catch {
            return 3;
        }
    }

    /* ================= LOAD & RENDER BUKU ================= */
    async function loadBooks(){
        try {
            let json = await getJSON("/books");
            allBooks = json.data;
            renderBooks(allBooks);
        } catch(err) {
            document.getElementById("listBuku").innerHTML = `<div class="col-12 text-danger text-center py-5">Gagal memuat katalog buku</div>`;
        }
    }

    function renderBooks(data){
        const container = document.getElementById("listBuku");
        if(data.length === 0){
            container.innerHTML = `<div class="col-12 text-center text-muted py-5">Buku tidak ditemukan</div>`;
            return;
        }

        container.innerHTML = data.map(book => {
            const stok = book.available_stock ?? 0;
            const image = book.image || 'https://via.placeholder.com/300x400?text=No+Image';

            return `
                <div class="col-md-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                        <img src="${image}" class="card-img-top" style="height:300px; object-fit:cover">
                        <div class="card-body p-3 d-flex flex-column">
                            <h6 class="font-weight-bold mb-1 text-truncate" title="${book.judul}">${book.judul}</h6>
                            <p class="small text-muted mb-2">${book.penerbit}</p>
                            <div class="mt-auto">
                                <span class="badge ${stok > 0 ? 'badge-success' : 'badge-danger'} mb-2">Stok: ${stok}</span>
                                <button class="btn btn-primary btn-sm btn-block" style="border-radius: 8px;"
                                    onclick="addSelectedBook({
                                        id: '${book.id}',
                                        judul: '${book.judul.replace(/'/g, "\\'")}',
                                        penerbit: '${book.penerbit.replace(/'/g, "\\'")}',
                                        rak: '${book.rak}-${book.nomor_rak}',
                                        stok: ${stok},
                                        image: '${image}'
                                    })" ${stok <= 0 ? 'disabled' : ''}>
                                    ${stok > 0 ? 'Pilih Buku' : 'Stok Habis'}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join("");
    }

    /* ================= LOGIKA PILIH BUKU ================= */
    window.addSelectedBook = async function(book) {
        if(selectedBooks.includes(book.id)) return alert("Buku sudah terpilih!");

        const maxHari = await getMaxHari();
        const today = new Date();
        const kembali = new Date(today);
        kembali.setDate(today.getDate() + maxHari);

        selectedBooks.push(book.id);
        document.getElementById("emptyState").classList.add("d-none");

        const template = document.getElementById("bookTemplate");
        const clone = template.cloneNode(true);

        clone.classList.remove("d-none");
        clone.removeAttribute("id");

        clone.querySelector("#modalCover").src = book.image;
        clone.querySelector(".judulText").innerText = book.judul;
        clone.querySelector(".penerbitText").innerText = "Penerbit: " + book.penerbit;
        clone.querySelector(".rak").value = book.rak;
        clone.querySelector(".stok").value = book.stok;
        clone.querySelector(".tgl_pinjam").value = today.toISOString().split('T')[0];
        clone.querySelector(".tgl_kembali").value = kembali.toISOString().split('T')[0];

        clone.querySelector(".btn-hapus-buku").onclick = () => {
            clone.remove();
            selectedBooks = selectedBooks.filter(id => id !== book.id);
            if(selectedBooks.length === 0) document.getElementById("emptyState").classList.remove("d-none");
        };

        document.getElementById("bookContainer").appendChild(clone);
        $('#modalBuku').modal('hide');
    };

    /* ================= SEARCHING BUKU ================= */
    document.getElementById("searchBuku").addEventListener("input", function(){
        const keyword = this.value.toLowerCase();
        const filtered = allBooks.filter(b => 
            b.judul.toLowerCase().includes(keyword) || 
            b.penerbit.toLowerCase().includes(keyword)
        );
        renderBooks(filtered);
    });

    /* ================= LOAD & SEARCH USER ================= */
    async function loadUsers(){
        try {
            let json = await getJSON("/users");
            allUsers = json.data.filter(u => u.role === "user");
            renderUsers(allUsers);
        } catch {
            document.getElementById("listAnggota").innerHTML = `<tr><td colspan="4" class="text-center py-4">Gagal memuat anggota</td></tr>`;
        }
    }

    function renderUsers(data){
        document.getElementById("listAnggota").innerHTML = data.map((u, i) => `
            <tr>
                <td>
                    <div class="font-weight-bold">${u.name}</div>
                    <div class="small text-muted">${u.email || ''}</div>
                </td>
                <td class="align-middle">${u.nisn ?? '-'}</td>
                <td class="align-middle">${u.class ?? '-'}</td>
                <td class="text-center align-middle">
                    <button class="btn btn-sm btn-primary px-3 btn-pilih-user" 
                        data-id="${u.id}" data-nama="${u.name}" data-kelas="${u.class}" data-nisn="${u.nisn}"
                        style="border-radius: 20px;">Pilih</button>
                </td>
            </tr>
        `).join("");
    }

    $(document).on("click", ".btn-pilih-user", function(){
        const d = $(this).data();
        $("#namaAnggota").val(d.nama);
        $("#kelasAnggota").val(d.kelas || '-');
        $("#nisnAnggota").val(d.nisn || '-');
        $("#user_id").val(d.id);
        $("#modalAnggota").modal("hide");
    });

    document.getElementById("searchUser").addEventListener("input", function(){
        const key = this.value.toLowerCase();
        const filtered = allUsers.filter(u => 
            u.name.toLowerCase().includes(key) || (u.nisn && u.nisn.includes(key))
        );
        renderUsers(filtered);
    });

    /* ================= AJUKAN & RESET ================= */
    document.getElementById("btnAjukan").addEventListener("click", async function(){
        const userId = document.getElementById("user_id").value;
        if(!userId) return Swal.fire('Gagal', 'Pilih anggota terlebih dahulu!', 'error');
        if(selectedBooks.length === 0) return Swal.fire('Gagal', 'Pilih minimal satu buku untuk dipinjam!', 'error');

        Swal.fire({
            title: 'Konfirmasi Peminjaman',
            text: "Apakah data peminjaman sudah benar?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2C5AA0',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Ajukan!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
                try {
                    const res = await fetch(API_BASE + "/transactions", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "Authorization": "Bearer " + token },
                        body: JSON.stringify({ user_id: userId, book_ids: selectedBooks })
                    });
                    const json = await res.json();
                    if(!res.ok) throw new Error(json.message);
                    
                    Swal.fire('Berhasil!', 'Peminjaman telah berhasil diajukan.', 'success').then(() => location.reload());
                } catch(err) {
                    Swal.fire('Gagal', err.message, 'error');
                }
            }
        });
    });

    document.getElementById("btnReset").onclick = () => {
        Swal.fire({
            title: 'Reset Form?',
            text: "Semua buku dan anggota yang dipilih akan dihapus.",
            icon: 'warning',
            confirmButtonColor: '#ef4444',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal'
        }).then((result) => { if(result.isConfirmed) location.reload(); });
    };

    $('#modalBuku').on('show.bs.modal', loadBooks);
    $('#modalAnggota').on('show.bs.modal', loadUsers);
});
</script>
@endsection