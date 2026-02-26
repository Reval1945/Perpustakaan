@extends('layouts.admin')

@section('title', 'Data Buku')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="text-gray-800">Daftar Buku</h4>
    <div>
        <button id="btnTambahBuku" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahBuku">
            <i class="fas fa-plus"></i> Tambah Buku
        </button>
        <button id="btnCetakBuku" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
    </div>
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
                            <i class="fas fa-book mr-1 text-primary"></i> Sinopsis
                        </label>
                        <input type="text" id="sinopsis" class="form-control form-control-lg" placeholder="Masukkan sinopsis buku">
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

<!-- Modal Kelola Stok -->
<div class="modal fade" id="modalTambahStok">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5>Kelola Stok Buku</h5>
                <button class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="stok_book_id">

                <!-- Input kode -->
                <div class="form-group">
                    <label>Kode Eksemplar</label>
                    <input type="text" id="kode_eksemplar" class="form-control" placeholder="Contoh: BK001-A">
                </div>

                <button class="btn btn-primary btn-block mb-3" id="btnTambahStok">
                    Tambah Kode
                </button>

                <hr>

                <!-- List stok -->
                <h6>Daftar Kode Buku</h6>
                <ul class="list-group" id="listStok"></ul>

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
const API = 'http://127.0.0.1:8000/api';
const token = localStorage.getItem('token');


/* ================= INIT ================= */
document.addEventListener('DOMContentLoaded', () => {
    fetchBooks();
    fetchCategories();
});


/* ================= FETCH BOOKS ================= */
async function fetchBooks(){
    try{
        const res = await fetch(`${API}/books`,{
            headers:{
                Accept:'application/json',
                Authorization:`Bearer ${token}`
            }
        });

        if(!res.ok) throw new Error("Fetch gagal");

        const data = await res.json();
        renderBooks(data.data);

    }catch(err){
        console.error(err);
        alert('Gagal mengambil data buku');
    }
}


/* ================= RENDER BOOK ================= */
function renderBooks(books){
    const container = document.getElementById('bookList');
    if(!container) return;

    container.innerHTML='';

    books.forEach(book=>{

        const image = book.image || 'https://via.placeholder.com/300x400?text=No+Image';
        const stok = book.available_stock ?? 0;

        container.innerHTML += `
        <div class="col-md-3 mb-4">
            <div class="card shadow h-100">

                <img src="${image}" class="card-img-top" style="height:220px;object-fit:cover;">

                <div class="card-body d-flex flex-column">

                    <h6 class="font-weight-bold">${book.judul}</h6>

                    <small class="text-muted">Penerbit: ${book.penerbit ?? '-'}</small>
                    <small>Rak: <b>${book.rak ?? '-'}-${book.nomor_rak ?? '-'}</b></small>
                    <small>Stok: <b>${stok} Buku</b></small>

                    <span class="badge badge-info mt-1 mb-2">${book.kategori ?? '-'}</span>

                    <div class="mt-auto text-right">

                        <button class="btn btn-warning btn-sm action"
                            data-type="edit"
                            data-book='${JSON.stringify(book)}'>
                            <i class="fas fa-edit"></i>
                        </button>

                        <button class="btn btn-info btn-sm action"
                            data-type="show"
                            data-book='${JSON.stringify(book)}'>
                            <i class="fas fa-eye"></i>
                        </button>

                        <button class="btn btn-danger btn-sm action"
                            data-type="delete"
                            data-id="${book.id}">
                            <i class="fas fa-trash"></i>
                        </button>

                        <button class="btn btn-primary btn-sm btn-stok"
                            data-id="${book.id}">
                            Stok
                        </button>

                    </div>
                </div>
            </div>
        </div>`;
    });
}


/* ================= FETCH CATEGORY ================= */
async function fetchCategories(){
    try{
        const res = await fetch(`${API}/categories`,{
            headers:{
                Authorization:`Bearer ${token}`,
                Accept:'application/json'
            }
        });

        const data = await res.json();
        const select = document.getElementById('category_id');
        if(!select) return;

        select.innerHTML='<option value="">-- Pilih Kategori --</option>';

        data.data.forEach(cat=>{
            select.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
        });

    }catch(err){
        console.error(err);
    }
}


