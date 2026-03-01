@extends('layouts.anggota')

@section('title', 'Daftar Buku')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Daftar Buku</h1>

<div class="row" id="book-list">
    <div class="col-12 text-center text-muted">Loading buku...</div>
</div>

<!-- MODAL VIEW -->
<div class="modal fade" id="modalViewBook" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white py-3">
        <div class="w-100">
          <h5 class="modal-title font-weight-bold mb-1" id="modalJudul">
            <i class="fas fa-book mr-2"></i>
            <span id="modalJudulText"></span>
          </h5>
          <small class="text-white-50">
            <i class="fas fa-info-circle mr-1"></i>
            Detail Informasi Buku
          </small>
        </div>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body p-4">
        <div class="row">
          <!-- Cover Card -->
          <div class="col-md-4 mb-4 mb-md-0">
            <div class="card border-0 shadow h-100">
              <div class="card-body p-0">
                <div class="p-3 bg-light rounded-top">
                  <img id="modalCover" class="img-fluid rounded mx-auto d-block" 
                       style="height: 250px; object-fit: contain; width: 100%;"
                       alt="Cover Buku">
                </div>
                <div class="p-3">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="font-weight-bold text-muted">
                      <i class="fas fa-cubes mr-1"></i>Stok
                    </span>
                    <span id="modalStok" class="badge badge-primary badge-pill px-3 py-2">0</span>
                  </div>

                </div>
              </div>
            </div>
          </div>
          
          <!-- Details Card -->
          <div class="col-md-8">
            <div class="card border-0 shadow h-100">
              <div class="card-body">
                <!-- Info Grid dengan penambahan penulis dan tahun -->
                <div class="row">
                  <!-- Baris 1: Penerbit dan Kategori -->
                  <div class="col-md-6 mb-3">
                    <div class="card border bg-light h-100">
                      <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                          <div class="rounded-circle bg-white p-2 mr-3 shadow-sm">
                            <i class="fas fa-building text-primary"></i>
                          </div>
                          <div>
                            <small class="text-muted d-block">Penerbit</small>
                            <span id="modalPenerbit" class="font-weight-bold text-dark">-</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-6 mb-3">
                    <div class="card border bg-light h-100">
                      <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                          <div class="rounded-circle bg-white p-2 mr-3 shadow-sm">
                            <i class="fas fa-tag text-primary"></i>
                          </div>
                          <div>
                            <small class="text-muted d-block">Kategori</small>
                            <span id="modalKategori" class="font-weight-bold text-dark">-</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Baris 2: Penulis dan Tahun Terbit -->
                  <div class="col-md-6 mb-3">
                    <div class="card border bg-light h-100">
                      <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                          <div class="rounded-circle bg-white p-2 mr-3 shadow-sm">
                            <i class="fas fa-user-edit text-primary"></i>
                          </div>
                          <div>
                            <small class="text-muted d-block">Penulis</small>
                            <span id="modalPenulis" class="font-weight-bold text-dark">-</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-6 mb-3">
                    <div class="card border bg-light h-100">
                      <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                          <div class="rounded-circle bg-white p-2 mr-3 shadow-sm">
                            <i class="fas fa-calendar-alt text-primary"></i>
                          </div>
                          <div>
                            <small class="text-muted d-block">Tahun Terbit</small>
                            <span id="modalTahun" class="font-weight-bold text-dark">-</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Baris 3: Lokasi Rak dan Status -->
                  <div class="col-md-6 mb-3">
                    <div class="card border bg-light h-100">
                      <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                          <div class="rounded-circle bg-white p-2 mr-3 shadow-sm">
                            <i class="fas fa-th-large text-primary"></i>
                          </div>
                          <div>
                            <small class="text-muted d-block">Lokasi Rak</small>
                            <span id="modalRak" class="font-weight-bold text-dark">-</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-6 mb-3">
                    <div class="card border bg-light h-100">
                      <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                          <div class="rounded-circle bg-white p-2 mr-3 shadow-sm">
                            <span id="statusIcon" class="d-flex align-items-center justify-content-center" style="width: 20px; height: 20px;"></span>
                          </div>
                          <div>
                            <small class="text-muted d-block">Status</small>
                            <span id="statusText" class="font-weight-bold"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Sinopsis Card -->
                <div class="card border mt-2">
                  <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0 font-weight-bold">
                      <i class="fas fa-align-left mr-2"></i>
                      Sinopsis Buku
                    </h6>
                  </div>
                  <div class="card-body">
                    <div id="modalDeskripsi" class="p-2 text-justify" 
                         style="min-height: 100px; max-height: 120px; overflow-y: auto; background-color: #f8f9fa; border-radius: 5px;">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Modal Footer -->
      <div class="modal-footer bg-light py-3">
        <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">
          <i class="fas fa-times mr-2"></i>Tutup
        </button>
      </div>
    </div>
  </div>
