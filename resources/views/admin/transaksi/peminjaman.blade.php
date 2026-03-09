@extends('layouts.admin')

@section('title', 'Peminjaman Buku')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" style="color: var(--dark); font-weight: 700;">
        Peminjaman Buku
    </h1>
    <div class="mt-3 mt-md-0">
        <button  class="btn btn-main btn-primary shadow-sm px-4" onclick="window.location.href='/admin/transaksi/tambahpeminjaman'">
            <i class="fas fa-plus fa-sm mr-2"></i> Tambah Peminjaman
        </button>
    </div>
</div>

<div class="card shadow-sm mb-4" style="border: none; border-radius: 16px;">
    <div class="card-body py-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="small font-weight-bold text-muted">Cari Data</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white" style="border-color: var(--border); border-radius: 30px 0 0 30px;">
                            <i class="fas fa-search" style="color: var(--gray);"></i>
                        </span>
                    </div>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari Nama atau kode..." style="border-color: var(--border); border-radius: 0 30px 30px 0; border-left: none;">
                </div>
            </div>
            <div class="col-md-3">
                <label class="small font-weight-bold text-muted">Dari Tanggal</label>
                <input type="date" id="startDate" class="form-control" style="border-radius: 12px; border-color: var(--border);">
            </div>
            <div class="col-md-3">
                <label class="small font-weight-bold text-muted">Sampai Tanggal</label>
                <input type="date" id="endDate" class="form-control" style="border-radius: 12px; border-color: var(--border);">
            </div>
            <div class="col-md-2">
                <button onclick="resetFilter()" class="btn btn-light w-100" style="border-radius: 12px; height: 45px; border: 1px solid var(--border);">
                    <i class="fas fa-undo mr-1"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm" style="border: none; border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: var(--gray-light);">
                    <tr>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none; width: 60px;">No</th>
                        <th class="py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Kode</th>
                        <th class="py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Nama Peminjam</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Role</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Tgl Pinjam</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Jatuh Tempo</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Status</th>
                        <th class="text-center py-3" style="color: var(--gray); font-size: 0.75rem; text-transform: uppercase; border: none;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="transactionTable">
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAccDetail" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title" style="font-weight: 700; color: var(--dark);">Konfirmasi Pengembalian</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body px-4">
                <input type="hidden" id="detailId">
                <input type="hidden" id="jumlah_hari_telat" value="0">

                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-muted">Status Pengembalian</label>
                    <select id="status" class="form-control" style="border-radius: 12px; border-color: var(--border);">
                        <option value="dikembalikan">Dikembalikan (Normal)</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="rusak">Rusak</option>
                        <option value="hilang">Hilang</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-muted">Jenis Pelanggaran (Denda)</label>
                    <select id="jenis_denda" class="form-control" style="border-radius: 12px; border-color: var(--border);">
                        <option value="">- Tidak Ada -</option>
                        <option value="telat">Telat (Uang)</option>
                        <option value="rusak">Buku Rusak</option>
                        <option value="hilang">Buku Hilang</option>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label class="small font-weight-bold text-muted">Nominal Denda</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light" style="border-radius: 12px 0 0 12px; border-color: var(--border);">Rp</span>
                        </div>
                        <input type="number" id="denda" class="form-control" style="border-radius: 0 12px 12px 0; border-color: var(--border);">
                    </div>
                    <div id="denda_keterangan" class="mt-2 p-2 bg-light" style="display:none; border-radius: 8px;">
                        <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> <span id="denda_formula"></span></small>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="small font-weight-bold">Catatan / Keterangan</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="2" placeholder=""></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button class="btn btn-light px-4" data-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Batal</button>
                <button class="btn btn-primary px-4" id="btnSubmitAccDetail" style="border-radius: 10px; font-weight: 600;">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<style>
.badge-custom { 
    font-weight: 600; 
    padding: 0.45rem 0.85rem; 
    border-radius: 30px; 
    font-size: 0.75rem; 
    display: inline-block;
    min-width: 90px;
    text-align: center;
}

