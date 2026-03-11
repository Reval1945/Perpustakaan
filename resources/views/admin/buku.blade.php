@extends('layouts.admin')

@section('title', 'Data Buku')

@section('content')

@php
  use App\Models\Category;
  $allCategories = Category::all();
@endphp

<div class="container-fluid animate-up">
    {{-- Header --}}
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

            <div class="button-group">
                <button id="btnTambahBuku" class="btn-main btn-add" data-toggle="modal" data-target="#modalTambahBuku">
                    <i class="fas fa-plus"></i><span>Tambah</span>
                </button>
                <button id="btnCetakBuku" class="btn-main btn-export">
                    <i class="fas fa-print"></i><span>Export</span>
                </button>
            </div>
        </div>
    </div>

    <!-- DAFTAR BUKU -->
    <div class="row" id="bookList">
        <div class="col-12 text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted small">Memuat data buku...</p>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Buku -->
<div class="modal fade" id="modalTambahBuku" tabindex="-1" role="dialog" aria-labelledby="modalTambahBukuLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 18px; overflow: hidden;">
            
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <h5 class="modal-title font-weight-bold text-gray-800" id="modalTambahBukuLabel">
                    <i class="fas fa-plus-circle text-primary mr-2"></i> Tambah / Edit Buku
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4 pt-2">
                <form>
                    <input type="hidden" id="book_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="text-overline text-primary d-block mb-1">
                                    <i class="fas fa-book mr-1"></i> Judul Buku
                                </label>
                                <input type="text" id="judul" class="form-control rounded-pill border-0 bg-light" placeholder="Masukkan judul buku" required style="height: 40px;">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="text-overline text-primary d-block mb-1">
                                    <i class="fas fa-barcode mr-1"></i> Kode Buku
                                </label>
                                <input type="text" id="kode_buku" class="form-control rounded-pill border-0 bg-light" placeholder="Contoh: BK-001" style="height: 40px;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="text-overline text-primary d-block mb-1">
                                    <i class="fas fa-user-edit mr-1"></i> Penulis
                                </label>
                                <input type="text" id="penulis" class="form-control rounded-pill border-0 bg-light" placeholder="Nama penulis" style="height: 40px;">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="text-overline text-primary d-block mb-1">
                                    <i class="fas fa-building mr-1"></i> Penerbit
                                </label>
                                <input type="text" id="penerbit" class="form-control rounded-pill border-0 bg-light" placeholder="Nama penerbit" style="height: 40px;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="text-overline text-primary d-block mb-1">
                            <i class="fas fa-align-left mr-1"></i> Sinopsis
                        </label>
                        <textarea id="sinopsis" class="form-control rounded-lg border-0 bg-light" rows="2" placeholder="Masukkan sinopsis buku..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="text-overline text-primary d-block mb-1">
                                    <i class="fas fa-calendar-alt mr-1"></i> Tahun Terbit
                                </label>
                                <input type="number" id="tahun" class="form-control rounded-pill border-0 bg-light" placeholder="2024" style="height: 40px;">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="text-overline text-primary d-block mb-1">
                                    <i class="fas fa-tags mr-1"></i> Kategori
                                </label>
                                <select id="category_id" class="form-control rounded-pill border-0 bg-light" style="height: 40px;">
                                    <option value="" selected disabled>-- Pilih Kategori --</option>
                                    @foreach($allCategories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group mb-2">
                                        <label class="text-overline text-primary d-block mb-1">
                                            <i class="fas fa-archive mr-1"></i> Rak
                                        </label>
                                        <input type="text" id="rak" class="form-control rounded-pill border-0 bg-light" placeholder="A" style="height: 40px;">
                                    </div>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <div class="form-group mb-2">
                                        <label class="text-overline text-primary d-block mb-1">
                                            <i class="fas fa-hashtag mr-1"></i> No. Rak
                                        </label>
                                        <input type="text" id="nomor_rak" class="form-control rounded-pill border-0 bg-light" placeholder="03" style="height: 40px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                   <div class="form-group mb-2">
                        <label class="text-overline text-primary d-block mb-1">
                            <i class="fas fa-image mr-1"></i> Cover Buku
                        </label>
                        <div class="custom-file border-0">
                            <input type="file" id="image" class="custom-file-input" accept="image/*">
                            <label class="custom-file-label border-0 bg-light input-custom-style" for="image" style="height: 40px; border-radius: 50px; display: flex; align-items: center; padding-left: 20px;">
                                Pilih file gambar (jpg, png)
                            </label>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-secondary rounded px-3 shadow-sm" data-dismiss="modal" style="height: 38px;">
                    <i class="mr-1"></i> Batal
                </button>
                <button type="button" id="btnSaveBook" class="btn btn-primary rounded px-4 shadow-sm" style="height: 38px;">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Modal Kelola Stok -->
<div class="modal fade" id="modalTambahStok" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 450px;">
        <div class="modal-content border-0 shadow" style="border-radius: 18px; overflow: hidden;">
            
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <h5 class="modal-title font-weight-bold text-gray-800">
                    <i class="fas fa-cubes text-primary mr-2"></i> Kelola Stok Buku
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4 pt-2">
                <input type="hidden" id="stok_book_id">

                <div class="form-group mb-3">
                    <label class="text-overline text-primary d-block mb-1">
                        <i class="fas fa-barcode mr-1"></i> Kode Eksemplar 
                    </label>
                    <div class="d-flex">
                        <input type="text" id="kode_eksemplar" class="form-control rounded-pill border-0 bg-light mr-2" placeholder="Contoh: BK01" style="height: 42px;">
                        <button class="btn btn-primary rounded-pill px-3 shadow-sm" id="btnTambahStok" style="height: 42px;">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <small class="ml-3 align-self-center" style="color: var(--gray);">Jika ingin menambah stok banyak secara langsung : BK01-BK10</small>
                </div>

                <hr class="my-3">

                <h6 class="font-weight-bold text-gray-700 mb-2">
                    <i class="fas fa-list mr-2 text-primary"></i>Daftar Kode Buku
                </h6>
                <ul class="list-group" id="listStok" style="max-height: 280px; overflow-y: auto;"></ul>
            </div>

        </div>
    </div>
</div>

<!-- MODAL VIEW -->
<div class="modal fade" id="modalViewBook" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 18px; overflow: hidden;">
            
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <div>
                    <h5 class="modal-title font-weight-bold text-gray-800 mb-1" id="modalJudul">
                        <i class="fas fa-book text-primary mr-2"></i>
                        <span id="modalJudulText"></span>
                    </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="bg-light rounded p-3 d-flex align-items-center justify-content-center shadow-sm" style="min-height: 250px;">
                            <img id="modalCover" class="img-fluid rounded shadow" style="max-height: 220px; object-fit: contain;" alt="Cover Buku">
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span id="modalKategori" class="badge badge-primary-soft px-3 py-2">Kategori</span>
                            <span id="statusText" class="small font-weight-bold"></span>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6 mb-2">
                                <label class="text-overline text-muted d-block mb-0">Penerbit</label>
                                <span id="modalPenerbit" class="font-weight-bold text-dark">-</span>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="text-overline text-muted d-block mb-0">Penulis</label>
                                <span id="modalPenulis" class="font-weight-bold text-dark">-</span>
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
                        <div id="modalDeskripsi" class="text-muted p-3 bg-light rounded" style="max-height: 100px; overflow-y: auto; line-height: 1.5;">
                            Sinopsis tidak tersedia.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 bg-light p-3">
                <div class="w-100 d-flex justify-content-between align-items-center px-3">
                    <div>
                        <small class="text-muted font-weight-bold">Stok Tersedia:</small>
                        <span id="modalStok" class="h5 font-weight-bold mb-0 ml-2 text-primary">0</span>
                    </div>
                    <button type="button" class="btn btn-light rounded px-4 shadow-sm" data-dismiss="modal" style="height: 38px;">
                        <i class="fas fa-times mr-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Global Styling */
    .text-overline { 
        text-transform: uppercase; 
        letter-spacing: 0.8px; 
        font-weight: 700; 
        font-size: 0.6rem; 
    }
    
    .animate-up { 
        animation: fadeInUp 0.5s ease-out forwards; 
    }
    
    @keyframes fadeInUp { 
        from { 
            opacity: 0; 
            transform: translateY(20px); 
        } 
        to { 
            opacity: 1; 
            transform: translateY(0); 
        } 
    }

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

    .btn-add { background: var(--primary); color: #fff; }
    .btn-export { background: #1cc88a; color: #fff; }

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

    /* Book Cards */
    .book-card { 
        border-radius: 16px !important; 
        overflow: hidden; 
        border: none; 
        transition: all 0.3s ease; 
    }
    
    .book-card:hover { 
        transform: translateY(-6px); 
        box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important; 
    }
    
    .book-cover-wrapper { 
        position: relative; 
        height: 300px; 
        background: #f8f9fa; 
        overflow: hidden; 
    }
    
    .book-cover-img { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        transition: transform 0.5s ease; 
    }
    
    .book-card:hover .book-cover-img { 
        transform: scale(1.1); 
    }
    
    .book-badge { 
        position: absolute; 
        top: 10px; 
        right: 10px; 
        z-index: 2; 
    }

    .badge-primary-soft { 
        background: rgba(78, 115, 223, 0.1); 
        color: var(--primary); }
    
    .badge-success-soft { background: #dffff3; color: var(--success); }
    .badge-warning-soft { background: #fff9e6; color: var(--warning); }
    .badge-danger-soft { background: #ffebeb; color: var(--danger); }
    
    .btn-action { 
        width: 34px; 
        height: 34px; 
        border-radius: 9px; 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        transition: all 0.2s; 
        border: none; 
        margin: 0 2px;
        font-size: 0.8rem;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
    }

    /* Menghilangkan border default dan menyamakan tinggi label */
    .custom-file-label {
        line-height: 1.5;
        color: #6e707e; /* Warna placeholder agar sama dengan input lain */
    }

    /* Menyesuaikan tombol "Browse" (::after) agar masuk ke dalam desain pill */
    .custom-file-label::after {
        height: 40px !important;
        display: flex;
        align-items: center;
        background-color: #eaecf4 !important; /* Warna abu-abu soft agar senada dengan bg-light */
        border: none !important;
        padding: 0 20px !important;
    }

    /* Menghilangkan ring biru saat fokus agar konsisten dengan style input Anda */
    .custom-file-input:focus ~ .custom-file-label {
        box-shadow: none !important;
        border: none !important;
    }

    /* Stock List */
    .list-group-item {
        border-radius: 10px !important;
        margin-bottom: 6px;
        border: none;
        background: #f8f9fc;
        padding: 0.6rem 0.8rem;
    }
    
    .list-group-item:hover {
        background: white;
        box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    }

    /* Form Controls */
    .form-control {
        font-size: 0.9rem;
    }
    
    .form-control:focus {
        box-shadow: 0 3px 10px rgba(78, 115, 223, 0.1);
        border-color: transparent;
    }
    
</style>

<!-- Script untuk menampilkan nama file yang dipilih -->
<script>
    document.querySelector('.custom-file-input')?.addEventListener('change', function(e) {
        var fileName = e.target.files[0]?.name || 'Pilih file gambar (jpg, png)';
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>

<!-- DATA BUKU (Script tetap sama) -->
<script>
let allBooks = []; // Master data buku
const API = 'http://127.0.0.1:8000/api';
const token = localStorage.getItem('token');

document.addEventListener('DOMContentLoaded', () => {
    fetchBooks();
    
    const searchInput = document.getElementById('searchInput');
    const categoryDropdownBtn = document.getElementById('categoryDropdown');
    const categoryText = document.getElementById('selectedCategoryText');
    const categoryItems = document.querySelectorAll('#category-filter .dropdown-item');

    // 1. Listener Search
    searchInput?.addEventListener('input', applyFilters);

    // 2. Listener Dropdown Kategori
    categoryItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // UI Update
            categoryItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            categoryText.innerText = this.innerText;

            // Logic Update: Simpan kategori terpilih di atribut data tombol
            categoryDropdownBtn.dataset.selected = this.dataset.category || "";

            applyFilters();
        });
    });
});

/**
 * FUNGSI FILTER GABUNGAN
 */
function applyFilters() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const selectedCat = document.getElementById('categoryDropdown').dataset.selected || "";

    const filtered = allBooks.filter(book => {
        const matchSearch = 
            (book.judul || "").toLowerCase().includes(query) || 
            (book.penulis || "").toLowerCase().includes(query) ||
            (book.penerbit || "").toLowerCase().includes(query);

        const matchCat = selectedCat === "" || book.kategori === selectedCat;

        return matchSearch && matchCat;
    });

    renderBooks(filtered);
}

/**
 * AMBIL DATA DARI API
 */
async function fetchBooks(){
    try {
        const res = await fetch(`${API}/books`, {
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${token}`
            }
        });

        if(!res.ok) throw new Error("Fetch gagal");

        const result = await res.json();
        allBooks = result.data || []; 
        renderBooks(allBooks); 

    } catch(err) {
        console.error(err);
        document.getElementById('bookList').innerHTML = `
            <div class="col-12 text-center py-4">
                <p class="text-muted">Gagal memuat data buku.</p>
            </div>`;
    }
}

function renderBooks(books){
    const container = document.getElementById('bookList');
    if(!container) return;

    if(books.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-4">
                <p class="text-muted">Tidak ada buku ditemukan.</p>
            </div>`;
        return;
    }

    container.innerHTML = '';

    books.forEach(book => {
        const image = book.image || 'https://via.placeholder.com/300x400?text=No+Cover';
        const stok = book.available_stock ?? 0;
        const isAvailable = stok > 0;

        container.innerHTML += `
        <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
            <div class="card book-card shadow-sm h-100">
                <div class="book-cover-wrapper">
                    <div class="book-badge">
                        <span class="badge shadow-sm px-2 py-1 ${isAvailable ? 'badge-success' : 'badge-danger'}">
                            ${isAvailable ? 'Tersedia' : 'Habis'}
                        </span>
                    </div>
                    <img src="${image}" class="book-cover-img" alt="${book.judul}">
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <span class="text-overline text-primary mb-1">${book.kategori ?? 'Umum'}</span>
                    <h6 class="font-weight-bold text-gray-800 mb-1" style="font-size: 0.9rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.3em;">${book.judul}</h6>
                    <small class="text-muted"><i class="fas fa-building mr-1"></i> ${book.penerbit ?? '-'}</small>
                    
                    <div class="mt-auto d-flex align-items-center justify-content-between pt-2">
                        <small class="font-weight-bold text-gray-700">Stok: ${stok}</small>
                        <div class="d-flex">
                            <button class="btn btn-light btn-action mr-1 text-primary shadow-sm action" data-type="show" data-book='${JSON.stringify(book).replace(/'/g, "&#39;")}'>
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-light btn-action mr-1 text-warning shadow-sm action" data-type="edit" data-book='${JSON.stringify(book).replace(/'/g, "&#39;")}'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-light btn-action mr-1 text-danger shadow-sm action" data-type="delete" data-id="${book.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-primary btn-action shadow-sm btn-stok" data-id="${book.id}">
                                <i class="fas fa-cubes"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    });
}

// Simpan / Update Buku
document.addEventListener('click', async e => {
    if(!e.target.closest('#btnSaveBook')) return;

    const id = document.getElementById('book_id').value;
    const form = new FormData();

    ['judul', 'sinopsis', 'kode_buku', 'category_id', 'penulis', 'penerbit', 'tahun', 'rak', 'nomor_rak']
        .forEach(field => {
            const el = document.getElementById(field);
            if(el) form.append(field, el.value);
        });

    const img = document.getElementById('image')?.files[0];
    if(img) form.append('image', img);

    let url = `${API}/books`;
    if(id){
        url += `/${id}`;
        form.append('_method', 'PUT');
    }

    try{
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json'
            },
            body: form
        });

        const result = await res.json();

        if(result.errors){
            Swal.fire({
                icon: 'error',
                title: 'Gagal Validasi',
                text: Object.values(result.errors)[0][0]
            });
            return;
        }

        $('#modalTambahBuku').modal('hide');
        
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data buku berhasil disimpan.',
            showConfirmButton: false,
            timer: 1500
        });

        fetchBooks();
        resetBookForm();

    }catch(err){
        console.error(err);
        Swal.fire('Error', 'Gagal menyimpan buku', 'error');
    }
});

// Action Buttons (Edit, Show, Delete)
document.addEventListener('click', async e => {
    const btn = e.target.closest('.action');
    if(!btn) return;

    const type = btn.dataset.type;

    if(type === 'edit'){
        const book = JSON.parse(btn.dataset.book);
        document.getElementById('book_id').value = book.id;

        ['judul', 'sinopsis', 'kode_buku', 'category_id', 'penulis', 'penerbit', 'tahun', 'rak', 'nomor_rak']
            .forEach(k => {
                const el = document.getElementById(k);
                if(el) el.value = book[k] ?? '';
            });

        $('#modalTambahBuku').modal('show');
    }

    if(type === 'show'){
        const book = JSON.parse(btn.dataset.book);
        document.getElementById('modalJudulText').innerText = book.judul;
        document.getElementById('modalPenerbit').innerText = book.penerbit ?? '-';
        document.getElementById('modalKategori').innerText = book.kategori ?? '-';
        document.getElementById('modalRak').innerText = `${book.rak ?? '-'} - ${book.nomor_rak ?? '-'}`;
        document.getElementById('modalStok').innerText = book.available_stock ?? 0;
        document.getElementById('modalPenulis').innerText = book.penulis ?? '-';
        document.getElementById('modalTahun').innerText = book.tahun ?? '-';
        document.getElementById('modalDeskripsi').innerText = book.sinopsis || 'Sinopsis tidak tersedia.';
        document.getElementById('modalCover').src = book.image || 'https://via.placeholder.com/300x400?text=No+Cover';

        const statusText = document.getElementById('statusText');
        const kategori = document.getElementById('modalKategori');

        statusText.innerHTML = book.available_stock > 0 
            ? '<span class="badge badge-success px-3 py-2">● TERSEDIA</span>' 
            : '<span class="badge badge-danger px-3 py-2">● HABIS</span>';

        kategori.innerHTML = book.kategori || 'Umum';
        kategori.className = 'badge badge-primary-soft px-3 py-2';

        $('#modalViewBook').modal('show');
    }

    if(type === 'delete'){
        const id = btn.dataset.id;

        Swal.fire({
            title: 'Hapus Buku?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try{
                    await fetch(`${API}/books/${id}`, {
                        method: 'DELETE',
                        headers: { Authorization: `Bearer ${token}` }
                    });
                    fetchBooks();
                    Swal.fire('Terhapus!', 'Buku telah dihapus dari sistem.', 'success');
                }catch(err){
                    Swal.fire('Gagal', 'Gagal menghapus buku', 'error');
                }
            }
        });
    }
});

// Manajemen Stok (Eksemplar)
let currentBookId = null;

document.addEventListener('click', async function(e){
    const btn = e.target.closest('.btn-stok');
    if(!btn) return;

    currentBookId = btn.dataset.id;
    document.getElementById('stok_book_id').value = currentBookId;

    $('#modalTambahStok').modal('show');
    await loadStok(currentBookId);
});

async function loadStok(bookId){
    try {
        const res = await fetch(`${API}/books/${bookId}/stok`, {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });

        const data = await res.json();
        const list = document.getElementById('listStok');
        list.innerHTML = '';

        if(!data.data || data.data.length === 0){
            list.innerHTML = '<li class="list-group-item text-muted text-center">Belum ada stok</li>';
            return;
        }

        data.data.forEach(stok => {
            let badgeClass = 'badge-secondary-soft';
            if(stok.status === 'tersedia') badgeClass = 'badge-success-soft';
            else if(stok.status === 'dipinjam') badgeClass = 'badge-warning-soft';
            else if(stok.status === 'rusak') badgeClass = 'badge-danger-soft';
            else if(stok.status === 'hilang') badgeClass = 'badge-danger-soft';

            list.innerHTML += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong class="text-gray-800">${stok.kode_eksemplar}</strong><br>
                    <span class="badge ${badgeClass} mt-1">${stok.status}</span>
                </div>
                <button class="btn btn-danger btn-sm rounded-pill px-2 btn-hapus-stok shadow-sm" data-id="${stok.id}" style="height: 32px;">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </li>`;
        });
    } catch (err) {
        console.error(err);
    }
}

// Tambah Stok
document.getElementById('btnTambahStok').addEventListener('click', async () => {

    const bookId = document.getElementById('stok_book_id').value;
    const kodeInput = document.getElementById('kode_eksemplar').value.trim();

    if(!kodeInput){
        Swal.fire('Peringatan', 'Kode eksemplar harus diisi', 'warning');
        return;
    }

    let daftarKode = [];

        const rangePattern = /^([A-Za-z]+)(\d+)-([A-Za-z]+)(\d+)$/;

        if(rangePattern.test(kodeInput)){

            const match = kodeInput.match(rangePattern);

            const prefix = match[1];
            const startNum = parseInt(match[2]);
            const endNum = parseInt(match[4]);

            const digitLength = match[2].length; // jumlah digit dari input

            for(let i = startNum; i <= endNum; i++){

                let number = String(i).padStart(digitLength,'0');
                daftarKode.push(prefix + number);

            }

        }else{

            daftarKode.push(kodeInput);

        }

    try {

        for(const kode of daftarKode){

            const res = await fetch(`${API}/books/${bookId}/stok`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ kode_eksemplar: kode })
            });

            if(!res.ok){
                const data = await res.json();
                Swal.fire('Gagal', data.message || 'Gagal tambah stok', 'error');
                return;
            }
        }

        document.getElementById('kode_eksemplar').value = '';

        await loadStok(bookId);
        fetchBooks();

        Swal.fire({
            toast: true,
            position: 'center',
            icon: 'success',
            title: 'Stok berhasil ditambahkan',
            showConfirmButton: false,
            timer: 1500
        });

    } catch (err) {

        Swal.fire('Error', 'Gagal menambah stok', 'error');

    }

});

// Hapus Stok Eksemplar
document.addEventListener('click', async function(e){
    const btn = e.target.closest('.btn-hapus-stok');
    if(!btn) return;

    Swal.fire({
        title: 'Hapus eksemplar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus'
    }).then(async (result) => {
        if (result.isConfirmed) {
            const id = btn.dataset.id;
            const bookId = document.getElementById('stok_book_id').value;

            try {
                await fetch(`${API}/stok/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });
                await loadStok(bookId);
                fetchBooks();
                Swal.fire('Berhasil', 'Kode eksemplar dihapus', 'success');
            } catch (err) {
                Swal.fire('Gagal', 'Gagal menghapus stok', 'error');
            }
        }
    });
});

function resetBookForm(){
    document.getElementById('book_id').value = '';
    const formEl = document.querySelector('#modalTambahBuku form');
    if(formEl) formEl.reset();
    
    const fileLabel = document.querySelector('.custom-file-label');
    if(fileLabel) fileLabel.innerText = 'Pilih file gambar (jpg, png)';
}

document.addEventListener('click', e => {
    if(e.target.closest('[data-target="#modalTambahBuku"]')) {
        setTimeout(resetBookForm, 100);
    }
});

// Export Excel
document.getElementById('btnCetakBuku').addEventListener('click', async () => {
    Swal.fire({
        title: 'Sedang Memproses',
        text: 'Mohon tunggu sebentar...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    try {
        const res = await fetch(`${API}/books/export/excel`, {
            headers: { Authorization: `Bearer ${token}` }
        });

        if (!res.ok) throw new Error('Fetch export gagal');

        const blob = await res.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'data-buku.xlsx';
        a.click();
        window.URL.revokeObjectURL(url);
        
        Swal.close();
    } catch (err) {
        console.error(err);
        Swal.fire('Gagal', 'Gagal mengekspor data ke Excel', 'error');
    }
});
</script>

@endsection