</div>

<script>
let allBooks = [];

document.addEventListener('DOMContentLoaded', function () {
    const token = localStorage.getItem('token');
    const bookList = document.getElementById('book-list');
    const searchInput = document.getElementById('searchInput');
    const categoryButtons = document.querySelectorAll('#category-filter .dropdown-item');
    const dropdown = document.getElementById('categoryDropdown');

    // Ambil data buku dari API
    fetch('/api/list-books', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(res => {
        allBooks = res.data || [];
        renderBooks(allBooks);
    });

    // Render daftar buku
    function renderBooks(books) {
        if (books.length === 0) {
            bookList.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Buku tidak tersedia
                    </div>
                </div>`;
            return;
        }

        bookList.innerHTML = '';
        books.forEach(book => {
            const image = book.image 
                ? book.image 
                : 'https://via.placeholder.com/300x400?text=No+Image';
            const stok = book.available_stock ?? 0;

            bookList.innerHTML += `
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow">
                    <img src="${image}"
                         class="card-img-top"
                         style="height:220px; object-fit:cover">

                    <div class="card-body d-flex flex-column">
                        <h6 class="font-weight-bold">${book.judul}</h6>
                        <p class="small mb-1 text-muted">Penerbit: ${book.penerbit}</p>
                        <p class="small mb-1">Rak: ${book.rak} - ${book.nomor_rak}</p>
                        <p class="small mb-2">Stok: ${stok}</p>

                        <span class="badge badge-info mb-3">${book.kategori}</span>

                        <div class="mt-auto d-flex">
                            <button class="btn btn-info btn-sm flex-fill mr-1"
                                onclick="viewBook('${book.id}')">
                                <i class="fas fa-eye"></i>
                            </button>

                            <button class="btn btn-success btn-sm flex-fill mr-1"
                                    onclick="pinjamBook('${book.id}')">
                                <i class="fas fa-cart-plus"></i>
                            </button>

                        </div>
                    </div>
                </div>
            </div>`;
        });
    }

    // Filter buku berdasarkan search dan kategori
    function filterBooks() {
        const search = searchInput ? searchInput.value.toLowerCase() : '';
        const categoryId = dropdown ? dropdown.dataset.selected : '';

        const filtered = allBooks.filter(book => {
            const matchSearch = search 
                ? book.judul.toLowerCase().includes(search) 
                : true;

            const matchCategory = categoryId 
                ? book.kategori.toLowerCase() === categoryId.toLowerCase() 
                : true;

            return matchSearch && matchCategory;
        });

        renderBooks(filtered);
    }

    // Event search input
    if (searchInput) {
        searchInput.addEventListener('input', filterBooks);
    }

    // Event klik kategori dropdown
    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            // Update active class
            categoryButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Update dropdown text dan selected
            dropdown.innerText = btn.innerText;
            dropdown.dataset.selected = btn.dataset.category;

            filterBooks();
        });
    });
});

/* ===== VIEW MODAL ===== */
function viewBook(id) {
    const book = allBooks.find(b => b.id == id);
    if (!book) return;

    const image = book.image 
        ? book.image 
        : 'https://via.placeholder.com/300x400?text=No+Image';

    document.getElementById('modalJudul').innerText = book.judul;
    document.getElementById('modalPenerbit').innerText = book.penerbit;
    document.getElementById('modalKategori').innerText = book.kategori ?? '-';
    document.getElementById('modalRak').innerText = `${book.rak} - ${book.nomor_rak}`;
    document.getElementById('modalPenulis').innerText = book.penulis ?? '-';
    document.getElementById('modalTahun').innerText = book.tahun ?? '-';
    document.getElementById('modalStok').innerText = book.available_stock ?? 0;
    document.getElementById('modalDeskripsi').innerText = book.sinopsis ?? 'Sipnosis buku tidak tersedia.';
    document.getElementById('modalCover').src = image;

    const statusIcon = document.getElementById('statusIcon');
    const statusText = document.getElementById('statusText');

    if (book.available_stock > 0) {
        statusIcon.innerHTML = `<i class="fas fa-check-circle text-success fa-lg"></i>`;
        statusText.innerText = 'Tersedia';
        statusText.className = 'font-weight-bold text-success';
    } else {
        statusIcon.innerHTML = `<i class="fas fa-times-circle text-danger fa-lg"></i>`;
        statusText.innerText = 'Habis';
        statusText.className = 'font-weight-bold text-danger';
    }

    $('#modalViewBook').modal('show');
}

function pinjamBook(bookId) {
    window.location.href = `/anggota/peminjaman/create?book_id=${bookId}`;
}
</script>
@endsection