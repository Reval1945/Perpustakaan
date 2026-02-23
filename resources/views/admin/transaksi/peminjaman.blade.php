@extends('layouts.admin')

@section('title', 'Peminjaman Buku')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="text-gray-800">Peminjaman Buku</h4>

    <div>
        <a href="/admin/transaksi/tambahpeminjaman" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Peminjaman
        </a>
    </div>
</div>

    <div class="card shadow mb-4">
        <table class="table table-bordered">
            <thead class="bg-primary text-white text-center">
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Peminjam</th>
                    <th>Role</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="transactionTable">
                <tr>
                    <td colspan="8" class="text-center text-muted">Loading data...</td>
                </tr>
            </tbody>

        </table>

        <!-- Modal Konfirmasi Detail -->
        <div class="modal fade" id="modalAccDetail" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pengembalian</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="detailId">

                <div class="form-group">
                <label>Status</label>
                <select id="status" class="form-control">
                    <option value="dikembalikan">Dikembalikan</option>
                    <option value="terlambat">Terlambat</option>
                </select>
                </div>

                <div class="form-group">
                <label>Jenis Denda</label>
                <select id="jenis_denda" class="form-control">
                    <option value="">- Tidak Ada -</option>
                    <option value="uang">Uang</option>
                    <option value="barang">Barang</option>
                </select>
                </div>

                <div class="form-group">
                <label>Denda</label>
                <input type="number" id="denda" class="form-control" placeholder="Masukkan denda">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="btnSubmitAccDetail">Simpan</button>
            </div>
            </div>
        </div>
        </div>

    </div>

    <!-- FETCH DATA TRANSAKSI -->
<!-- Modal Konfirmasi Detail -->
<div class="modal fade" id="modalAccDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Konfirmasi Pengembalian</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <input type="hidden" id="detailId">

            <div class="form-group">
            <label>Status</label>
            <select id="status" class="form-control">
                <option value="dikembalikan">Dikembalikan</option>
                <option value="terlambat">Terlambat</option>
            </select>
            </div>

            <div class="form-group">
            <label>Jenis Denda</label>
            <select id="jenis_denda" class="form-control">
                <option value="">- Tidak Ada -</option>
                <option value="uang">Uang</option>
                <option value="barang">Barang</option>
            </select>
            </div>

            <div class="form-group">
            <label>Denda</label>
            <input type="number" id="denda" class="form-control" placeholder="Masukkan denda">
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button class="btn btn-primary" id="btnSubmitAccDetail">Simpan</button>
        </div>
        </div>
    </div>
</div>

<script>
function formatTanggal(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
}

function getVerificationType(trx) {
    const statuses = trx.details.map(d => d.status);
    const total = statuses.length;

    const menunggu = statuses.filter(s => s === 'menunggu_verifikasi' || s === 'menunggu_verifikasi_kembali').length;

    if (menunggu === 0) return 'none';
    if (menunggu === total) return 'all';
    return 'partial';
}

// FETCH STATUS
function getTransactionStatus(trx) {
    const detailStatus = trx.details.map(d => d.status);

    const total = detailStatus.length;
    const dipinjam = detailStatus.filter(s => s === 'dipinjam').length;
    const dikembalikan = detailStatus.filter(s => s === 'dikembalikan').length;
    const menunggu = detailStatus.filter(s => s === 'menunggu_verifikasi').length;

    if (menunggu > 0) return 'menunggu_verifikasi';
    if (dikembalikan === total) return 'dikembalikan';
    if (dipinjam === total) return 'dipinjam';
    if (dikembalikan > 0 && dipinjam > 0) return 'sebagian_dipinjam';
    return trx.status;
}

function statusBadge(status) {
    if (status === 'dipinjam') return '<span class="badge badge-success">Dipinjam</span>';
    if (status === 'menunggu_verifikasi') return '<span class="badge badge-warning">Menunggu Verifikasi</span>';
    if (status === 'mengajukan_pengembalian') return '<span class="badge badge-info">Mengajukan Pengembalian</span>';
    if (status === 'dikembalikan') return '<span class="badge badge-secondary">Dikembalikan</span>';
    if (status === 'ditolak') return '<span class="badge badge-danger">Ditolak</span>';
    if (status === 'sebagian_dipinjam') return '<span class="badge badge-warning">Sebagian Dipinjam</span>';
    return `<span class="badge badge-dark">${status}</span>`;
}

