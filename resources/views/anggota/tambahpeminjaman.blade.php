@extends('layouts.anggota')

@section('title', 'Tambah Peminjaman')

@section('content')

<div class="container-fluid animate-up">
    <div class="header-container mb-4 d-flex align-items-center justify-content-between">
        <div class="header-left">
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Keranjang Peminjaman</h1>
        </div>
        <div class="button-group-wrapper d-flex align-items-center">
            <button class="btn-main btn-white text-primary" data-toggle="modal" data-target="#modalPilihBuku">
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

    <div id="pinjaman-list" class="row">
        <div class="col-12 text-center py-5 empty-state">
            <div class="mb-3"><i class="fas fa-shopping-basket fa-3x text-light"></i></div>
            <p class="text-muted font-italic">Keranjang pinjaman masih kosong...</p>
        </div>
    </div>
</div>

{{-- MODAL A — PILIH BUKU --}}
<div class="modal fade" id="modalPilihBuku" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <div>
                    <h5 class="font-weight-bold text-gray-800 mb-0">Pilih Buku</h5>
                    <small class="text-muted">Klik <strong>Pilih Eksemplar</strong> untuk memilih nomor fisik buku.</small>
                </div>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="px-4 pt-3">
                <div class="input-group shadow-sm" style="border-radius:12px;overflow:hidden;border:1px solid #e3e6f0;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="searchBukuModal" class="form-control border-0 py-4"
                           placeholder="Cari judul buku atau kategori...">
                </div>
            </div>
            <div class="modal-body px-4 mt-2">
                <div class="row" id="book-list">
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL B — PILIH EKSEMPLAR --}}
<div class="modal fade" id="modalPilihEksemplar" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div>
                    <h5 class="font-weight-bold text-gray-800 mb-0">
                        <i class="fas fa-barcode mr-2 text-primary"></i>Pilih Eksemplar
                    </h5>
                    <small class="text-muted" id="eksemplarSubtitle">Pilih nomor eksemplar yang ingin dipinjam.</small>
                </div>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="px-4 pb-2">
                <div class="d-flex align-items-center p-2 rounded-lg" style="background:#f0f4ff;gap:12px;">
                    <img id="eksemplarBookCover" src="" alt=""
                         style="width:42px;height:58px;object-fit:cover;border-radius:6px;flex-shrink:0;">
                    <div style="min-width:0;">
                        <div class="font-weight-bold text-gray-800 small text-truncate" id="eksemplarBookJudul"></div>
                        <div class="text-muted" style="font-size:0.72rem;" id="eksemplarBookPenulis"></div>
                    </div>
                </div>
            </div>
            <div class="modal-body px-4 pt-2 pb-3">
                <div id="eksemplarLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted small">Memuat daftar eksemplar...</p>
                </div>
                <div id="eksemplarEmpty" class="text-center py-5" style="display:none;">
                    <i class="fas fa-box-open fa-3x text-light mb-3 d-block"></i>
                    <p class="text-muted mb-0">Tidak ada eksemplar tersedia.</p>
                </div>
                <div id="eksemplarListContainer" style="display:none;"></div>
            </div>
            <div class="modal-footer border-0 px-4 pb-3 pt-0">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="btnBackToBuku">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Buku
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .text-overline { text-transform:uppercase; letter-spacing:1px; font-weight:800; font-size:0.65rem; }
    .animate-up { animation:fadeInUp 0.5s ease-out forwards; }
    @keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

    .button-group-wrapper { display:flex; gap:10px; align-items:center; }
    .btn-main {
        height:45px; padding:0 20px; border-radius:12px; border:none;
        display:flex; align-items:center; gap:8px; font-weight:600; font-size:0.85rem;
        transition:all 0.3s; box-shadow:0 4px 12px rgba(0,0,0,0.08);
    }
    .btn-main:hover { transform:translateY(-2px); filter:brightness(1.05); text-decoration:none; }
    .btn-white { background:white; border:1px solid #e3e6f0; }

    .loan-item-card { border-radius:18px !important; border:none; transition:all 0.3s; }
    .loan-item-card:hover { transform:translateY(-5px); box-shadow:0 10px 20px rgba(0,0,0,0.08) !important; }
    .loan-item-img { width:100%; height:120px; object-fit:cover; border-radius:12px; }
    .bg-soft-light { background:#f8f9fc; border-radius:12px; padding:15px; }
    .form-control-read { background:transparent !important; border:none; font-weight:700; padding:0; height:auto; color:#4e73df; }

    .eksemplar-badge {
        display:inline-flex; align-items:center; gap:6px;
        background:#eef2ff; color:#4e73df; border-radius:8px;
        padding:4px 12px; font-size:0.78rem; font-weight:700; letter-spacing:0.4px;
    }

    .modal-book-card { border-radius:15px; border:1px solid #edf0f5; transition:all 0.2s; height:100%; }
    .modal-book-card:hover { border-color:#4e73df; transform:scale(1.02); }
    .img-modal-cover { height:180px; object-fit:cover; border-radius:15px 15px 0 0; }
    .badge-primary-soft { background-color:#eaecf4; color:#4e73df; }
    .badge-success-soft { background: #dffff3; color: var(--success); }

    .eksemplar-item {
        display:flex; align-items:center; justify-content:space-between;
        padding:13px 16px; border:1.5px solid #e8ecf5; border-radius:14px;
        margin-bottom:10px; background:#fff; transition:all 0.2s; cursor:pointer;
    }
    .eksemplar-item:hover { border-color:#4e73df; background:#f5f7ff; transform:translateX(3px); }
    .eksemplar-item:last-child { margin-bottom:0; }
    .eksemplar-kode { font-weight:700; color:#2d3748; font-size:0.92rem; }
    .eksemplar-meta { font-size:0.72rem; color:#a0aec0; margin-top:2px; }
    .badge-tersedia { background:#d4edda; color:#155724; border-radius:7px; padding:2px 9px; font-size:0.7rem; font-weight:700; }

    @media (max-width:576px) {
        .header-container { flex-direction:column; align-items:flex-start; }
        .button-group-wrapper { width:100%; flex-wrap:wrap; margin-top:15px; }
        .btn-main { flex:1; min-width:120px; }
    }
</style>

<script>
let selectedItems  = [];
let allBooks       = [];
let maksHariPinjam = 0;
let _currentBook   = null;

async function loadAturanPeminjaman() {
    try {
        const token = localStorage.getItem('token');
        const res   = await fetch('/api/aturanpeminjaman/aktif', {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        });
        maksHariPinjam = (await res.json()).data.maks_hari_pinjam;
    } catch (e) { console.error('Gagal ambil aturan', e); }
}

function formatDate(date) {
    const d = new Date(date);
    return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
}

// ── Keranjang ─────────────────────────────────────────────────────────────────
function renderKeranjang() {
    const container = document.getElementById('pinjaman-list');
    if (selectedItems.length === 0) {
        container.innerHTML = `
        <div class="col-12 text-center py-5 empty-state">
            <div class="mb-3"><i class="fas fa-shopping-basket fa-3x text-light"></i></div>
            <p class="text-muted font-italic">Keranjang pinjaman masih kosong...</p>
        </div>`;
        return;
    }

    const tglPinjam  = formatDate(new Date());
    const tglKembali = formatDate(new Date(Date.now() + maksHariPinjam * 86400000));

    container.innerHTML = selectedItems.map(item => {
        const book  = item.book;
        const image = book.image || 'https://via.placeholder.com/300x400?text=No+Image';
        return `
        <div class="col-xl-4 col-md-6 mb-4 card-container" data-book-id="${book.id}">
            <div class="card loan-item-card shadow-sm h-100">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-4">
                            <img src="${image}" class="loan-item-img shadow-sm">
                        </div>
                        <div class="col-8">
                            <span class="text-overline text-primary mb-1 d-block">Informasi Buku</span>
                            <h6 class="font-weight-bold text-gray-800 mb-1 text-truncate">${book.judul}</h6>
                            <small class="text-muted d-block mb-2">${book.penerbit || '-'}</small>
                            <button class="btn btn-sm btn-outline-danger border-0 px-0"
                                    onclick="removeItem('${book.id}')">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                            </button>
                        </div>
                    </div>
                    <div class="bg-soft-light text-xs">
                        <div class="mb-3">
                            <label class="text-overline text-muted d-block mb-1">Eksemplar Dipilih</label>
                            <span class="eksemplar-badge">
                                <i class="fas fa-barcode" style="font-size:0.75rem;"></i>
                                ${item.kodeEksemplar}
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-2">
                                <label class="text-overline text-muted d-block mb-0">Rak / No</label>
                                <span class="font-weight-bold text-dark">
                                    ${book.rak || '-'}${book.nomor_rak ? ' - ' + book.nomor_rak : ''}
                                </span>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="text-overline text-muted d-block mb-0">Tersedia</label>
                                <span class="font-weight-bold text-success">Stok: ${book.available_stock}</span>
                            </div>
                            <div class="col-6">
                                <label class="text-overline text-muted d-block mb-0">Tgl Pinjam</label>
                                <input type="date" class="form-control-read" value="${tglPinjam}" readonly>
                            </div>
                            <div class="col-6">
                                <label class="text-overline text-muted d-block mb-0">Tgl Kembali</label>
                                <input type="date" class="form-control-read" value="${tglKembali}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function removeItem(bookId) {
    selectedItems = selectedItems.filter(i => i.bookId !== bookId);
    renderKeranjang();
    renderBooksModal(allBooks);
}

// ── Modal A — Daftar Buku ─────────────────────────────────────────────────────
async function loadBooksModal() {
    const token = localStorage.getItem('token');
    try {
        const res = await fetch('/api/list-books', {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        });
        allBooks = (await res.json()).data || [];
        renderBooksModal(allBooks);
    } catch (e) { console.error('Gagal memuat buku', e); }
}

function renderBooksModal(books) {
    const container = document.getElementById('book-list');
    const kw = (document.getElementById('searchBukuModal')?.value || '').toLowerCase();
    const filtered = kw
        ? books.filter(b => b.judul.toLowerCase().includes(kw) || (b.kategori || '').toLowerCase().includes(kw))
        : books;

    container.innerHTML = '';
    if (filtered.length === 0) {
        container.innerHTML = `<div class="col-12 text-center py-5 text-muted">Buku tidak ditemukan...</div>`;
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
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card modal-book-card shadow-sm ${isHabis ? 'opacity-75' : ''}">
                <img src="${image}" class="img-modal-cover">
                <div class="card-body p-3 d-flex flex-column">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="badge badge-primary-soft text-primary px-2 py-1" style="font-size:10px;">
                            ${book.kategori || 'Umum'}
                        </span>
                        <span class="small font-weight-bold ${isHabis ? 'text-danger' : 'text-success'}">
                            <i class="fas fa-box mr-1"></i>${stok}
                        </span>
                    </div>
                    <h6 class="font-weight-bold text-gray-800 small mb-3"
                        style="height:2.4em;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                        ${book.judul}
                    </h6>
                    <div class="mt-auto">
                        <button class="btn ${btnClass} btn-sm btn-block rounded-pill shadow-sm" ${disabled}
                                onclick="openEksemplarModal('${book.id}')">
                            ${btnText}
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    });
}

document.getElementById('searchBukuModal').addEventListener('input', function () {
    renderBooksModal(allBooks);
});

// ── Modal B — Pilih Eksemplar ─────────────────────────────────────────────────
function openEksemplarModal(bookId) {
    const book = allBooks.find(b => b.id == bookId);
    if (!book) return;

    _currentBook = book;
    document.getElementById('eksemplarBookJudul').innerText   = book.judul;
    document.getElementById('eksemplarBookPenulis').innerText = book.penulis || '-';
    document.getElementById('eksemplarBookCover').src         = book.image || 'https://via.placeholder.com/300x400?text=No+Cover';
    document.getElementById('eksemplarSubtitle').innerText    = `Tersedia ${book.available_stock} eksemplar — pilih satu`;

    document.getElementById('eksemplarLoading').style.display       = '';
    document.getElementById('eksemplarEmpty').style.display         = 'none';
    document.getElementById('eksemplarListContainer').style.display = 'none';
    document.getElementById('eksemplarListContainer').innerHTML     = '';

    $('#modalPilihBuku').modal('hide');
    $('#modalPilihBuku').one('hidden.bs.modal', () => $('#modalPilihEksemplar').modal('show'));

    const token = localStorage.getItem('token');
    fetch(`/api/books/${bookId}/stok-tersedia`, {
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
}

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
                <div class="eksemplar-meta">Status: <span class="badge badge-success-soft" style="font-size: 0.70rem;">tersedia</span></div>
            </div>
            <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" style="font-size:0.8rem;white-space:nowrap;">
                <i class="fas fa-check mr-1"></i>Pilih
            </button>`;
        item.addEventListener('click', () => addToCartFromModal(_currentBook, stock));
        container.appendChild(item);
    });
}

function addToCartFromModal(book, stock) {
    if (selectedItems.find(i => i.bookId == book.id)) {
        Swal.fire({ icon:'info', title:'Info', text:'Buku ini sudah ada di keranjang.' });
        return;
    }
    selectedItems.push({
        bookId:        book.id,
        bookStockId:   stock.id,
        kodeEksemplar: stock.kode_eksemplar,
        book,
    });
    renderKeranjang();
    $('#modalPilihEksemplar').modal('hide');
    Swal.fire({
        icon:'success', title:'Ditambahkan!',
        html:`Eksemplar <b>${stock.kode_eksemplar}</b> berhasil masuk keranjang.`,
        timer:1500, showConfirmButton:false
    });
}

document.getElementById('btnBackToBuku').addEventListener('click', function () {
    $('#modalPilihEksemplar').modal('hide');
    $('#modalPilihEksemplar').one('hidden.bs.modal', () => $('#modalPilihBuku').modal('show'));
});

// ── Ajukan Pinjaman ───────────────────────────────────────────────────────────
document.getElementById('btnAjukan').addEventListener('click', async function () {
    if (selectedItems.length === 0) {
        Swal.fire('Info', 'Silakan pilih buku terlebih dahulu.', 'info');
        return;
    }
    const { isConfirmed } = await Swal.fire({
        title: 'Konfirmasi Peminjaman',
        html:  `Ajukan peminjaman untuk <b>${selectedItems.length}</b> buku?`,
        icon:  'question', showCancelButton: true,
        confirmButtonColor: 'var(--primary)', confirmButtonText: 'Ya, Ajukan!', cancelButtonText: 'Batal',
    });
    if (!isConfirmed) return;

    Swal.fire({ title:'Memproses...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });
    try {
        const token = localStorage.getItem('token');
        const res   = await fetch('/api/transaksi-pinjam', {
            method: 'POST',
            headers: { 'Authorization':'Bearer '+token, 'Content-Type':'application/json', 'Accept':'application/json' },
            body: JSON.stringify({
                book_ids:       selectedItems.map(i => i.bookId),
                book_stock_ids: selectedItems.map(i => i.bookStockId),
            }),
        });
        const data = await res.json();
        if (res.ok) {
            Swal.fire('Berhasil!', data.message, 'success')
                .then(() => { window.location.href = "{{ route('anggota.buku') }}"; });
        } else {
            Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
        }
    } catch (e) {
        Swal.fire('Error', 'Gagal terhubung ke server.', 'error');
    }
});

// ── Reset ─────────────────────────────────────────────────────────────────────
document.getElementById('btnReset').addEventListener('click', function () {
    if (selectedItems.length === 0) return;
    Swal.fire({
        title:'Reset Keranjang?', text:'Semua buku pilihan akan dihapus.',
        icon:'warning', showCancelButton:true,
        confirmButtonColor:'#e74a3b', confirmButtonText:'Ya, Reset', cancelButtonText:'Batal',
    }).then(r => {
        if (r.isConfirmed) {
            selectedItems = [];
            renderKeranjang();
            renderBooksModal(allBooks);
            Swal.fire({ icon:'success', title:'Direset!', timer:1000, showConfirmButton:false });
        }
    });
});

// ── Init ──────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', async function () {
    await loadAturanPeminjaman();
    await loadBooksModal();

    // Dari buku.blade via URL: ?book_id=X&book_stock_id=Y&kode_eksemplar=Z
    const p    = new URLSearchParams(window.location.search);
    const bId  = p.get('book_id');
    const sId  = p.get('book_stock_id');
    const kode = p.get('kode_eksemplar');

    if (bId && sId && kode) {
        const book = allBooks.find(b => b.id == bId);
        if (book) {
            selectedItems.push({
                bookId:        bId,
                bookStockId:   sId,
                kodeEksemplar: decodeURIComponent(kode),
                book,
            });
            renderKeranjang();
            renderBooksModal(allBooks);
        }
    }
});
</script>

@endsection