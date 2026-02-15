@extends('layouts.admin')

@section('title', 'Data Buku')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="text-gray-800">Daftar Buku</h4>
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahBuku">
        <i class="fas fa-plus"></i> Tambah Buku
    </button>
</div>

<!-- DAFTAR BUKU -->
<div class="row" id="bookList">
</div>

<!-- Modal Tambah Buku -->
<div class="modal fade" id="modalTambahBuku" tabindex="-1" role="dialog" aria-labelledby="modalTambahBukuLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="modalTambahBukuLabel">
                    <i class="fas fa-book-medical mr-2"></i> Tambah Buku
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <form>

                    <input type="hidden" id="book_id">

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-book mr-1 text-primary"></i> Judul Buku
                        </label>
                        <input type="text" id="judul" class="form-control form-control-lg" placeholder="Masukkan judul buku">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-book mr-1 text-primary"></i> Kode Buku
                        </label>
                        <input type="text" id="kode_buku" class="form-control form-control-lg" placeholder="Masukkan kode buku">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-book mr-1 text-primary"></i> Penulis
                        </label>
                        <input type="text" id="penulis" class="form-control form-control-lg" placeholder="Masukkan judul buku">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-building mr-1 text-primary"></i> Penerbit
                        </label>
                        <input type="text" id="penerbit" class="form-control" placeholder="Masukkan penerbit">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-building mr-1 text-primary"></i> Tahun Terbit
                        </label>
                        <input type="text" id="tahun" class="form-control" placeholder="Masukkan penerbit">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-tags mr-1 text-primary"></i> Kategori
                        </label>
                        <select id="category_id" class="form-control">
                            <option value="">-- Pilih Kategori --</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">
                                <i class="fas fa-layer-group mr-1 text-primary"></i> Stok Buku
                            </label>
                            <input type="number" id="stok" class="form-control" placeholder="Jumlah stok">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">
                                <i class="fas fa-archive mr-1 text-primary"></i> Rak Buku
                            </label>
                            <input type="text" id="rak" class="form-control" placeholder="Contoh: A-03">
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">
                            <i class="fas fa-archive mr-1 text-primary"></i> Nomor Rak
                        </label>
                        <input type="text" id="nomor_rak" class="form-control" placeholder="Contoh: 03">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-image mr-1 text-primary"></i> Cover Buku
                        </label>
                        <div class="custom-file">
                            <input type="file" id="image" class="custom-file-input">
                            <label class="custom-file-label" for="image">Pilih file</label>
                        </div>
                    </div>

                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button id="btnSaveBook" class="btn btn-primary px-4">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>

        </div>
    </div>
</div>

<!-- MODAL VIEW -->
<div class="modal fade" id="modalViewBook" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header bg-light">
        <div class="w-100">
          <h5 class="modal-title font-weight-bold text-dark mb-1" id="modalJudul"></h5>
          <small class="text-muted">
            <i class="fas fa-book-open mr-1"></i>
            Detail Informasi Buku
          </small>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
        <div class="row">
          <!-- Cover Card -->
          <div class="col-md-4 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-0">
                <div class="p-3 border-bottom">
                  <img id="modalCover" class="img-fluid rounded" 
                       style="height: 250px; object-fit: cover; width: 100%;">
                </div>
                <div class="p-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-muted">Stok Tersedia</span>
                    <span id="modalStok" class="badge badge-primary badge-pill px-3 py-2 font-weight-normal">0</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Details Card -->
          <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body">
                <!-- Info Grid -->
                <div class="row mb-4">
                  <div class="col-md-6 mb-3">
                    <div class="card border h-100">
                      <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                          <div class="rounded-circle bg-light p-2 mr-3">
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
                    <div class="card border h-100">
                      <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                          <div class="rounded-circle bg-light p-2 mr-3">
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
                  
                  <div class="col-md-6 mb-3">
                    <div class="card border h-100">
                      <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                          <div class="rounded-circle bg-light p-2 mr-3">
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
                    <div class="card border h-100">
                        <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light p-2 mr-3">
                            <span id="statusIcon"></span>
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
                
                <!-- Description Card -->
                <div class="card border">
                  <div class="card-header bg-light border-bottom py-2">
                    <h6 class="mb-0 font-weight-bold">
                      <i class="fas fa-align-left mr-2 text-primary"></i>
                      Sipnosis Buku
                    </h6>
                  </div>
                  <div class="card-body">
                    <div id="modalDeskripsi" class="p-2" 
                         style="min-height: 100px; max-height: 150px; overflow-y: auto;">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Modal Footer -->
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-2"></i>Tutup
        </button>
      </div>
    </div>
  </div>
