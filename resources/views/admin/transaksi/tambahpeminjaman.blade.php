@extends('layouts.admin')

@section('title', 'Tambah Peminjaman')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" style="color: var(--dark); font-weight: 700;">Tambah Peminjaman</h1>
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

<div class="row">
    {{-- Kiri: Daftar Buku Dipilih --}}
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

    {{-- Kanan: Informasi Peminjam --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="mb-0 font-weight-bold" style="color: var(--dark);">Informasi Peminjam</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="text-center mb-4">
                    <div class="avatar-placeholder mx-auto mb-3 bg-light d-flex align-items-center justify-content-center"
                         style="width: 80px; height: 80px; border-radius: 50%;">
                        <i class="fas fa-user text-muted fa-2x"></i>
                    </div>
                    <button class="btn btn-sm btn-light border px-3 shadow-sm"
                            data-toggle="modal" data-target="#modalAnggota" style="border-radius: 20px;">
                        <i class="fas fa-search mr-1"></i> Cari Anggota
                    </button>
                </div>

                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-muted text-uppercase">Nama Anggota</label>
                    <input type="text" id="namaAnggota" class="form-control bg-light border-0"
                           placeholder="Pilih anggota..." readonly style="border-radius: 10px;">
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-muted text-uppercase">Kelas</label>
                    <input type="text" id="kelasAnggota" class="form-control bg-light border-0"
                           placeholder="-" readonly style="border-radius: 10px;">
                </div>
                <div class="form-group mb-0">
                    <label class="small font-weight-bold text-muted text-uppercase">NISN</label>
                    <input type="text" id="nisnAnggota" class="form-control bg-light border-0"
                           placeholder="-" readonly style="border-radius: 10px;">
                </div>

                <input type="hidden" id="user_id">
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MODAL A — KATALOG BUKU
══════════════════════════════════════════ --}}
<div class="modal fade" id="modalBuku" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <div>
                    <h5 class="modal-title font-weight-bold">Katalog Buku</h5>
                    <small class="text-muted">Klik <strong>Pilih Eksemplar</strong> untuk memilih nomor fisik buku.</small>
                </div>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body px-4">
                <div class="input-group mb-4 shadow-sm" style="border-radius: 12px; overflow: hidden; border: 1px solid #e3e6f0;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-0 pl-3"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="searchBuku" class="form-control border-0 py-4"
                           placeholder="Cari judul buku, penerbit atau kategori...">
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

{{-- ══════════════════════════════════════════
     MODAL B — PILIH EKSEMPLAR
══════════════════════════════════════════ --}}
<div class="modal fade" id="modalPilihEksemplar" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">

            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div>
                    <h5 class="font-weight-bold text-gray-800 mb-0">
                        <i class="fas fa-barcode mr-2 text-primary"></i>Pilih Eksemplar
                    </h5>
                    <small class="text-muted" id="eksemplarSubtitle">Pilih nomor eksemplar yang ingin dipinjam.</small>
                </div>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            {{-- Ringkasan buku --}}
            <div class="px-4 pb-2">
                <div class="d-flex align-items-center p-2 rounded-lg" style="background: #f0f4ff; gap: 12px;">
                    <img id="eksemplarBookCover" src="" alt=""
                         style="width: 42px; height: 58px; object-fit: cover; border-radius: 6px; flex-shrink: 0;">
                    <div style="min-width: 0;">
                        <div class="font-weight-bold small text-truncate" id="eksemplarBookJudul"></div>
                        <div class="text-muted" style="font-size: 0.72rem;" id="eksemplarBookPenulis"></div>
                    </div>
                </div>
            </div>

            <div class="modal-body px-4 pt-2 pb-3">
                <div id="eksemplarLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted small">Memuat daftar eksemplar...</p>
                </div>
                <div id="eksemplarEmpty" class="text-center py-5" style="display: none;">
                    <i class="fas fa-box-open fa-3x text-light mb-3 d-block"></i>
                    <p class="text-muted mb-0">Tidak ada eksemplar tersedia untuk buku ini.</p>
                </div>
                <div id="eksemplarListContainer" style="display: none;"></div>
            </div>

            <div class="modal-footer border-0 px-4 pb-3 pt-0">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="btnBackToBuku">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Katalog
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MODAL C — PILIH ANGGOTA
══════════════════════════════════════════ --}}
<div class="modal fade" id="modalAnggota" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 px-4 pt-4 text-white" style="border-radius: 20px 20px 0 0; background:var(--primary);">
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