/* ================= SAVE BOOK ================= */
document.addEventListener('click', async e=>{

    if(!e.target.closest('#btnSaveBook')) return;

    const id = document.getElementById('book_id').value;
    const form = new FormData();

    [
        'judul','sinopsis','kode_buku',
        'category_id','penulis','penerbit',
        'tahun','rak','nomor_rak'
    ].forEach(field=>{
        const el=document.getElementById(field);
        if(el) form.append(field,el.value);
    });

    const img=document.getElementById('image')?.files[0];
    if(img) form.append('image',img);

    let url=`${API}/books`;
    if(id){
        url+=`/${id}`;
        form.append('_method','PUT');
    }

    try{
        const res=await fetch(url,{
            method:'POST',
            headers:{
                Authorization:`Bearer ${token}`,
                Accept:'application/json'
            },
            body:form
        });

        const result=await res.json();

        if(result.errors){
            alert(Object.values(result.errors)[0][0]);
            return;
        }

        $('#modalTambahBuku').modal('hide');
        fetchBooks();
        resetBookForm();

    }catch(err){
        console.error(err);
        alert('Gagal menyimpan buku');
    }
});


/* ================= ACTION BUTTON ================= */
document.addEventListener('click', async e=>{

    const btn=e.target.closest('.action');
    if(!btn) return;

    const type=btn.dataset.type;

    /* ===== EDIT ===== */
    if(type==='edit'){
        const book = JSON.parse(btn.dataset.book);

        document.getElementById('book_id').value = book.id;

        [
            'judul','sinopsis','kode_buku',
            'category_id','penulis','penerbit',
            'tahun','rak','nomor_rak'
        ].forEach(k=>{
            const el=document.getElementById(k);
            if(el) el.value = book[k] ?? '';
        });

        $('#modalTambahBuku').modal('show');
    }


    /* ===== SHOW ===== */
    if(type==='show'){
        const book = JSON.parse(btn.dataset.book);

        document.getElementById('modalJudul').innerText = book.judul;
        document.getElementById('modalPenerbit').innerText = book.penerbit ?? '-';
        document.getElementById('modalKategori').innerText = book.kategori ?? '-';
        document.getElementById('modalRak').innerText = `${book.rak} - ${book.nomor_rak}`;
        document.getElementById('modalStok').innerText = book.available_stock ?? 0;
        document.getElementById('modalDeskripsi').innerText = book.sinopsis ?? '-';
        document.getElementById('modalCover').src = book.image ?? 'https://via.placeholder.com/300x400?text=No+Image';

        const statusIcon = document.getElementById('statusIcon');
        const statusText = document.getElementById('statusText');

        if (book.available_stock > 0) {
            statusIcon.innerHTML = `<i class="fas fa-check-circle text-success fa-lg"></i>`;
            statusText.innerText = 'Tersedia';
            statusText.className = 'font-weight-bold text-success';
        } else {
            statusIcon.innerHTML = `<i class="fas fa-times-circle text-danger fa-lg"></i>`;
            statusText.innerText = 'Tidak Tersedia';
            statusText.className = 'font-weight-bold text-danger';
        }

        $('#modalViewBook').modal('show');
    }


    /* ===== DELETE ===== */
    if(type==='delete'){

        const id = btn.dataset.id;

        if(!confirm('Yakin hapus buku ini?')) return;

        try{
            await fetch(`${API}/books/${id}`,{
                method:'DELETE',
                headers:{ Authorization:`Bearer ${token}` }
            });

            fetchBooks();

        }catch(err){
            console.error(err);
            alert('Gagal hapus buku');
        }
    }
});


/* ================= MODAL TAMBAH STOK ================= */

// buka modal stok
document.addEventListener('click', async function(e){
    const btn = e.target.closest('.btn-stok');
    if(!btn) return;

    const bookId = btn.dataset.id;
    document.getElementById('stok_book_id').value = bookId;

    $('#modalTambahStok').modal('show');

    loadStok(bookId);
});