</div>


<!-- DATA BUKU -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetchBooks();
});

function fetchBooks() {
    const token = localStorage.getItem('token');

    fetch('http://127.0.0.1:8000/api/books', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Token invalid / unauthorized');
        return res.json();
    })
    .then(res => {
        renderBooks(res.data);
    })
    .catch(err => {
        console.error(err);
        alert('Gagal mengambil data buku');
    });
}

function renderBooks(books) {
    const container = document.getElementById('bookList');
    container.innerHTML = '';

    books.forEach(book => {
        const image = book.image 
            ? book.image 
            : 'https://via.placeholder.com/300x400?text=No+Image';

        container.innerHTML += `
            <div class="col-md-3 mb-4">
                <div class="card shadow h-100">

                    <img src="${image}" class="card-img-top" style="height:220px; object-fit:cover;">

                    <div class="card-body d-flex flex-column">
                        <h6 class="font-weight-bold mb-1">${book.judul}</h6>

                        <p class="mb-1 small text-muted">
                            Penerbit: ${book.penerbit}
                        </p>

                        <p class="mb-1 small">
                            Rak: <strong>${book.rak}-${book.nomor_rak}</strong>
                        </p>

                        <p class="mb-2 small">
                            Stok: <strong>${book.stok} Buku</strong>
                        </p>

                        <span class="badge badge-info mb-2 pt-2 pb-2">${book.kategori}</span>

                        <div class="mt-auto d-flex justify-content-end">
                            <button class="btn btn-sm btn-warning btn-edit mr-2" data-id="${book.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-warning btn-show mr-2" data-id="${book.id}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${book.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        `;
    });
}
</script>

<!-- FETCH KATEGORI -->

<script>
function fetchCategories() {
    const token = localStorage.getItem('token');

    fetch('http://127.0.0.1:8000/api/categories', {
        headers: {
            'Authorization': `Bearer ${token}`
        }
    })
    .then(res => res.json())
    .then(res => {
        const select = document.getElementById('category_id');
        select.innerHTML = '<option value="">-- Pilih Kategori --</option>';

        res.data.forEach(cat => {
            select.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
        });
    });
}

document.addEventListener('DOMContentLoaded', fetchCategories);
</script>

<!-- FETCH CREATE & UPDATE-->