function fetchTransactions() {
    const token = localStorage.getItem('token');

    fetch('http://127.0.0.1:8000/api/transactions', {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(res => {
        const tbody = document.getElementById('transactionTable');
        tbody.innerHTML = '';

        if (!res.data || res.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted">Tidak ada data peminjaman</td></tr>`;
            return;
        }

        const filteredData = res.data.filter(trx => trx.details.some(d => d.status !== 'dikembalikan'));

        if (filteredData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted">Tidak ada data peminjaman</td></tr>`;
            return;
        }

        filteredData.forEach((trx, index) => {
            const detailRows = trx.details.map((d, i) => {

                let actionDetail = `
                    <a href="/admin/transaksi/detail/edit/${d.id}" class="btn btn-warning btn-sm mr-1">
                        <i class="fas fa-edit"></i>
                    </a>
                `;

                // ✅ Tambahkan kondisi untuk pengembalian
                if ((d.status === 'menunggu_verifikasi' || d.status === 'menunggu_verifikasi_kembali') 
                    && getVerificationType(trx) === 'partial') {
                    actionDetail += `
                        <button 
                            class="btn btn-success btn-sm btn-acc-detail" 
                            data-id="${d.id}"
                            title="Konfirmasi Buku">
                            Konfirmasi
                        </button>
                    `;
                }

                return `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${d.book_stock?.kode_eksemplar ?? '-'}</td>
                        <td>${d.judul_buku}</td>
                        <td>${d.status}</td>
                        <td>${actionDetail}</td>
                    </tr>
                `;
            }).join('');

            const row = `
                <!-- ROW UTAMA -->
                <tr class="text-center main-row" data-id="${trx.id}" style="cursor:pointer;">
                    <td>${index + 1}</td>
                    <td>${trx.kode_transaksi}</td>
                    <td>${trx.user.name}</td>
                    <td>${trx.user.role}</td>
                    <td>${formatTanggal(trx.tanggal_pinjam)}</td>
                    <td>${formatTanggal(trx.tanggal_jatuh_tempo)}</td>
                    <td>${statusBadge(getTransactionStatus(trx))}</td>
                    <td>${renderActionButton(trx)}</td>
                </tr>

                <!-- ROW EXPAND (DROPDOWN) -->
                <tr id="expand-${trx.id}" class="expand-row" style="display:none;">
                    <td colspan="8">
                        <div class="text-center text-muted mb-2">
                            <span class="expand-toggle" data-id="${trx.id}"></span>
                        </div>

                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Buku</th>
                                    <th>Judul Buku</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${detailRows}
                            </tbody>
                        </table>
                    </td>
                </tr>
            `;

            tbody.innerHTML += row;
        });
    })
    .catch(err => {
        console.error(err);
        document.getElementById('transactionTable').innerHTML = `<tr><td colspan="8" class="text-center text-danger">Gagal load data</td></tr>`;
    });
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

// Render tombol action utama
function renderActionButton(trx) {
    const verificationType = getVerificationType(trx);
    let buttons = `
        <button class="btn btn-info btn-sm btn-detail mr-1" data-id="${trx.id}" title="Detail">
            <i class="fas fa-eye"></i>
        </button>

        <a href="/admin/transaksi/edit/${trx.id}" class="btn btn-warning btn-sm mr-1" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
    `;

    if (verificationType === 'all') {
        buttons += `
            <button 
                class="btn btn-success btn-sm btn-acc mr-1" 
                data-id="${trx.id}"
                data-kembali="${trx.details[0]?.tanggal_kembali ?? ''}"
                title="Konfirmasi Semua">
                Konfirmasi
            </button>
        `;
    }

    return `<div class="d-flex justify-content-center">${buttons}</div>`;
}

document.addEventListener('DOMContentLoaded', fetchTransactions);

// =========================
// ACC STATUS / MODAL
// =========================
let accEndpoint = '';

document.addEventListener('click', function(e) {
    const token = localStorage.getItem('token');

    // ACC TRANSAKSI (pinjam)
    if (e.target.closest('.btn-acc')) {
        const btn = e.target.closest('.btn-acc');
        const id = btn.dataset.id;
        const kembali = btn.dataset.kembali;

        if (!kembali || kembali === '' || kembali === 'null') {
            fetch(`http://127.0.0.1:8000/api/transactions/${id}/verifikasi-pinjam`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({status: 'dipinjam'})
            })
            .then(res => res.json())
            .then(res => fetchTransactions())
            .catch(err => alert('Gagal konfirmasi peminjaman'));
            return;
        }

        accEndpoint = `http://127.0.0.1:8000/api/transactions/${id}/verifikasi-kembali`;
        document.getElementById('status').value = 'dikembalikan';
        document.getElementById('jenis_denda').value = '';
        document.getElementById('denda').value = '';
        $('#modalAccDetail').modal('show');
    }

    // ACC DETAIL (pengembalian)
    if (e.target.closest('.btn-acc-detail')) {
        const id = e.target.closest('.btn-acc-detail').dataset.id;
        accEndpoint = `http://127.0.0.1:8000/api/transaction-detail/${id}/verify-return`;

        document.getElementById('detailId').value = id;
        document.getElementById('status').value = 'dikembalikan';
        document.getElementById('jenis_denda').value = '';
        document.getElementById('denda').value = '';
        $('#modalAccDetail').modal('show');
    }
});

// Submit modal
document.getElementById('btnSubmitAccDetail').addEventListener('click', function() {
    if (!accEndpoint) {
        alert('Endpoint belum diset!');
        return;
    }

    const data = {
        status: document.getElementById('status').value,
        jenis_denda: document.getElementById('jenis_denda').value,
        denda: document.getElementById('denda').value
    };

    const token = localStorage.getItem('token');

    fetch(accEndpoint, {
        method: 'PUT',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        $('#modalAccDetail').modal('hide');
        fetchTransactions();
    })
    .catch(err => alert('Gagal konfirmasi'));
});
</script>


@endsection