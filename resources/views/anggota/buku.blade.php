@extends('layouts.anggota')

@section('title', 'Daftar Buku')

@section('content')

@php
  use App\Models\Category;
  $categoryId = request('category');
  $search = request('search');
  $allCategories = Category::all();
@endphp

<div class="container-fluid animate-up">
    <div class="header-container mb-4">
        <div class="header-left">
            <h1 class="h3 mb-1" style="color: var(--dark); font-weight: 700;">Daftar Buku</h1>
        </div>

        <div class="header-right">
            <div class="search-box">
                <i class="fas fa-search" style="color: var(--gray);"></i>
                <input type="text" id="searchInput" placeholder="Cari judul buku...">
            </div>

            <div class="dropdown" id="category-filter">
                <button class="btn-filter" type="button" id="categoryDropdown" data-toggle="dropdown">
                    <i class="fas fa-layer-group"></i>
                    <span id="selectedCategoryText">Kategori</span>
                    <i class="fas fa-chevron-down ml-auto"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item active" href="#" data-category="">Semua Kategori</a>
                    <div class="dropdown-divider"></div>
                    @foreach($allCategories as $cat)
                        <a class="dropdown-item" href="#" data-category="{{ $cat->name }}">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="book-list">
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted small">Menyiapkan koleksi buku...</p>
        </div>
    </div>
</div>

{{-- MODAL DETAIL BUKU --}}
<div class="modal fade" id="modalViewBook" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="close px-3 py-3" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="row">
                    <div class="col-md-5 mb-4 mb-md-0 text-center">
                        <div class="bg-light rounded-lg p-3 d-flex align-items-center justify-content-center shadow-sm" style="min-height: 350px;">
                            <img id="modalCover" class="img-fluid rounded shadow" style="max-height: 320px; object-fit: contain;" alt="Cover">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span id="modalKategori" class="badge badge-primary-soft text-overline px-3 py-2">Kategori</span>
                            <span id="statusText" class="small font-weight-bold"></span>
                        </div>
                        <h3 id="modalJudulText" class="font-weight-bold text-gray-800 mb-3 leading-tight">Judul Buku</h3>
                        <div class="row mb-4">
                            <div class="col-6 mb-2">
                                <label class="text-overline text-muted d-block mb-0">Penulis</label>
                                <span id="modalPenulis" class="font-weight-bold text-dark">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="text-overline text-muted d-block mb-0">Penerbit</label>
                                <span id="modalPenerbit" class="font-weight-bold text-dark">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="text-overline text-muted d-block mb-0">Tahun Terbit</label>
                                <span id="modalTahun" class="font-weight-bold text-dark">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="text-overline text-muted d-block mb-0">Lokasi Rak</label>
                                <span id="modalRak" class="font-weight-bold text-dark">-</span>
                            </div>
                        </div>
                        <label class="text-overline text-primary">Sinopsis</label>
                        <div id="modalDeskripsi" class="text-muted small p-3 bg-light rounded" style="max-height: 120px; overflow-y: auto; line-height: 1.6;">
                            Sinopsis tidak tersedia.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light p-3">
                <div class="w-100 d-flex justify-content-between align-items-center px-3">
                    <div>
                        <small class="text-muted font-weight-bold">Stok:</small>
                        <span id="modalStok" class="h5 font-weight-bold mb-0 ml-1 text-primary">0</span>
                    </div>
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" id="btnPinjamDirect">
                        <i class="fas fa-cart-plus mr-2"></i>Pinjam Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PILIH EKSEMPLAR --}}
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
                <div class="d-flex align-items-center p-2 rounded-lg" style="background: #f0f4ff; gap: 12px;">
                    <img id="eksemplarBookCover" src="" alt=""
                         style="width: 42px; height: 58px; object-fit: cover; border-radius: 6px; flex-shrink: 0;">
                    <div style="min-width: 0;">
                        <div class="font-weight-bold text-gray-800 small text-truncate" id="eksemplarBookJudul"></div>
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
        </div>
    </div>
</div>