.btn-main { height: 45px; border-radius: 12px; border: none; font-weight: 600; font-size: 0.85rem; transition: all 0.3s; }
.form-control:focus { border-color: var(--primary); box-shadow: none; }
.table td { vertical-align: middle !important; border-color: var(--border); padding: 1rem 0.75rem; color: var(--dark); }

/* Dropdown Expand Style */
.expand-row { background-color: var(--gray-light); }
.inner-table { background: white; border-radius: 12px; overflow: hidden; border: 1px solid var(--border); }
</style>

<script>

let allTransactions = []; // Menyimpan semua transaksi untuk keperluan filter

function formatTanggal(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
}

function hitungDendaOtomatis(tanggalJatuhTempo, dendaPerHariAturan) {
    if (!tanggalJatuhTempo) return 0;
    
    const hariIni = new Date();
    hariIni.setHours(0,0,0,0);
    
    const jatuhTempo = new Date(tanggalJatuhTempo);
    jatuhTempo.setHours(0,0,0,0);
    
    if (hariIni <= jatuhTempo) {
        return 0; // Belum jatuh tempo, tidak ada denda
    }
    
    const diffTime = Math.abs(hariIni - jatuhTempo);
    const selisihHari = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    return selisihHari * dendaPerHariAturan;
}

function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
}

function getVerificationType(trx) {
    const statuses = trx.details.map(d => d.status);
    const total = statuses.length;

    const menunggu = statuses.filter(s => s === 'menunggu_verifikasi' || s === 'menunggu_verifikasi_kembali').length;

    if (menunggu === 0) return 'none';
    if (menunggu === total) return 'all';
    return 'partial';
}

document.addEventListener('DOMContentLoaded', () => {
    fetchDendaRule();
    fetchTransactions();

    // Event Listeners untuk Filter
    document.getElementById('searchInput').addEventListener('input', applyFilters);
    document.getElementById('startDate').addEventListener('change', applyFilters);
    document.getElementById('endDate').addEventListener('change', applyFilters);
});

// 3. Fungsi Fetch Data dari API
function fetchTransactions() {
    const token = localStorage.getItem('token');
    const tbody = document.getElementById('transactionTable');

    fetch('http://127.0.0.1:8000/api/transactions', {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(res => {
        // Simpan data ke variabel global
        allTransactions = res.data || [];
        // Kirim data ke fungsi render
        renderTable(allTransactions);
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Koneksi Gagal',
            text: 'Gagal memuat data transaksi dari server.'
        });
        tbody.innerHTML = `<tr><td colspan="9" class="text-center text-danger">Gagal load data</td></tr>`;
    });
}

// Fetch Status
// Fetch Status Utama (Header)
function getTransactionStatus(trx) {
    const detailStatus = trx.details.map(d => d.status);
    const total = detailStatus.length;

    const adaMintaPerpanjang = trx.details.some(d => d.status === 'mengajukan_perpanjangan');
    const menungguKembali = detailStatus.filter(s => s === 'menunggu_verifikasi_kembali').length;
    
    // PRIORITAS 1: Jika ada yang minta perpanjang, ini harus langsung kelihatan
    if (adaMintaPerpanjang) return 'sebagian_minta_perpanjang';

    // PRIORITAS 2: Verifikasi kembali (buku sudah di meja admin)
    if (menungguKembali > 0) return 'menunggu_verifikasi_kembali'; 

    // PRIORITAS 3: Verifikasi pinjam baru
    const menungguPinjam = detailStatus.filter(s => s === 'menunggu_verifikasi').length;
    if (menungguPinjam > 0) return 'menunggu_verifikasi';
    
    const dikembalikan = detailStatus.filter(s => s === 'dikembalikan' || s === 'rusak' || s === 'hilang').length;
    const dipinjam = detailStatus.filter(s => s === 'dipinjam' || s === 'diperpanjang').length;

    if (dikembalikan === total) return 'dikembalikan';
    if (dipinjam === total) return 'dipinjam';
    if (dikembalikan > 0 && dipinjam > 0) return 'sebagian_dipinjam';
    
    return trx.status;
}

