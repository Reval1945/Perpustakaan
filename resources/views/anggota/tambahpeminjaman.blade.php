@extends('layouts.anggota')

@section('title', 'Tambah Peminjaman')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="text-gray-800">Peminjaman Buku</h4>

    <div>
        <button class="btn btn-primary mr-2" data-toggle="modal" data-target="#modalPilihBuku">
            <i class="fas fa-plus"></i> Tambah Peminjaman
        </button>

        <button class="btn btn-success mr-2" id="btnReset">
            <i class="fas fa-undo"></i> Reset
        </button>

        <button class="btn btn-warning" id="btnAjukan">
            <i class="fas fa-paper-plane"></i> Ajukan Peminjaman
        </button>
    </div>
</div>

{{-- LIST PINJAMAN (CARD) --}}
<div id="pinjaman-list"></div>

{{-- MODAL PILIH BUKU --}}
<div class="modal fade" id="modalPilihBuku" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Pilih Buku</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row" id="book-list">
                    <div class="col-12 text-center text-muted">
                        Memuat daftar buku...
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
let selectedBooks = []; // ID buku yang dipilih
let allBooks = []; // semua buku dari API
let maksHariPinjam = 0; // akan diambil dari DB


async function loadAturanPeminjaman() {
    try {
        const token = localStorage.getItem('token'); // token user
        const res = await fetch('/api/aturanpeminjaman/aktif', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('status: ' + res.status);

        const data = await res.json();
        maksHariPinjam = data.data.maks_hari_pinjam; // ambil maks_hari_pinjam
        console.log('Maksimal hari pinjam:', maksHariPinjam);
    } catch (err) {
        console.error('Gagal ambil aturan peminjaman', err);
    }
}

function formatDateToInput(date) {
    const d = new Date(date);
    const yyyy = d.getFullYear();
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const dd = String(d.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
}

function renderBookCard(book) {
    if (selectedBooks.includes(book.id)) {
        alert('Buku sudah dipilih');
        return;
    }
    selectedBooks.push(book.id);

    const image = book.image
            ? book.image
            : 'https://via.placeholder.com/300x400?text=No+Image';

    const tanggalPinjam = formatDateToInput(new Date());
    const tanggalKembali = formatDateToInput(new Date(Date.now() + maksHariPinjam * 24 * 60 * 60 * 1000));
    const stok = book.available_stock ?? 0;

    document.getElementById('pinjaman-list').innerHTML += `
    <div class="card shadow-sm mb-4" data-id="${book.id}">
        <div class="d-flex align-items-center p-3">
            <img src="${image}" width="80" class="rounded mr-3">
            <div>
                <h5>${book.judul}</h5>
                <small class="text-muted">${book.penerbit}</small>
            </div>
        </div>

        <div class="p-3 bg-light border-top">
            <div class="row text-sm">
                <div class="col-md-3">
                    <label>Rak</label>
                    <input type="text" class="form-control" value="${book.rak} - ${book.nomor_rak || ''}" readonly>
                </div>
                <div class="col-md-3">
                    <label>Stok</label>
                    <input type="text" class="form-control" value="${stok}" readonly>
                </div>
                <div class="col-md-3">
                    <label>Tanggal Pinjam</label>
                    <input type="date" class="form-control" value="${tanggalPinjam}" readonly>
                </div>
                <div class="col-md-3">
                    <label>Tanggal Kembali</label>
                    <input type="date" class="form-control" value="${tanggalKembali}" readonly>
                    <small class="text-muted">Maksimal ${maksHariPinjam} hari</small>
                </div>
            </div>
        </div>

        <div class="p-3 text-right border-top">
            <button class="btn btn-danger btn-sm" onclick="removeBook('${book.id}', this)">Hapus</button>
        </div>
    </div>`;
}

/**
 * Hapus buku dari card
 */
function removeBook(id, btn) {
    selectedBooks = selectedBooks.filter(b => b !== id);
    btn.closest('.card').remove();
}

/**
 * Load semua buku untuk modal
 */
async function loadBooksModal() {
    const token = localStorage.getItem('token');
    const res = await fetch('/api/list-books', {
        headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
    });
    const data = await res.json();
    allBooks = data.data || [];
    renderBooksModal(allBooks);
}

/**
 * Render buku di modal pilih buku
 */
function renderBooksModal(books) {
    const container = document.getElementById('book-list');
    container.innerHTML = '';

    if (books.length === 0) {
        container.innerHTML = `<div class="col-12 text-center text-muted">Buku tidak tersedia</div>`;
        return;
    }

    books.forEach(book => {
        const stok = book.available_stock ?? 0;
        const image = book.image
            ? book.image
            : 'https://via.placeholder.com/300x400?text=No+Image';

        container.innerHTML += `
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow">
                    <img src="${image}" class="card-img-top" style="height:220px; object-fit:cover">
                    <div class="card-body d-flex flex-column">
                        <h6 class="font-weight-bold">${book.judul}</h6>
                        <p class="small mb-1 text-muted">Penerbit: ${book.penerbit}</p>
                        <span class="badge badge-info mb-3">${book.kategori}</span>
                        <div class="mt-auto d-flex">
                            <button class="btn btn-success btn-sm flex-fill"
                                onclick="renderBookCard({
                                    id: '${book.id}',
                                    judul: '${book.judul}',
                                    penerbit: '${book.penerbit}',
                                    rak: '${book.rak}',
                                    nomor_rak: '${book.nomor_rak || ''}',
                                    stok: '${stok}',
                                    image: '${image}'
                                })">
                                Pilih
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
    });
}

/**
 * Ajukan peminjaman ke server
 */
document.getElementById('btnAjukan').addEventListener('click', async function() {
    if (selectedBooks.length === 0) {
        alert('Pilih buku terlebih dahulu!');
        return;
    }

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
        if (data.message) alert(data.message);

        selectedBooks = [];
        document.getElementById('pinjaman-list').innerHTML = '';
    } catch (err) {
        console.error(err);
    }
});

/**
 * Reset semua card
 */
document.getElementById('btnReset').addEventListener('click', function() {
    selectedBooks = [];
    document.getElementById('pinjaman-list').innerHTML = '';
});

/**
 * Load halaman
 */
document.addEventListener('DOMContentLoaded', async function() {
    await loadAturanPeminjaman();
    await loadBooksModal();

    // Jika klik dari daftar buku (?book_id=xxx)
    const urlParams = new URLSearchParams(window.location.search);
    const bookId = urlParams.get('book_id');
    if (bookId) {
        const book = allBooks.find(b => b.id === bookId);
        if (book) renderBookCard(book);
    }
});
</script>

@endsection
