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
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Daftar Buku</h1>
            <p class="text-muted small mb-0">Temukan buku yang Anda cari.</p>
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

<div class="modal fade" id="modalViewBook" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="close px-3 py-3" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
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

<style>
    /* Global Styling */
    .text-overline { text-transform: uppercase; letter-spacing: 1px; font-weight: 800; font-size: 0.65rem; }
    .animate-up { animation: fadeInUp 0.5s ease-out forwards; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* Search & Filter */
       /* Container Utama Header */
    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap; /* Agar aman di layar HP */
        gap: 15px;
    }

    /* Bagian Kanan (Controls) */
    .header-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Styling Search Bar */
    .search-box {
        position: relative;
        background: #fff;
        border-radius: 12px;
        padding: 0 15px;
        height: 45px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        min-width: 250px;
    }

    .search-box i { color: #4e73df; margin-right: 10px; }
    .search-box input {
        border: none;
        outline: none;
        font-size: 0.9rem;
        width: 100%;
        background: transparent;
    }

    /* Styling Dropdown Filter */
    .btn-filter {
        background: #fff;
        border: none;
        height: 45px;
        padding: 0 18px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 160px;
        color: #5a5c69;
        font-weight: 600;
        font-size: 0.85rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }

    .btn-filter i:first-child { color: #4e73df; }
    .btn-filter i.ml-auto { font-size: 0.7rem; opacity: 0.5; }

    /* Button Group & Main Buttons */
    .button-group {
        display: flex;
        gap: 8px;
    }

    .btn-main {
        height: 45px;
        padding: 0 18px;
        border-radius: 12px;
        border: none;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-main:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
        color: #fff;
    }

    /* Responsif untuk HP */
    @media (max-width: 768px) {
        .header-container { flex-direction: column; align-items: flex-start; }
        .header-right { width: 100%; }
        .search-box, .btn-filter, .button-group { width: 100%; }
        .btn-main { flex: 1; justify-content: center; }
    }
    
    .btn-white { background: white; transition: all 0.3s ease; }
    .btn-white:hover { background: #f8f9fc; transform: translateY(-1px); }
    .dropdown-item { font-size: 0.85rem; padding: 10px 20px; font-weight: 500; transition: all 0.2s; }
    .dropdown-item:hover { padding-left: 25px; background: rgba(78, 115, 223, 0.05); color: var(--primary); }

    /* Book Card */
    .book-card { border-radius: 18px !important; overflow: hidden; border: none; transition: all 0.3s ease; }
    .book-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    .book-cover-wrapper { position: relative; height: 300px; background: #f8f9fa; overflow: hidden; }
    .book-cover-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .book-card:hover .book-cover-img { transform: scale(1.1); }
    .book-badge { position: absolute; top: 12px; right: 12px; z-index: 2; }
    .badge-primary-soft { background: rgba(78, 115, 223, 0.1); color: var(--primary); }
    
    .btn-action { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border: none; }
</style>

<script>
let allBooks = [];

document.addEventListener('DOMContentLoaded', function () {
    const token = localStorage.getItem('token');
    const bookList = document.getElementById('book-list');
    const searchInput = document.getElementById('searchInput');
    const categoryButtons = document.querySelectorAll('#category-filter .dropdown-item');
    const categoryDropdown = document.getElementById('categoryDropdown');
    const categoryText = document.getElementById('selectedCategoryText');

    // Fetch Data
    fetch('/api/list-books', {
        headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(res => {
        allBooks = res.data || [];
        renderBooks(allBooks);
    });

    // Render Function
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
                        <h6 class="font-weight-bold text-gray-800 mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.4em;">${book.judul}</h6>
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

    // Filter Logic
    function applyFilters() {
        const query = searchInput.value.toLowerCase();
        const selectedCat = categoryDropdown.dataset.selected || "";

        const filtered = allBooks.filter(b => {
            const matchSearch = b.judul.toLowerCase().includes(query) || b.penulis.toLowerCase().includes(query);
            const matchCat = selectedCat === "" || b.kategori === selectedCat;
            return matchSearch && matchCat;
        });
        renderBooks(filtered);
    }

    searchInput.addEventListener('input', applyFilters);

    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            categoryButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            categoryText.innerText = this.innerText;
            categoryDropdown.dataset.selected = this.dataset.category;
            applyFilters();
        });
    });
});

// Modal Logic
function viewBook(id) {
    const book = allBooks.find(b => b.id == id);
    if (!book) return;

    document.getElementById('modalJudulText').innerText = book.judul;
    document.getElementById('modalPenerbit').innerText = book.penerbit;
    document.getElementById('modalKategori').innerText = book.kategori || 'Umum';
    document.getElementById('modalRak').innerText = `${book.rak} (${book.nomor_rak})`;
    document.getElementById('modalPenulis').innerText = book.penulis || '-';
    document.getElementById('modalTahun').innerText = book.tahun || '-';
    document.getElementById('modalStok').innerText = book.available_stock || 0;
    document.getElementById('modalDeskripsi').innerText = book.sinopsis || 'Sinopsis tidak tersedia.';
    document.getElementById('modalCover').src = book.image || 'https://via.placeholder.com/300x400?text=No+Cover';
    
    const statusText = document.getElementById('statusText');
    statusText.innerText = book.available_stock > 0 ? '● TERSEDIA' : '● HABIS';
    statusText.className = book.available_stock > 0 ? 'small font-weight-bold text-success' : 'small font-weight-bold text-danger';

    document.getElementById('btnPinjamDirect').onclick = () => pinjamBook(book.id);
    const btnPinjam = document.getElementById('btnPinjamDirect');
        if (book.available_stock <= 0) {
            btnPinjam.classList.add('disabled');
            btnPinjam.innerHTML = '<i class="fas fa-times-circle mr-2"></i>Stok Habis';
            btnPinjam.style.cursor = 'not-allowed';
        } else {
            btnPinjam.classList.remove('disabled');
            btnPinjam.innerHTML = '<i class="fas fa-cart-plus mr-2"></i>Pinjam Sekarang';
            btnPinjam.style.cursor = 'pointer';
        }

        $('#modalViewBook').modal('show');
    }

function pinjamBook(id) {
    // Cari data buku berdasarkan ID
    const book = allBooks.find(b => b.id == id);

    // Cek jika stok nol atau buku tidak ditemukan
    if (book && book.available_stock <= 0) {
        // Jika kamu menggunakan SweetAlert2 (Disarankan)
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Stok Habis',
                text: 'Maaf, buku "' + book.judul + '" saat ini tidak tersedia untuk dipinjam.',
                confirmButtonColor: '#4e73df'
            });
        } else {
            // Fallback ke alert biasa jika SweetAlert tidak ada
            alert('Maaf, stok buku ini sedang habis.');
        }
        return; // Hentikan proses
    }

    // Jika stok ada, arahkan ke halaman peminjaman
    window.location.href = `/anggota/peminjaman/create?book_id=${id}`;
}
</script>
@endsection