// Badge untuk Tabel Utama
function statusBadge(status) {
    if (status === 'sebagian_minta_perpanjang') {
        return `<span class="badge-custom" style="background: #fff3cd; color: #856404; border: 1px solid #ffeeba;">Sebagian Minta Perpanjang</span>`;
    }
    if (status === 'dipinjam') return `<span class="badge-custom" style="background: var(--primary-soft); color: var(--primary);">Dipinjam</span>`;
    if (status === 'menunggu_verifikasi') return `<span class="badge-custom" style="background: var(--warning-soft); color: var(--warning);">Verifikasi</span>`;
    if (status === 'menunggu_verifikasi_kembali') return `<span class="badge-custom" style="background: var(--warning-soft); color: var(--warning);">Verifikasi Kembali</span>`;
    if (status === 'dikembalikan') return `<span class="badge-custom" style="background: var(--success-soft); color: var(--success);">Selesai</span>`;
    if (status === 'sebagian_dipinjam') return `<span class="badge-custom" style="background: var(--warning-soft); color: var(--warning);">Sebagian</span>`;
    
    return `<span class="badge-custom" style="background: var(--gray-light); color: var(--dark);">${status}</span>`;
}

// Badge khusus untuk Tabel Detail (Row Expanded)
function statusBadgeDetail(status) {
    switch (status) {
        case 'mengajukan_perpanjangan':
            return `<span class="badge-custom" style="background: #fff3cd; color: #856404; border: 1px solid #ffeeba;">Minta Perpanjang</span>`;
        case 'diperpanjang':
            return `<span class="badge-custom" style="background: #e1f5fe; color: var(--info);">Diperpanjang</span>`;
        case 'dipinjam':
            return `<span class="badge-custom" style="background: var(--primary-soft); color: var(--primary);">Dipinjam</span>`;
        case 'dikembalikan':
            return `<span class="badge-custom" style="background: var(--success-soft); color: var(--success);">Kembali</span>`;
        default:
            return `<span class="badge-custom" style="background: var(--gray-light); color: var(--dark);">${status}</span>`;
    }
}