<script>
document.getElementById('btnSaveBook').addEventListener('click', function () {
    const token = localStorage.getItem('token');
    const id = document.getElementById('book_id').value;

    const formData = new FormData();
    formData.append('judul', document.getElementById('judul').value);
    formData.append('kode_buku', document.getElementById('kode_buku').value);
    formData.append('category_id', document.getElementById('category_id').value);
    formData.append('penulis', document.getElementById('penulis').value);
    formData.append('penerbit', document.getElementById('penerbit').value);
    formData.append('tahun', document.getElementById('tahun').value);
    formData.append('stok', document.getElementById('stok').value);
    formData.append('rak', document.getElementById('rak').value);
    formData.append('nomor_rak', document.getElementById('nomor_rak').value);

    const image = document.getElementById('image').files[0];
    if (image) formData.append('image', image);

    let url = 'http://127.0.0.1:8000/api/books';
    let method = 'POST';

    if (id) {
        url = `http://127.0.0.1:8000/api/books/${id}`;
        formData.append('_method', 'PUT');
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`
        },
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        if (res.errors) {
            alert(Object.values(res.errors)[0][0]);
            return;
        }

        $('#modalTambahBuku').modal('hide');
        fetchBooks();
        resetBookForm();
    })
    .catch(err => {
        console.error(err);
        alert('Gagal menyimpan buku');
    });
});

function resetBookForm() {
    document.getElementById('book_id').value = '';
    document.querySelector('#modalTambahBuku form').reset();
}
</script>

<!-- FETCH TOMBOL EDIT & SHOW -->
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-edit');
    if (!btn) return;

    const id = btn.dataset.id;
    const token = localStorage.getItem('token');

    fetch(`http://127.0.0.1:8000/api/books/${id}`, {
        headers: {
            'Authorization': `Bearer ${token}`
        }
    })
    .then(res => res.json())
    .then(res => {
        const book = res.data;

        document.getElementById('book_id').value = book.id;
        document.getElementById('judul').value = book.judul;
        document.getElementById('kode_buku').value = book.kode_buku;
        document.getElementById('penulis').value = book.penulis;
        document.getElementById('penerbit').value = book.penerbit;
        document.getElementById('tahun').value = book.tahun;
        document.getElementById('stok').value = book.stok;
        document.getElementById('rak').value = book.rak;
        document.getElementById('nomor_rak').value = book.nomor_rak;
        document.getElementById('category_id').value = book.category_id;

        $('#modalTambahBuku').modal('show');

    });
});

document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-show');
    if (!btn) return;

    const id = btn.dataset.id;
    const token = localStorage.getItem('token');

    fetch(`http://127.0.0.1:8000/api/books/${id}`, {
        headers: {
            'Authorization': `Bearer ${token}`
        }
    })
    .then(res => res.json())
    .then(res => {
        const book = res.data;

        const image = book.image
            ? book.image
            : 'https://via.placeholder.com/300x400?text=No+Image';

        // Header
        document.getElementById('modalJudul').textContent = book.judul;

        // Cover & stok
        document.getElementById('modalCover').src = image;
        document.getElementById('modalStok').textContent = book.stok + " Buku";

        // Detail info
        document.getElementById('modalPenerbit').textContent = book.penerbit;
        document.getElementById('modalKategori').textContent = book.kategori;
        document.getElementById('modalRak').textContent = book.rak + "-" + book.nomor_rak;

        // Status stok
        const statusIcon = document.getElementById('statusIcon');
        const statusText = document.getElementById('statusText');

        if (book.stok > 0) {
            statusIcon.innerHTML = '<i class="fas fa-check text-success"></i>';
            statusText.textContent = 'Tersedia';
            statusText.className = 'font-weight-bold text-success';
        } else {
            statusIcon.innerHTML = '<i class="fas fa-times text-danger"></i>';
            statusText.textContent = 'Habis';
            statusText.className = 'font-weight-bold text-danger';
        }

        // Deskripsi / sinopsis (jika tidak ada field, pakai default)
        document.getElementById('modalDeskripsi').textContent =
            book.deskripsi || 'Tidak ada sinopsis buku.';

        // Tampilkan modal
        $('#modalViewBook').modal('show');
    });
});


</script>

<!-- FETCH TOMBOL DELETE -->
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-delete');
    if (!btn) return;

    const id = btn.dataset.id;
    const token = localStorage.getItem('token');

    if (!confirm('Yakin hapus buku ini?')) return;

    fetch(`http://127.0.0.1:8000/api/books/${id}`, {
        method: 'DELETE',
        headers: {
            'Authorization': `Bearer ${token}`
        }
    })
    .then(res => {
        if (!res.ok) throw new Error();
        fetchBooks();
    })
    .catch(() => alert('Gagal hapus buku'));
});
</script>

<!-- RESET MODAL -->
<script>
document.querySelector('[data-target="#modalTambahBuku"]').addEventListener('click', () => {
    resetBookForm();
});
</script>

<!-- FILTER KATEGORI -->



@endsection
