@extends('layouts.anggota')

@section('title', 'Tambah Peminjaman')

@section('content')

<div class="container-fluid animate-up">
    <div class="header-container mb-4 d-flex align-items-center justify-content-between">
        <div class="header-left">
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Keranjang Peminjaman</h1>
            <p class="text-muted small mb-0">Daftar buku yang akan diajukan.</p>
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
            <div class="mb-3">
                <i class="fas fa-shopping-basket fa-3x text-light"></i>
            </div>
            <p class="text-muted font-italic">Keranjang pinjaman masih kosong...</p>
        </div>
    </div>
</div>

{{-- MODAL PILIH BUKU --}}
<div class="modal fade" id="modalPilihBuku" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <div>
                    <h5 class="font-weight-bold text-gray-800 mb-0">Pilih Buku</h5>
                    <small class="text-muted">Klik 'Pilih' pada buku yang tersedia.</small>
                </div>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            
            <div class="px-4 pt-3">
                <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden; border: 1px solid #e3e6f0;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="searchBukuModal" class="form-control border-0 py-4" placeholder="Cari judul buku atau kategori...">
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

<style>
    /* Global Style */
    .text-overline { text-transform: uppercase; letter-spacing: 1px; font-weight: 800; font-size: 0.65rem; }
    .animate-up { animation: fadeInUp 0.5s ease-out forwards; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .button-group-wrapper { display: flex; gap: 10px; justify-content: space-between; align-items: center; }

    .btn-main {
        height: 45px; padding: 0 20px; border-radius: 12px; border: none;
        display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.85rem;
        transition: all 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .btn-main:hover { transform: translateY(-2px); filter: brightness(1.05); text-decoration: none; }
    .btn-white { background: white; border: 1px solid #e3e6f0; }

    /* Loan Card Style */
    .loan-item-card { border-radius: 18px !important; border: none; transition: all 0.3s ease; }
    .loan-item-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
    .loan-item-img { width: 100%; height: 120px; object-fit: cover; border-radius: 12px; }
    .bg-soft-light { background-color: #f8f9fc; border-radius: 12px; padding: 15px; }

    /* Modal Book Cards */
    .modal-book-card { border-radius: 15px; border: 1px solid #edf0f5; transition: all 0.2s; height: 100%; }
    .modal-book-card:hover { border-color: #4e73df; transform: scale(1.02); }
    .img-modal-cover { height: 180px; object-fit: cover; border-radius: 15px 15px 0 0; }
    
    .form-control-read { background: transparent !important; border: none; font-weight: 700; padding: 0; height: auto; color: #4e73df; }

    .badge-primary-soft { background-color: #eaecf4; color: #4e73df; }

    @media (max-width: 576px) {
        .header-container { flex-direction: column; align-items: flex-start; }
        .button-group-wrapper { width: 100%; flex-wrap: wrap; margin-top: 15px; }
        .btn-main { flex: 1; min-width: 120px; }
    }
</style>

<script>
let selectedBooks = []; 
let allBooks = []; 
let maksHariPinjam = 0; 

async function loadAturanPeminjaman() {
    try {
        const token = localStorage.getItem('token'); 
        const res = await fetch('/api/aturanpeminjaman/aktif', {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        });
        const data = await res.json();
        maksHariPinjam = data.data.maks_hari_pinjam; 
    } catch (err) {
        console.error('Gagal ambil aturan', err);
    }
}

function formatDateToInput(date) {
    const d = new Date(date);
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
}

function renderBookCard(book) {
    if (selectedBooks.includes(book.id)) {
        Swal.fire('Info', 'Buku ini sudah masuk dalam daftar pinjam.', 'info');
        return;
    }
    selectedBooks.push(book.id);

    const emptyState = document.querySelector('.empty-state');
    if(emptyState) emptyState.remove();

    const image = book.image || 'https://via.placeholder.com/300x400?text=No+Image';
    const tglPinjam = formatDateToInput(new Date());
    const tglKembali = formatDateToInput(new Date(Date.now() + maksHariPinjam * 24 * 60 * 60 * 1000));

    const cardHtml = `
    <div class="col-xl-4 col-md-6 mb-4 card-container">
        <div class="card loan-item-card shadow-sm h-100">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-4">
                        <img src="${image}" class="loan-item-img shadow-sm">
                    </div>
                    <div class="col-8">
                        <span class="text-overline text-primary mb-1 d-block">Informasi Buku</span>
                        <h6 class="font-weight-bold text-gray-800 mb-1 text-truncate">${book.judul}</h6>
                        <small class="text-muted d-block mb-2">${book.penerbit}</small>
                        <button class="btn btn-sm btn-outline-danger border-0 px-0" onclick="removeBook('${book.id}', this)">
                            <i class="fas fa-trash-alt mr-1"></i> Batal Pilih
                        </button>
                    </div>
                </div>
                
                <div class="bg-soft-light text-xs">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="text-overline text-muted d-block mb-0">Rak / No</label>
                            <span class="font-weight-bold text-dark">${book.rak} - ${book.nomor_rak || ''}</span>
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
    
    document.getElementById('pinjaman-list').insertAdjacentHTML('beforeend', cardHtml);
    $('#modalPilihBuku').modal('hide');
}

function removeBook(id, btn) {
    selectedBooks = selectedBooks.filter(b => b !== id);
    btn.closest('.card-container').remove();
    
    if (selectedBooks.length === 0) {
        document.getElementById('pinjaman-list').innerHTML = `
        <div class="col-12 text-center py-5 empty-state">
            <div class="mb-3"><i class="fas fa-shopping-basket fa-3x text-light"></i></div>
            <p class="text-muted font-italic">Keranjang pinjaman masih kosong...</p>
        </div>`;
    }
}

async function loadBooksModal() {
    const token = localStorage.getItem('token');
    try {
        const res = await fetch('/api/list-books', {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        });
        const data = await res.json();
        allBooks = data.data || [];
        renderBooksModal(allBooks);
    } catch (err) {
        console.error('Gagal memuat buku', err);
    }
}

function renderBooksModal(books) {
    const container = document.getElementById('book-list');
    container.innerHTML = '';

    if(books.length === 0) {
        container.innerHTML = `<div class="col-12 text-center py-5 text-muted">Buku tidak ditemukan...</div>`;
        return;
    }

    books.forEach(book => {
        const stok = book.available_stock ?? 0;
        const isHabis = stok <= 0;
        const image = book.image || 'https://via.placeholder.com/300x400?text=No+Image';

        container.innerHTML += `
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card modal-book-card shadow-sm ${isHabis ? 'opacity-75' : ''}">
                    <img src="${image}" class="img-modal-cover">
                    <div class="card-body p-3 d-flex flex-column">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="badge badge-primary-soft text-primary px-2 py-1" style="font-size: 10px;">${book.kategori || 'Umum'}</span>
                            <span class="small font-weight-bold ${isHabis ? 'text-danger' : 'text-success'}">
                                <i class="fas fa-box mr-1"></i>${stok}
                            </span>
                        </div>
                        <h6 class="font-weight-bold text-gray-800 small mb-3 text-truncate-2" style="height: 2.4em; overflow: hidden;">${book.judul}</h6>
                        
                        <div class="mt-auto">
                            <button class="btn ${isHabis ? 'btn-secondary' : 'btn-primary'} btn-sm btn-block rounded-pill shadow-sm"
                                onclick="checkAndAddBook('${book.id}')">
                                ${isHabis ? '<i class="fas fa-times-circle mr-1"></i> Habis' : '<i class="fas fa-plus mr-1"></i> Pilih'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
    });
}

/**
 * FUNGSI PENCARIAN (SEARCH)
 */
document.getElementById('searchBukuModal').addEventListener('input', function() {
    const keyword = this.value.toLowerCase();
    const filteredBooks = allBooks.filter(book => {
        return book.judul.toLowerCase().includes(keyword) || 
               (book.kategori && book.kategori.toLowerCase().includes(keyword));
    });
    renderBooksModal(filteredBooks);
});

function checkAndAddBook(id) {
    const book = allBooks.find(b => b.id == id);
    if (!book) return;

    if (parseInt(book.available_stock || 0) <= 0) {
        Swal.fire({ icon: 'error', title: 'Stok Habis', text: `Maaf, buku "${book.judul}" tidak tersedia.` });
        return;
    }

    renderBookCard(book);
}

document.getElementById('btnAjukan').addEventListener('click', async function() {
    if (selectedBooks.length === 0) {
        Swal.fire('Info', 'Silakan pilih buku terlebih dahulu.', 'info');
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Peminjaman',
        text: "Ajukan peminjaman untuk buku yang dipilih?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        confirmButtonText: 'Ya, Ajukan!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
            try {
                const token = localStorage.getItem('token');
                const res = await fetch('/api/transaksi-pinjam', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ book_ids: selectedBooks })
                });
                
                const data = await res.json();
                if (res.ok) {
                    Swal.fire('Berhasil!', data.message, 'success').then(() => {
                        window.location.href = "{{ route('anggota.buku') }}";
                    });
                } else {
                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                }
            } catch (err) {
                Swal.fire('Error', 'Gagal terhubung ke server.', 'error');
            }
        }
    });
});

document.getElementById('btnReset').addEventListener('click', function() {
    if (selectedBooks.length === 0) return;
    Swal.fire({
        title: 'Reset Keranjang?',
        text: "Semua buku pilihan akan dihapus.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74a3b',
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            selectedBooks = [];
            document.getElementById('pinjaman-list').innerHTML = `
                <div class="col-12 text-center py-5 empty-state">
                    <div class="mb-3"><i class="fas fa-shopping-basket fa-3x text-light"></i></div>
                    <p class="text-muted font-italic">Keranjang pinjaman masih kosong...</p>
                </div>`;
            Swal.fire({ icon: 'success', title: 'Direset!', timer: 1000, showConfirmButton: false });
        }
    });
});

document.addEventListener('DOMContentLoaded', async function() {
    await loadAturanPeminjaman();
    await loadBooksModal();

    const urlParams = new URLSearchParams(window.location.search);
    const bookId = urlParams.get('book_id');
    if (bookId) {
        setTimeout(() => {
            const book = allBooks.find(b => b.id == bookId);
            if (book) checkAndAddBook(book.id);
        }, 500);
    }
});
</script>

@endsection