.button-group-wrapper { display: flex; gap: 10px; align-items: center; }
.btn-white { background: white; border: 1px solid #e3e6f0; }
.btn-main {
    height: 45px; padding: 0 20px; border-radius: 12px; border: none;
    display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.85rem;
    transition: all 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.btn-main:hover { transform: translateY(-2px); filter: brightness(1.05); text-decoration: none; }

.form-control:focus { border-color: var(--primary); box-shadow: none; }

/* Kartu buku di bookContainer */
.book-item-card { border-radius: 16px; border: none; transition: all 0.3s; }
.book-item-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important; }
.book-item-img { width: 70px; height: 100px; object-fit: cover; border-radius: 10px; flex-shrink: 0; }

/* Badge eksemplar di kartu */
.eksemplar-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: #eef2ff; color: #4e73df; border-radius: 8px;
    padding: 3px 10px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.4px;
}
.badge-success-soft { background: #dffff3; color: var(--success); }

/* Modal buku cards */
.modal-book-card { border-radius: 15px; border: 1px solid #edf0f5; transition: all 0.2s; height: 100%; }
.modal-book-card:hover { border-color: #4e73df; transform: scale(1.02); }
.img-modal-cover { height: 180px; object-fit: cover; border-radius: 15px 15px 0 0; }

/* Eksemplar list */
.eksemplar-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 13px 16px; border: 1.5px solid #e8ecf5; border-radius: 14px;
    margin-bottom: 10px; background: #fff; transition: all 0.2s; cursor: pointer;
}
.eksemplar-item:hover { border-color: #4e73df; background: #f5f7ff; transform: translateX(3px); }
.eksemplar-item:last-child { margin-bottom: 0; }
.eksemplar-kode { font-weight: 700; color: #2d3748; font-size: 0.92rem; }
.eksemplar-meta { font-size: 0.72rem; color: #a0aec0; margin-top: 2px; }
.badge-tersedia { background: #d4edda; color: #155724; border-radius: 7px; padding: 2px 9px; font-size: 0.7rem; font-weight: 700; }

/* Scrollbar */
#listBuku::-webkit-scrollbar { width: 6px; }
#listBuku::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const API_BASE = '/api';
    const token    = localStorage.getItem('token');

    // STATE
    // selectedItems: [{ bookId, bookStockId, kodeEksemplar, book }, ...]
    let selectedItems = [];
    let allBooks      = [];
    let allUsers      = [];
    let _currentBook  = null;
    let maxHari       = 7;

    // ── Helpers ──────────────────────────────────────────────────────────────
    async function getJSON(url) {
        const res = await fetch(API_BASE + url, {
            headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
        });
        if (!res.ok) throw new Error(res.status);
        return res.json();
    }

    function formatDate(date) {
        const d = new Date(date);
        return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
    }

    async function loadMaxHari() {
        try {
            const json = await getJSON('/aturan-peminjaman/aktif');
            maxHari = json.data?.maks_hari_pinjam ?? 7;
        } catch { maxHari = 7; }
    }

    // ── Render kartu buku di bookContainer ───────────────────────────────────
    function renderBookContainer() {
        const container = document.getElementById('bookContainer');
        const empty     = document.getElementById('emptyState');

        // Hapus semua kartu lama (kecuali emptyState)
        container.querySelectorAll('.book-item-card-wrap').forEach(el => el.remove());

        if (selectedItems.length === 0) {
            empty.classList.remove('d-none');
            return;
        }

        empty.classList.add('d-none');

        const tglPinjam  = formatDate(new Date());
        const tglKembali = formatDate(new Date(Date.now() + maxHari * 86400000));

        selectedItems.forEach(item => {
            const book  = item.book;
            const image = book.image || 'https://via.placeholder.com/300x400?text=No+Image';

            const wrap = document.createElement('div');
            wrap.className = 'book-item-card-wrap mb-3';
            wrap.innerHTML = `
                <div class="card book-item-card shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start" style="gap: 15px;">
                            <img src="${image}" class="book-item-img shadow-sm">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <h6 class="font-weight-bold text-dark mb-0">${book.judul}</h6>
                                        <small class="text-muted">Penerbit: ${book.penerbit || '-'}</small>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm border-0 btn-hapus"
                                            style="border-radius: 10px; padding: 6px 10px;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                                {{-- Eksemplar dipilih --}}
                                <div class="mt-2 mb-2">
                                    <span class="small font-weight-bold text-muted text-uppercase" style="font-size:0.65rem;letter-spacing:1px;">Eksemplar</span><br>
                                    <span class="eksemplar-badge mt-1">
                                        <i class="fas fa-barcode" style="font-size:0.75rem;"></i>
                                        ${item.kodeEksemplar}
                                    </span>
                                </div>

                                <div class="row no-gutters" style="gap: 8px;">
                                    <div class="col-auto">
                                        <label class="small font-weight-bold text-muted mb-0">Rak</label>
                                        <input type="text" class="form-control form-control-sm bg-light border-0"
                                               value="${book.rak || '-'}${book.nomor_rak ? '-'+book.nomor_rak : ''}" readonly style="width:100px;">
                                    </div>
                                    <div class="col-auto">
                                        <label class="small font-weight-bold text-muted mb-0">Tgl Pinjam</label>
                                        <input type="date" class="form-control form-control-sm" value="${tglPinjam}" readonly style="width:140px;">
                                    </div>
                                    <div class="col-auto">
                                        <label class="small font-weight-bold text-muted mb-0">Jatuh Tempo</label>
                                        <input type="date" class="form-control form-control-sm" value="${tglKembali}" readonly style="width:140px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;

            wrap.querySelector('.btn-hapus').addEventListener('click', () => {
                selectedItems = selectedItems.filter(i => i.bookId !== book.id);
                renderBookContainer();
                renderBooksModal(allBooks); // update badge "Sudah Dipilih"
            });

            container.appendChild(wrap);
        });
    }

    // ── Modal A — Katalog Buku ────────────────────────────────────────────────
    async function loadBooks() {
        try {
            const json = await getJSON('/books');
            allBooks = json.data || [];
            renderBooksModal(allBooks);
        } catch {
            document.getElementById('listBuku').innerHTML =
                `<div class="col-12 text-danger text-center py-5">Gagal memuat katalog buku</div>`;
        }
    }

    function renderBooksModal(books) {
        const container = document.getElementById('listBuku');
        const kw = (document.getElementById('searchBuku')?.value || '').toLowerCase();
        const filtered = kw
            ? books.filter(b =>
                b.judul.toLowerCase().includes(kw) ||
                (b.penerbit || '').toLowerCase().includes(kw) ||
                (b.kategori || '').toLowerCase().includes(kw))
            : books;

        container.innerHTML = '';
        if (filtered.length === 0) {
            container.innerHTML = `<div class="col-12 text-center text-muted py-5">Buku tidak ditemukan</div>`;
            return;
        }

        filtered.forEach(book => {
            const stok    = book.available_stock ?? 0;
            const isHabis = stok <= 0;
            const inCart  = !!selectedItems.find(i => i.bookId == book.id);
            const image   = book.image || 'https://via.placeholder.com/300x400?text=No+Image';

            let btnClass = 'btn-primary';
            let btnText  = '<i class="fas fa-list-ul mr-1"></i> Pilih Eksemplar';
            let disabled = '';
            if (isHabis) { btnClass = 'btn-secondary'; btnText = '<i class="fas fa-times-circle mr-1"></i> Habis'; disabled = 'disabled'; }
            if (inCart)  { btnClass = 'btn-success';   btnText = '<i class="fas fa-check mr-1"></i> Sudah Dipilih'; disabled = 'disabled'; }

            container.innerHTML += `
            <div class="col-md-3 mb-4">
                <div class="card modal-book-card shadow-sm ${isHabis ? 'opacity-75' : ''}">
                    <img src="${image}" class="img-modal-cover">
                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="font-weight-bold mb-1 small"
                            style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;height:2.4em;">
                            ${book.judul}
                        </h6>
                        <p class="small text-muted mb-2">${book.penerbit || '-'}</p>
                        <div class="mt-auto">
                            <span class="badge ${stok > 0 ? 'badge-success' : 'badge-danger'} mb-2">Stok: ${stok}</span>
                            <button class="btn ${btnClass} btn-sm btn-block" style="border-radius: 8px;" ${disabled}
                                    onclick="openEksemplarModal('${book.id}')">
                                ${btnText}
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
        });
    }

    document.getElementById('searchBuku').addEventListener('input', function () {
        renderBooksModal(allBooks);
    });

    // ── Modal B — Pilih Eksemplar ─────────────────────────────────────────────
    window.openEksemplarModal = function (bookId) {
        const book = allBooks.find(b => b.id == bookId);
        if (!book) return;

        _currentBook = book;

        document.getElementById('eksemplarBookJudul').innerText   = book.judul;
        document.getElementById('eksemplarBookPenulis').innerText = book.penerbit || '-';
        document.getElementById('eksemplarBookCover').src         = book.image || 'https://via.placeholder.com/300x400?text=No+Cover';
        document.getElementById('eksemplarSubtitle').innerText    = `Tersedia ${book.available_stock} eksemplar — pilih satu`;

        document.getElementById('eksemplarLoading').style.display       = '';
        document.getElementById('eksemplarEmpty').style.display         = 'none';
        document.getElementById('eksemplarListContainer').style.display = 'none';
        document.getElementById('eksemplarListContainer').innerHTML     = '';

        $('#modalBuku').modal('hide');
        $('#modalBuku').one('hidden.bs.modal', () => $('#modalPilihEksemplar').modal('show'));

        fetch(`${API_BASE}/books/${bookId}/stok-tersedia1`, {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(res => {
            const stocks = res.data || [];
            document.getElementById('eksemplarLoading').style.display = 'none';
            if (stocks.length === 0) {
                document.getElementById('eksemplarEmpty').style.display = '';
                return;
            }
            renderEksemplarList(stocks);
            document.getElementById('eksemplarListContainer').style.display = '';
        })
        .catch(() => {
            document.getElementById('eksemplarLoading').style.display       = 'none';
            document.getElementById('eksemplarListContainer').style.display = '';
            document.getElementById('eksemplarListContainer').innerHTML     = `
                <div class="text-center py-4 text-danger">
                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                    <p class="mb-0 small">Gagal memuat eksemplar. Silakan coba lagi.</p>
                </div>`;
        });
    };

    function renderEksemplarList(stocks) {
        const container = document.getElementById('eksemplarListContainer');
        container.innerHTML = '';
        stocks.forEach(stock => {
            const item = document.createElement('div');
            item.className = 'eksemplar-item';
            item.innerHTML = `
                <div>
                    <div class="eksemplar-kode">
                        <i class="fas fa-barcode mr-2 text-primary" style="font-size:0.85rem;"></i>${stock.kode_eksemplar}
                    </div>
                    <div class="eksemplar-meta">Status: <span class="badge badge-success-soft" style="font-size:0.70rem;">tersedia</span></div>
                </div>
                <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" style="font-size:0.8rem;white-space:nowrap;">
                    <i class="fas fa-check mr-1"></i>Pilih
                </button>`;
            item.addEventListener('click', () => addToCart(_currentBook, stock));
            container.appendChild(item);
        });
    }

    function addToCart(book, stock) {
        if (selectedItems.find(i => i.bookId == book.id)) {
            Swal.fire({ icon: 'info', title: 'Info', text: 'Buku ini sudah ada di daftar.' });
            return;
        }

        selectedItems.push({
            bookId:        book.id,
            bookStockId:   stock.id,
            kodeEksemplar: stock.kode_eksemplar,
            book,
        });

        renderBookContainer();
        renderBooksModal(allBooks); // update badge "Sudah Dipilih"

        $('#modalPilihEksemplar').modal('hide');
        Swal.fire({
            icon: 'success', title: 'Ditambahkan!',
            html: `Eksemplar <b>${stock.kode_eksemplar}</b> berhasil ditambahkan.`,
            timer: 1500, showConfirmButton: false
        });
    }

    // Tombol kembali ke katalog
    document.getElementById('btnBackToBuku').addEventListener('click', function () {
        $('#modalPilihEksemplar').modal('hide');
        $('#modalPilihEksemplar').one('hidden.bs.modal', () => $('#modalBuku').modal('show'));
    });

    // ── Modal C — Pilih Anggota ───────────────────────────────────────────────
    async function loadUsers() {
        try {
            const json = await getJSON('/users');
            allUsers = (json.data || []).filter(u => u.role === 'user');
            renderUsers(allUsers);
        } catch {
            document.getElementById('listAnggota').innerHTML =
                `<tr><td colspan="4" class="text-center py-4">Gagal memuat anggota</td></tr>`;
        }
    }

    function renderUsers(data) {
        document.getElementById('listAnggota').innerHTML = data.map(u => `
            <tr>
                <td>
                    <div class="font-weight-bold">${u.name}</div>
                    <div class="small text-muted">${u.email || ''}</div>
                </td>
                <td class="align-middle">${u.nisn ?? '-'}</td>
                <td class="align-middle">${u.class ?? '-'}</td>
                <td class="text-center align-middle">
                    <button class="btn btn-sm btn-primary px-3 btn-pilih-user"
                        data-id="${u.id}" data-nama="${u.name}"
                        data-kelas="${u.class ?? ''}" data-nisn="${u.nisn ?? ''}"
                        style="border-radius: 20px;">Pilih</button>
                </td>
            </tr>`).join('');
    }

    $(document).on('click', '.btn-pilih-user', function () {
        const d = $(this).data();
        $('#namaAnggota').val(d.nama);
        $('#kelasAnggota').val(d.kelas || '-');
        $('#nisnAnggota').val(d.nisn || '-');
        $('#user_id').val(d.id);
        $('#modalAnggota').modal('hide');
    });

    document.getElementById('searchUser').addEventListener('input', function () {
        const key = this.value.toLowerCase();
        renderUsers(allUsers.filter(u =>
            u.name.toLowerCase().includes(key) || (u.nisn && u.nisn.includes(key))
        ));
    });

    // ── Ajukan Pinjaman ───────────────────────────────────────────────────────
    document.getElementById('btnAjukan').addEventListener('click', async function () {
        const userId = document.getElementById('user_id').value;
        if (!userId) {
            Swal.fire('Gagal', 'Pilih anggota terlebih dahulu!', 'error');
            return;
        }
        if (selectedItems.length === 0) {
            Swal.fire('Gagal', 'Pilih minimal satu buku untuk dipinjam!', 'error');
            return;
        }

        const { isConfirmed } = await Swal.fire({
            title: 'Konfirmasi Peminjaman',
            html: `Ajukan peminjaman <b>${selectedItems.length}</b> buku untuk <b>${$('#namaAnggota').val()}</b>?`,
            icon: 'question',
            showCancelButton:    true,
            confirmButtonColor:  '#2C5AA0',
            cancelButtonColor:   '#64748b',
            confirmButtonText:   'Ya, Ajukan!',
            cancelButtonText:    'Batal',
        });
        if (!isConfirmed) return;

        Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        try {
            const res = await fetch(`${API_BASE}/transactions`, {
                method: 'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'Authorization': 'Bearer ' + token,
                    'Accept':        'application/json',
                },
                body: JSON.stringify({
                    user_id:  userId,
                    book_ids: selectedItems.map(i => i.bookId),
                    // Kirim juga book_stock_ids jika backend AdminStore sudah support
                    // book_stock_ids: selectedItems.map(i => i.bookStockId),
                }),
            });
            const json = await res.json();
            if (!res.ok) throw new Error(json.message);

            Swal.fire('Berhasil!', 'Peminjaman telah berhasil diajukan.', 'success')
                .then(() => location.reload());
        } catch (err) {
            Swal.fire('Gagal', err.message, 'error');
        }
    });

    // ── Reset ─────────────────────────────────────────────────────────────────
    document.getElementById('btnReset').addEventListener('click', function () {
        Swal.fire({
            title: 'Reset Form?',
            text: 'Semua buku dan anggota yang dipilih akan dihapus.',
            icon: 'warning',
            confirmButtonColor: '#ef4444',
            showCancelButton:   true,
            confirmButtonText:  'Ya, Reset',
            cancelButtonText:   'Batal',
        }).then(r => { if (r.isConfirmed) location.reload(); });
    });

    // ── Init ──────────────────────────────────────────────────────────────────
    loadMaxHari();
    $('#modalBuku').on('show.bs.modal', loadBooks);
    $('#modalAnggota').on('show.bs.modal', loadUsers);
});
</script>

@endsection