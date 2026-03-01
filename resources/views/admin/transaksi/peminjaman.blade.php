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
                    <td colspan="9" class="text-center text-muted">Loading data...</td>
                </tr>
            </tbody>

        </table>
    </div>

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
                <!-- store calculated days late for submission -->
                <input type="hidden" id="jumlah_hari_telat" value="0">

                <div class="form-group">
                <label>Status</label>
                <select id="status" class="form-control">
                    <option value="dikembalikan">Dikembalikan</option>
                    <option value="terlambat">Terlambat</option>
                </select>
                </div>

                <div class="form-group">
                <label>Jenis Pelanggaran (Denda)</label>
                <select id="jenis_denda" class="form-control">
                    <option value="">- Tidak Ada -</option>
                    <option value="telat">Telat (Uang)</option>
                    <option value="rusak">Buku Rusak</option>
                    <option value="hilang">Buku Hilang</option>
                </select>
            </div>

                <div class="form-group">
                    <label>Denda</label>
                    <input type="number" id="denda" class="form-control" placeholder="Masukkan denda">
                    <small class="form-text text-muted" id="denda_keterangan" style="display:none;">
                        <strong>Penjelasan:</strong> <span id="denda_formula"></span>
                    </small>
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
            tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted">Tidak ada data peminjaman</td></tr>`;
            return;
        }

        // Sembunyikan transaksi yang sudah semua dikembalikan
        // dan juga sembunyikan transaksi dengan status keseluruhan 'terlambat'
        const filteredData = res.data.filter(trx =>
            trx.details.some(d => d.status !== 'dikembalikan')
            && getTransactionStatus(trx) !== 'terlambat'
        );

        if (filteredData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted">Tidak ada data peminjaman</td></tr>`;
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
                            data-jatuh-tempo="${d.tanggal_jatuh_tempo}">
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

            // Hitung denda otomatis untuk transaksi ini
            const dendaOtomatis = hitungDendaOtomatis(trx.tanggal_jatuh_tempo, dendaPerHariAturan);

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
        document.getElementById('transactionTable').innerHTML = `<tr><td colspan="9" class="text-center text-danger">Gagal load data</td></tr>`;
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
                data-jatuh-tempo="${trx.tanggal_jatuh_tempo || ''}"
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

    // Tombol Konfirmasi Utama (Pinjam)
    if (e.target.closest('.btn-acc')) {
        const btn = e.target.closest('.btn-acc');
        const id = btn.dataset.id;
        const kembali = btn.dataset.kembali;

        if (!kembali || kembali === '' || kembali === 'null') {
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
            .then(() => fetchTransactions())
            .catch(() => alert('Gagal konfirmasi peminjaman'));
            return;
        }

        accEndpoint = `http://127.0.0.1:8000/api/transactions/${id}/verifikasi-kembali`;
        // Gunakan jatuh tempo dari data transaksi atau detail pertama
        const tglJatuhTempo = btn.dataset.jatuhTempo || '';
        calculateAndFillModal(null, tglJatuhTempo);
    }

    // Tombol Konfirmasi Per Buku (Detail)
    if (e.target.closest('.btn-acc-detail')) {
        const btn = e.target.closest('.btn-acc-detail');
        const id = btn.dataset.id;
        const tglJatuhTempo = btn.dataset.jatuhTempo;

        accEndpoint = `http://127.0.0.1:8000/api/transaction-detail/${id}/verify-return`;

        document.getElementById('detailId').value = id;
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
    let jenisDendaVal = document.getElementById('jenis_denda').value;

    // OTOMATISASI: Jika status terlambat, jenis_denda HARUS 'telat'
    if (statusVal === 'terlambat' && !jenisDendaVal) {
            jenisDendaVal = 'telat';
        }

    // Buat payload
    const data = {
        status: statusVal,
        denda: dendaVal,
        jenis_denda: jenisDendaVal || null,
        jumlah_hari_telat: parseInt(document.getElementById('jumlah_hari_telat').value) || 0
    };

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
            // Tampilkan pesan error spesifik dari Laravel (errors.jenis_denda[0])
            let msg = result.message;
            if (result.errors && result.errors.jenis_denda) msg = result.errors.jenis_denda[0];
            alert("Gagal: " + msg);
            return;
        }
        $('#modalAccDetail').modal('hide');
        fetchTransactions();
    });
});

document.getElementById('status').addEventListener('change', function(){
    const jenis = document.getElementById('jenis_denda');
    const denda = document.getElementById('denda');
    const keteranganElem = document.getElementById('denda_keterangan');
    const formulaElem = document.getElementById('denda_formula');

    if(this.value === 'terlambat'){
        jenis.value = 'telat';
        // hitung ulang denda berdasarkan tanggal jatuh tempo jika tersedia di modal
        const tgl = document.getElementById('modalAccDetail')
                     .querySelector('[data-jatuh-tempo]')?.dataset?.jatuhTempo;
        if (tgl) {
            const total = hitungDendaOtomatis(tgl, dendaPerHariAturan);
            denda.value = total;
            keteranganElem.style.display = total > 0 ? 'block' : 'none';
            formulaElem.textContent = `${total === 0 ? 0 : `...`}`; // formula already set during open
            // also update jumlah hari telat hidden
            const hari = total / dendaPerHariAturan;
            document.getElementById('jumlah_hari_telat').value = hari;
        }
    } else if(this.value === 'rusak'){
        jenis.value = 'rusak';
        document.getElementById('jumlah_hari_telat').value = 0;
    } else if(this.value === 'hilang'){
        jenis.value = 'hilang';
        document.getElementById('jumlah_hari_telat').value = 0;
    } else {
        jenis.value = '';
        denda.value = '0';
        keteranganElem.style.display = 'none';
        document.getElementById('jumlah_hari_telat').value = 0;
    }
});

// Auto-calculate denda ketika jenis_denda berubah
document.getElementById('jenis_denda').addEventListener('change', function(){
    const denda = document.getElementById('denda');
    const status = document.getElementById('status').value;
    const keteranganElem = document.getElementById('denda_keterangan');
    const formulaElem = document.getElementById('denda_formula');

    if(this.value === 'telat' && status === 'terlambat'){
        // hitung ulang berdasarkan tanggal jatuh tempo
        const tgl = document.getElementById('modalAccDetail')
                     .querySelector('[data-jatuh-tempo]')?.dataset?.jatuhTempo;
        if (tgl) {
            const selisih = hitungDendaOtomatis(tgl, dendaPerHariAturan) / dendaPerHariAturan;
            const total = hitungDendaOtomatis(tgl, dendaPerHariAturan);
            denda.value = total;
            keteranganElem.style.display = total > 0 ? 'block' : 'none';
            formulaElem.textContent = `${selisih} hari × ${formatCurrency(dendaPerHariAturan)} = ${formatCurrency(total)}`;
            document.getElementById('jumlah_hari_telat').value = selisih;
        }
    } else if(this.value === 'rusak' || this.value === 'hilang'){
        denda.value = '0';
        keteranganElem.style.display = 'none';
        document.getElementById('jumlah_hari_telat').value = 0;
    } else {
        denda.value = '0';
        keteranganElem.style.display = 'none';
        document.getElementById('jumlah_hari_telat').value = 0;
    }
});

</script>
@endsection