<style>
    .text-overline { text-transform: uppercase; letter-spacing: 1px; font-weight: 800; font-size: 0.65rem; }
    .animate-up { animation: fadeInUp 0.5s ease-out forwards; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .header-container { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
    .header-right { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

    .search-box {
        position: relative; background: #fff; border-radius: 12px; padding: 0 15px;
        height: 45px; display: flex; align-items: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); min-width: 250px;
    }
    .search-box i { color: #4e73df; margin-right: 10px; }
    .search-box input { border: none; outline: none; font-size: 0.9rem; width: 100%; background: transparent; }

    .btn-filter {
        background: #fff; border: none; height: 45px; padding: 0 18px; border-radius: 12px;
        display: flex; align-items: center; gap: 10px; min-width: 160px; color: #5a5c69;
        font-weight: 600; font-size: 0.85rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: all 0.2s;
    }
    .btn-filter i:first-child { color: #4e73df; }
    .btn-filter i.ml-auto { font-size: 0.7rem; opacity: 0.5; }

    .button-group { display: flex; gap: 8px; }
    .btn-main {
        height: 45px; padding: 0 18px; border-radius: 12px; border: none;
        display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.85rem;
        transition: all 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .btn-main:hover { transform: translateY(-2px); filter: brightness(1.1); color: #fff; }
    .btn-white { background: white; transition: all 0.3s ease; }
    .btn-white:hover { background: #f8f9fc; }
    .dropdown-item { font-size: 0.85rem; padding: 10px 20px; font-weight: 500; transition: all 0.2s; }
    .dropdown-item:hover { padding-left: 25px; background: rgba(78,115,223,0.05); color: var(--primary); }

    .book-card { border-radius: 18px !important; overflow: hidden; border: none; transition: all 0.3s ease; }
    .book-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    .book-cover-wrapper { position: relative; height: 300px; background: #f8f9fa; overflow: hidden; }
    .book-cover-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .book-card:hover .book-cover-img { transform: scale(1.1); }
    .badge-success-soft { background: #dffff3; color: var(--success); }
    .book-badge { position: absolute; top: 12px; right: 12px; z-index: 2; }
    .badge-primary-soft { background: rgba(78, 115, 223, 0.1); color: var(--primary); }
    .btn-action { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border: none; }

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

    @media (max-width: 768px) {
        .header-container { flex-direction: column; align-items: flex-start; }
        .header-right { width: 100%; }
        .search-box, .btn-filter, .button-group { width: 100%; }
        .btn-main { flex: 1; justify-content: center; }
    }
</style>

<script>
let allBooks = [];

document.addEventListener('DOMContentLoaded', function () {
    const token        = localStorage.getItem('token');
    const bookList     = document.getElementById('book-list');
    const searchInput  = document.getElementById('searchInput');
    const categoryBtns = document.querySelectorAll('#category-filter .dropdown-item');
    const categoryDD   = document.getElementById('categoryDropdown');
    const categoryText = document.getElementById('selectedCategoryText');

    fetch('/api/list-books', {
        headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(res => { allBooks = res.data || []; renderBooks(allBooks); });

    function renderBooks(books) {
        if (books.length === 0) {
            bookList.innerHTML = `<div class="col-12 text-center py-5"><p class="text-muted">Buku tidak ditemukan.</p></div>`;
            return;
        }
        bookList.innerHTML = '';
        books.forEach(book => {
            const isAvailable = book.available_stock > 0;
            bookList.innerHTML += `
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card book-card shadow-sm h-100">
                    <div class="book-cover-wrapper">
                        <div class="book-badge">
                            <span class="badge shadow-sm px-2 py-1 ${isAvailable ? 'badge-success' : 'badge-danger'}" style="font-size: 0.65rem;">
                                ${isAvailable ? 'Tersedia' : 'Habis'}
                            </span>
                        </div>
                        <img src="${book.image || 'https://via.placeholder.com/300x400?text=No+Cover'}" class="book-cover-img">
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <span class="text-overline text-primary mb-1">${book.kategori || 'Umum'}</span>
                        <h6 class="font-weight-bold text-gray-800 mb-2"
                            style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;height:2.4em;">
                            ${book.judul}
                        </h6>
                        <small class="text-muted"><i class="fas fa-pen-nib mr-1"></i> ${book.penulis || '-'}</small>
                        <div class="mt-auto d-flex align-items-center justify-content-between pt-3">
                            <small class="font-weight-bold text-gray-700">Stok: ${book.available_stock}</small>
                            <div class="d-flex">
                                <button class="btn btn-light btn-action mr-2 text-primary shadow-sm" onclick="viewBook('${book.id}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-primary btn-action shadow-sm" onclick="pinjamBook('${book.id}')">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        });
    }

    function applyFilters() {
        const q   = searchInput.value.toLowerCase();
        const cat = categoryDD.dataset.selected || '';
        renderBooks(allBooks.filter(b =>
            (b.judul.toLowerCase().includes(q) || (b.penulis || '').toLowerCase().includes(q)) &&
            (cat === '' || b.kategori === cat)
        ));
    }

    searchInput.addEventListener('input', applyFilters);
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            categoryText.innerText      = this.innerText;
            categoryDD.dataset.selected = this.dataset.category;
            applyFilters();
        });
    });
});

function viewBook(id) {
    const book = allBooks.find(b => b.id == id);
    if (!book) return;

    document.getElementById('modalJudulText').innerText  = book.judul;
    document.getElementById('modalPenerbit').innerText   = book.penerbit;
    document.getElementById('modalKategori').innerText   = book.kategori || 'Umum';
    document.getElementById('modalRak').innerText        = `${book.rak} (${book.nomor_rak})`;
    document.getElementById('modalPenulis').innerText    = book.penulis || '-';
    document.getElementById('modalTahun').innerText      = book.tahun || '-';
    document.getElementById('modalStok').innerText       = book.available_stock || 0;
    document.getElementById('modalDeskripsi').innerText  = book.sinopsis || 'Sinopsis tidak tersedia.';
    document.getElementById('modalCover').src            = book.image || 'https://via.placeholder.com/300x400?text=No+Cover';

    const statusText = document.getElementById('statusText');
    statusText.innerText = book.available_stock > 0 ? '● TERSEDIA' : '● HABIS';
    statusText.className = book.available_stock > 0 ? 'small font-weight-bold text-success' : 'small font-weight-bold text-danger';

    const btnPinjam = document.getElementById('btnPinjamDirect');
    if (book.available_stock <= 0) {
        btnPinjam.classList.add('disabled');
        btnPinjam.innerHTML    = '<i class="fas fa-times-circle mr-2"></i>Stok Habis';
        btnPinjam.style.cursor = 'not-allowed';
        btnPinjam.onclick      = null;
    } else {
        btnPinjam.classList.remove('disabled');
        btnPinjam.innerHTML    = '<i class="fas fa-cart-plus mr-2"></i>Pinjam Sekarang';
        btnPinjam.style.cursor = 'pointer';
        btnPinjam.onclick = () => {
            $('#modalViewBook').modal('hide');
            $('#modalViewBook').one('hidden.bs.modal', () => openModalEksemplar(book.id));
        };
    }
    $('#modalViewBook').modal('show');
}

function pinjamBook(id) {
    const book = allBooks.find(b => b.id == id);
    if (!book) return;

    if (book.available_stock <= 0) {
        Swal.fire({
            icon: 'error', title: 'Stok Habis',
            text: `Buku "${book.judul}" saat ini tidak tersedia.`,
            confirmButtonColor: '#4e73df'
        });
        return;
    }
    openModalEksemplar(id);
}

function openModalEksemplar(bookId) {
    const book = allBooks.find(b => b.id == bookId);
    if (!book) return;

    document.getElementById('eksemplarBookJudul').innerText   = book.judul;
    document.getElementById('eksemplarBookPenulis').innerText = book.penulis || '-';
    document.getElementById('eksemplarBookCover').src         = book.image || 'https://via.placeholder.com/300x400?text=No+Cover';
    document.getElementById('eksemplarSubtitle').innerText    = `Tersedia ${book.available_stock} eksemplar — pilih satu`;

    document.getElementById('eksemplarLoading').style.display       = '';
    document.getElementById('eksemplarEmpty').style.display         = 'none';
    document.getElementById('eksemplarListContainer').style.display = 'none';
    document.getElementById('eksemplarListContainer').innerHTML     = '';

    $('#modalPilihEksemplar').modal('show');

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
        renderEksemplarList(stocks, book);
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

function renderEksemplarList(stocks, book) {
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
        item.addEventListener('click', () => konfirmasiEksemplar(book, stock));
        container.appendChild(item);
    });
}

function konfirmasiEksemplar(book, stock) {
    $('#modalPilihEksemplar').modal('hide');

    Swal.fire({
        icon: 'question',
        title: 'Konfirmasi Eksemplar',
        html: `
            <div style="text-align:left;">
                <div class="mb-3 pb-2" style="border-bottom:1px solid #e8ecf5;">
                    <div style="text-transform:uppercase;letter-spacing:1px;font-weight:800;font-size:0.65rem;color:#a0aec0;margin-bottom:4px;">Buku</div>
                    <div style="font-weight:700;color:#2d3748;">${book.judul}</div>
                    <div style="font-size:0.8rem;color:#718096;">${book.penulis || '-'}</div>
                </div>
                <div>
                    <div style="text-transform:uppercase;letter-spacing:1px;font-weight:800;font-size:0.65rem;color:#a0aec0;margin-bottom:4px;">Eksemplar Dipilih</div>
                    <div style="font-size:1.05rem;font-weight:700;color:#4e73df;">
                        <i class="fas fa-barcode mr-2"></i>${stock.kode_eksemplar}
                    </div>
                </div>
            </div>`,
        showCancelButton:   true,
        confirmButtonColor: 'var(--primary)',
        cancelButtonColor:  'var(--secondary)',
        confirmButtonText:  '<i class="fas fa-cart-plus mr-1"></i> Tambah ke Keranjang',
        cancelButtonText:   '<i class="fas fa-arrow-left mr-1"></i> Ganti Eksemplar',
    }).then(result => {
        if (result.isConfirmed) {
            const url = `/anggota/peminjaman/create`
                      + `?book_id=${book.id}`
                      + `&book_stock_id=${stock.id}`
                      + `&kode_eksemplar=${encodeURIComponent(stock.kode_eksemplar)}`;
            window.location.href = url;
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            openModalEksemplar(book.id);
        }
    });
}
</script>

@endsection