// 4. Fungsi Render Tabel (Logika Utama Tampilan)
function renderTable(data) {
    const tbody = document.getElementById('transactionTable');
    tbody.innerHTML = '';

    // Filter: Sembunyikan yang sudah kembali & tidak terlambat
    const filteredData = data.filter(trx =>
        trx.details.some(d => 
            ['dipinjam', 'menunggu_verifikasi', 'menunggu_verifikasi_kembali', 'mengajukan_perpanjangan', 'diperpanjang'].includes(d.status)
        )
    );

    if (filteredData.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data peminjaman aktif</td></tr>`;
        return;
    }

    filteredData.forEach((trx, index) => {
        // --- Render Detail Rows (Expanded) ---
        // Di dalam trx.details.map
        const detailRows = trx.details.map((d, i) => {
            let actionDetail = ''; 

            // 1. Tombol Perpanjang (Hanya jika minta perpanjang)
            if (d.status === 'mengajukan_perpanjangan') {
                actionDetail += `
                    <a href="/admin/transaksi/detail/edit/${d.id}" 
                    class="btn btn-sm btn-light mr-1" 
                    title="Perpanjang Buku Ini" 
                    style="border-radius: 8px; border: 1px solid #ddd;">
                        <i class="fas fa-calendar-alt text-warning"></i>
                    </a>
                `;
            }

            // 2. Tombol Konfirmasi Pengembalian (Hanya jika mau kembali)
            // Kita HAPUS pengecekan 'menunggu_verifikasi' di sini agar tombol hijau tidak muncul
            if (d.status === 'menunggu_verifikasi_kembali') {
                actionDetail += `
                    <button class="btn btn-info btn-sm btn-acc-detail" 
                            data-id="${d.id}" 
                            data-judul="${d.judul_buku}"
                            data-jatuh-tempo="${d.tanggal_jatuh_tempo}"
                            title="Verifikasi Kembali / Rusak / Hilang"
                            style="border-radius: 8px;">
                        <i class="fas fa-undo-alt"></i>
                    </button>
                `;
            }

            if (actionDetail === '') {
                actionDetail = '<span class="text-muted small">-</span>';
            }

            return `
                <tr>
                    <td class="text-center small">${i + 1}</td>
                    <td class="text-center small">${d.book_stock?.kode_eksemplar ?? '-'}</td>
                    <td class="font-weight-bold small">${d.judul_buku}</td>
                    <td class="text-center small">${formatTanggal(d.tanggal_jatuh_tempo)}</td>
                    <td class="text-center">${statusBadgeDetail(d.status)}</td>
                    <td class="text-center small">${actionDetail}</td>
                </tr>
            `;
        }).join('');

        // --- Render Baris Utama ---
        const row = `
            <tr class="main-row" data-id="${trx.id}" style="cursor:pointer;">
                <td class="text-center small">${index + 1}</td> 
                <td class="align-middle">
                    <span class="badge badge-light text-primary p-2" style="font-weight: 700; border-radius:8px;">${trx.kode_transaksi}</span>
                </td>
                <td class="font-weight-bold small">${trx.user.name}</td>
                <td class="text-center small">${trx.user.role}</td>
                <td class="text-center small">${formatTanggal(trx.tanggal_pinjam)}</td>
                <td class="text-center small">${formatTanggal(trx.tanggal_jatuh_tempo)}</td>
                <td class="text-center">${statusBadge(getTransactionStatus(trx))}</td>
                <td class="text-center">${renderActionButton(trx)}</td>
            </tr>
            <tr id="expand-${trx.id}" class="expand-row" style="display:none; background-color: #fcfcfc;">
                <td colspan="8">
                    <div class="inner-table p-3 shadow-sm" style="background: white; border: 1px solid #eee; border-radius: 12px; margin: 10px;">
                        <h6 class="font-weight-bold text-muted mb-3"><i class="fas fa-list mr-2"></i>ITEM BUKU</h6>
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light text-center small">
                                <tr>
                                    <th width="50">No</th>
                                    <th width="80">Kode Buku</th>
                                    <th width="250">Judul Buku</th>
                                    <th width="150">Jatuh Tempo</th>
                                    <th width="150">Status</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>${detailRows}</tbody>
                        </table>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Render tombol action utama
function renderActionButton(trx) {
    const statusUtama = getTransactionStatus(trx);
    
    // 1. Tombol standar yang selalu ada (Lihat Detail & Perpanjang Semua)
    let buttons = `
        <button class="btn btn-sm btn-light btn-detail mr-1" data-id="${trx.id}" title="Lihat Item" style="border-radius: 8px; border: 1px solid var(--border);">
            <i class="fas fa-eye text-primary"></i>
        </button>
        <a href="/admin/transaksi/edit/${trx.id}" class="btn btn-sm btn-light mr-1" title="Perpanjang Semua" style="border-radius: 8px; border: 1px solid var(--border);">
            <i class="fas fa-calendar-alt text-warning"></i>
        </a>
    `;

    // 2. Logika Tombol Konfirmasi (Muncul hanya jika butuh verifikasi)
    if (statusUtama === 'menunggu_verifikasi' || statusUtama === 'menunggu_verifikasi_kembali') {
        const isKembali = statusUtama === 'menunggu_verifikasi_kembali';
        
        buttons += `
            <button class="btn btn-sm ${isKembali ? 'btn-info' : 'btn-success'} btn-acc" 
                    data-id="${trx.id}" 
                    data-status-type="${statusUtama}"
                    data-jatuh-tempo="${trx.tanggal_jatuh_tempo}"
                    style="border-radius: 8px;"
                    title="${isKembali ? 'Konfirmasi Pengembalian' : 'Konfirmasi Peminjaman'}">
                <i class="fas ${isKembali ? 'fa-undo-alt' : 'fa-check'}"></i>
            </button>
        `;
    }

    return `<div class="d-flex justify-content-center">${buttons}</div>`;
}

// 5. Fungsi Logic Filter (Search & Tanggal)
function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    const filtered = allTransactions.filter(trx => {
        // Cek pencarian (Nama, Kode Trx, atau Judul Buku di dalam details)
        const matchesSearch = 
            trx.user.name.toLowerCase().includes(searchTerm) ||
            trx.kode_transaksi.toLowerCase().includes(searchTerm) ||
            trx.details.some(d => d.judul_buku.toLowerCase().includes(searchTerm));

        // Cek range tanggal pinjam
        let matchesDate = true;
        const trxDate = trx.tanggal_pinjam.split('T')[0]; // Ambil bagian YYYY-MM-DD

        if (startDate && endDate) {
            matchesDate = trxDate >= startDate && trxDate <= endDate;
        } else if (startDate) {
            matchesDate = trxDate >= startDate;
        } else if (endDate) {
            matchesDate = trxDate <= endDate;
        }

        return matchesSearch && matchesDate;
    });

    renderTable(filtered);
}

document.addEventListener('click', function(e) {
    // Toggle detail row
    if (e.target.closest('.btn-detail')) {
        const id = e.target.closest('.btn-detail').dataset.id;
        const detailRow = document.getElementById(`expand-${id}`);
        const isOpen = detailRow.style.display === 'table-row';
        document.querySelectorAll('.expand-row').forEach(r => r.style.display = 'none');
        detailRow.style.display = isOpen ? 'none' : 'table-row';
    }
});

document.addEventListener('DOMContentLoaded', fetchTransactions);


// =========================
// ACC STATUS / MODAL
// =========================
let accEndpoint = '';
let dendaPerHariAturan = 0;

document.addEventListener('DOMContentLoaded', () => {
    fetchTransactions();
    fetchDendaRule();
});

function fetchDendaRule() {
    const token = localStorage.getItem('token');
    // Tambahkan return fetch agar bisa di-await jika perlu
    return fetch('http://127.0.0.1:8000/api/aturan-peminjaman/aktif', {
        headers: { 
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(res => {
        if (res.data && res.data.denda_per_hari) {
            // Gunakan Number() untuk memastikan tipe data angka
            dendaPerHariAturan = Number(res.data.denda_per_hari);
            console.log('Denda dimuat:', dendaPerHariAturan);
        }
    })
    .catch(err => console.error('Gagal load aturan:', err));
}

document.addEventListener('click', function(e) {
    const token = localStorage.getItem('token');

    // 1. Tombol Konfirmasi Utama (Bisa Pinjam atau Kembali)
    if (e.target.closest('.btn-acc')) {
        const btn = e.target.closest('.btn-acc');
        const id = btn.dataset.id;
        // Kita gunakan statusType sebagai penentu (lebih akurat daripada dataset.kembali)
        const statusType = btn.dataset.statusType; 

        // LOGIKA PINJAM: Jika statusnya menunggu_verifikasi (peminjaman awal)
        if (statusType === 'menunggu_verifikasi') {
            Swal.fire({
                title: 'Konfirmasi Pinjam?',
                text: "Pastikan buku sudah diserahkan kepada peminjam.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2C5AA0',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Verifikasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.showLoading();
                    
                    fetch(`http://127.0.0.1:8000/api/transactions/${id}/verifikasi-pinjam`, {
                        method: 'PUT',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({status: 'dipinjam'})
                    })
                    .then(res => res.json())
                    .then(() => {
                        Swal.fire('Berhasil!', 'Buku berhasil dipinjam.', 'success');
                        fetchTransactions();
                    })
                    .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan sistem.', 'error'));
                }
            });
            return; // Stop di sini untuk proses pinjam
        }

        // LOGIKA KEMBALI: Jika statusnya menunggu_verifikasi_kembali
        // (Ini adalah bagian yang sebelumnya terpicu salah)
        accEndpoint = `http://127.0.0.1:8000/api/transactions/${id}/verifikasi-kembali`;
        const tglJatuhTempo = btn.dataset.jatuhTempo || '';
        
        // Pastikan input hidden detailId dikosongkan karena ini verifikasi massal/header
        const detailIdElem = document.getElementById('detailId');
        if (detailIdElem) detailIdElem.value = '';
        
        calculateAndFillModal(null, tglJatuhTempo);
    }

    // 2. Tombol Konfirmasi Per Buku (Detail) - TETAP SAMA
    if (e.target.closest('.btn-acc-detail')) {
        const btn = e.target.closest('.btn-acc-detail');
        const id = btn.dataset.id;
        const tglJatuhTempo = btn.dataset.jatuhTempo;

        accEndpoint = `http://127.0.0.1:8000/api/transaction-detail/${id}/verify-return`;

        const detailIdElem = document.getElementById('detailId');
        if (detailIdElem) detailIdElem.value = id;
        
        calculateAndFillModal(id, tglJatuhTempo);
    }
});


function calculateAndFillModal(id, tglJatuhTempo) {
    const hariIni = new Date();
    hariIni.setHours(0,0,0,0);
    
    const jatuhTempo = new Date(tglJatuhTempo);
    jatuhTempo.setHours(0,0,0,0);

    let selisihHari = 0;
    if (tglJatuhTempo && hariIni > jatuhTempo) {
        const diffTime = Math.abs(hariIni - jatuhTempo);
        selisihHari = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    }
    // store in hidden field for use when submitting
    document.getElementById('jumlah_hari_telat').value = selisihHari;

    const statusElem = document.getElementById('status');
    const jenisElem = document.getElementById('jenis_denda');
    const dendaElem = document.getElementById('denda');
    const keteranganElem = document.getElementById('denda_keterangan');
    const formulaElem = document.getElementById('denda_formula');

    if (selisihHari > 0) {
        statusElem.value = 'terlambat';
        jenisElem.value = 'telat';
        const totalDenda = selisihHari * dendaPerHariAturan;
        dendaElem.value = totalDenda;
        
        // Tampilkan penjelasan denda
        keteranganElem.style.display = 'block';
        formulaElem.textContent = `${selisihHari} hari × ${formatCurrency(dendaPerHariAturan)} = ${formatCurrency(totalDenda)}`;
    } else {
        statusElem.value = 'dikembalikan';
        jenisElem.value = '';
        dendaElem.value = '0';
        keteranganElem.style.display = 'none';
    }
    // update hidden count as well (in case recalculated through other interactions)
    document.getElementById('jumlah_hari_telat').value = selisihHari;

    $('#modalAccDetail').modal('show');
}

// Submit modal
document.getElementById('btnSubmitAccDetail').addEventListener('click', function() {
    const statusVal = document.getElementById('status').value;
    const dendaVal = parseFloat(document.getElementById('denda').value) || 0;
    const catatanVal = document.getElementById('catatan').value; // <--- TAMBAHKAN INI
    let jenisDendaVal = document.getElementById('jenis_denda').value;

    if (statusVal === 'terlambat' && !jenisDendaVal) {
        jenisDendaVal = 'telat';
    }

    // Buat payload
    const data = {
        status: statusVal,
        denda: dendaVal,
        jenis_denda: jenisDendaVal || null,
        catatan: catatanVal, // <--- MASUKKAN KE PAYLOAD
        jumlah_hari_telat: parseInt(document.getElementById('jumlah_hari_telat').value) || 0
    };

    Swal.fire({
        title: 'Menyimpan...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    fetch(accEndpoint, {
        method: 'PUT',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    
        .then(async res => {
            const result = await res.json();
            if (!res.ok) {
                let msg = result.message;
                if (result.errors && result.errors.jenis_denda) msg = result.errors.jenis_denda[0];
                
                Swal.fire('Gagal!', msg, 'error');
                return;
            }

            // Tutup modal bootstrap
            $('#modalAccDetail').modal('hide');

            // Notifikasi sukses
            Swal.fire({
                icon: 'success',
                title: 'Berhasil disimpan',
                showConfirmButton: false,
                timer: 1500
            });

            fetchTransactions();
        })
        .catch(err => Swal.fire('Error', 'Gagal menghubungi server', 'error'));
});

// Fungsi Reset Filter
function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    renderTable(allTransactions); // Kembalikan ke data awal
}

document.getElementById('status').addEventListener('change', function(){
    const jenis = document.getElementById('jenis_denda');
    const denda = document.getElementById('denda');
    const catatan = document.getElementById('catatan'); // Ambil elemen catatan
    const keteranganElem = document.getElementById('denda_keterangan');
    const formulaElem = document.getElementById('denda_formula');

    if(this.value === 'terlambat'){
        jenis.value = 'telat';
        catatan.value = 'Keterlambatan pengembalian buku.'; // Catatan otomatis
        
        const tgl = document.getElementById('modalAccDetail')
                     .querySelector('[data-jatuh-tempo]')?.dataset?.jatuhTempo;
        if (tgl) {
            const total = hitungDendaOtomatis(tgl, dendaPerHariAturan);
            const hari = total / dendaPerHariAturan;
            denda.value = total;
            keteranganElem.style.display = total > 0 ? 'block' : 'none';
            formulaElem.textContent = `${hari} hari × ${formatCurrency(dendaPerHariAturan)} = ${formatCurrency(total)}`;
            document.getElementById('jumlah_hari_telat').value = hari;
        }
    } else if(this.value === 'rusak'){
        jenis.value = 'rusak';
        denda.value = '';
        catatan.value = '[RUSAK - Membayar denda perbaikan]'; 
        keteranganElem.style.display = 'block'; 
        formulaElem.textContent = 'Masukkan biaya perbaikan buku'; 
        document.getElementById('jumlah_hari_telat').value = 0;

    } else if(this.value === 'hilang'){
        jenis.value = 'hilang';
        denda.value = '0';
        catatan.value = '[HILANG - WAJIB GANTI BUKU FISIK!!]'; // Catatan otomatis
        keteranganElem.style.display = 'none';
        document.getElementById('jumlah_hari_telat').value = 0;

    } else {
        // Status: dikembalikan (Normal)
        jenis.value = '';
        denda.value = '0';
        catatan.value = ''; // Kosongkan catatan
        keteranganElem.style.display = 'none';
        document.getElementById('jumlah_hari_telat').value = 0;
    }
});

// Auto-calculate denda ketika jenis_denda berubah
document.getElementById('jenis_denda').addEventListener('change', function(){
    const dendaInput = document.getElementById('denda');
    const status = document.getElementById('status').value;
    const keteranganElem = document.getElementById('denda_keterangan');
    const catatanInput = document.getElementById('catatan');

    if(this.value === 'telat' && status === 'terlambat'){
        // Hitung denda telat otomatis (logika lama Anda)
        const tgl = document.getElementById('modalAccDetail').querySelector('[data-jatuh-tempo]')?.dataset?.jatuhTempo;
        if (tgl) {
            const total = hitungDendaOtomatis(tgl, dendaPerHariAturan);
            dendaInput.value = total;
            dendaInput.readOnly = true; 
            keteranganElem.style.display = 'block';
        }
    } 
    else if(this.value === 'rusak'){
        dendaInput.value = ''; 
        dendaInput.readOnly = false; // Admin input nominal uang perbaikan
        dendaInput.placeholder = "Masukkan biaya rusak...";
        keteranganElem.style.display = 'none';
        catatanInput.placeholder = "Sebutkan kerusakan buku...";
    } 
    else if(this.value === 'hilang'){
        dendaInput.value = '0';
        dendaInput.readOnly = true; 
        keteranganElem.style.display = 'none';
        catatanInput.value = "User wajib mengganti dengan buku fisik asli yang sama.";
        alert("STATUS HILANG: Denda uang diset Rp 0. Pastikan user mengganti buku fisik!");
    } 
    else {
        dendaInput.value = '0';
        dendaInput.readOnly = true;
        keteranganElem.style.display = 'none';
    }
});

</script>
@endsection