// LOAD DATA STOK
async function loadStok(bookId){
    const token = localStorage.getItem('token');

    const res = await fetch(`/api/books/${bookId}/stok`,{
        headers:{
            'Authorization':'Bearer '+token,
            'Accept':'application/json'
        }
    });

    const data = await res.json();

    const list = document.getElementById('listStok');
    list.innerHTML='';

    if(!data.data || data.data.length===0){
        list.innerHTML='<li class="list-group-item text-muted">Belum ada stok</li>';
        return;
    }

    data.data.forEach(stok=>{

        let badge='secondary';
        if(stok.status==='tersedia') badge='success';
        else if(stok.status==='dipinjam') badge='warning';
        else if(stok.status==='rusak') badge='danger';

        list.innerHTML += `
        <li class="list-group-item d-flex justify-content-between align-items-center">

            <div>
                <strong>${stok.kode_eksemplar}</strong><br>
                <span class="badge badge-${badge}">${stok.status}</span>
            </div>

            <div>
                <button class="btn btn-danger btn-sm btn-hapus-stok"
                    data-id="${stok.id}">
                    Hapus
                </button>
            </div>

        </li>
        `;
    });
}

document.getElementById('btnTambahStok').addEventListener('click', async ()=>{

    const bookId = document.getElementById('stok_book_id').value;
    const kode = document.getElementById('kode_eksemplar').value.trim();
    const token = localStorage.getItem('token');

    if(!kode){
        alert('Kode harus diisi');
        return;
    }

    const res = await fetch(`/api/books/${bookId}/stok`,{
        method:'POST',
        headers:{
            'Authorization':'Bearer '+token,
            'Content-Type':'application/json',
            'Accept':'application/json'
        },
        body: JSON.stringify({
            kode_eksemplar:kode
        })
    });

    const data = await res.json();

    if(!res.ok){
        alert(data.message || 'Gagal tambah stok');
        return;
    }

    document.getElementById('kode_eksemplar').value='';
    loadStok(bookId);
});

document.addEventListener('click', async function(e){

    // ================= EDIT STATUS =================
    const editBtn = e.target.closest('.btn-edit-stok');
    if(editBtn){

        const id = editBtn.dataset.id;
        const oldStatus = editBtn.dataset.status;

        const status = prompt(
            "Ubah status:\ntersedia | dipinjam | rusak",
            oldStatus
        );

        if(!status) return;

        const token = localStorage.getItem('token');

        const res = await fetch(`/api/stok/${id}`,{
            method:'PUT',
            headers:{
                'Authorization':'Bearer '+token,
                'Content-Type':'application/json',
                'Accept':'application/json'
            },
            body: JSON.stringify({status})
        });

        if(res.ok){
            loadStok(currentBookId);
        }else{
            alert('Gagal update status');
        }
    }

});

document.addEventListener('click', async function(e){
    const btn = e.target.closest('.btn-hapus-stok');
    if(!btn) return;

    if(!confirm('Hapus kode ini?')) return;

    const id = btn.dataset.id;
    const token = localStorage.getItem('token');
    const bookId = document.getElementById('stok_book_id').value;

    await fetch(`/api/stok/${id}`,{
        method:'DELETE',
        headers:{
            'Authorization':'Bearer '+token,
            'Accept':'application/json'
        }
    });

    loadStok(bookId);
});


/* ================= RESET FORM ================= */

function resetBookForm(){
    document.getElementById('book_id').value='';
    document.querySelector('#modalTambahBuku form').reset();
}

// ================= EXPORT EXCEL =================
// tombol cetak buku akan memanggil endpoint yang sama seperti
// `BookController@exportExcel`, lalu men-trigger unduhan file.
document.getElementById('btnCetakBuku').addEventListener('click', async () => {
    try {
        const res = await fetch(`${API}/books/export/excel`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        if (!res.ok) throw new Error('Fetch export gagal');

        const blob = await res.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'data-buku.xlsx';
        a.click();
    } catch (err) {
        console.error(err);
        alert('Gagal export buku');
    }
});

document.addEventListener('click', e=>{
    if(e.target.closest('[data-target="#modalTambahBuku"]'))
        resetBookForm();
});
</script>
@endsection