@extends('layouts.admin')

@section('title', 'Edit Jatuh Tempo')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0" style="color: var(--dark); font-weight: 700;">
            Edit Jatuh Tempo
        </h1>
        <a href="/admin/transaksi/peminjaman" class="btn btn-sm btn-secondary shadow-sm" style="border-radius: 8px;">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body p-4">

            @if(isset($detail))
                <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius: 10px; background: #e0f2fe; color: #0369a1;">
                    <i class="fas fa-book mr-2"></i> 
                    Mode Edit Buku: <strong>{{ $detail->judul_buku }}</strong>
                </div>
            @else
                <div class="alert alert-warning border-0 shadow-sm mb-4" style="border-radius: 10px; background: #fff3cd; color: #856404;">
                    <i class="fas fa-layer-group mr-2"></i> 
                    Mode Edit: <strong>Semua Buku dalam Transaksi {{ $trx->kode_transaksi }}</strong>
                </div>
            @endif

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="text-muted small font-weight-bold">KODE TRANSAKSI</label>
                    <div class="h5 font-weight-bold text-primary">{{ $trx->kode_transaksi }}</div>
                </div>
                <div class="col-md-6 text-md-right">
                    <label class="text-muted small font-weight-bold">TANGGAL PINJAM</label>
                    <div class="text-dark font-weight-bold">
                        {{ \Carbon\Carbon::parse($trx->tanggal_pinjam)->translatedFormat('d F Y') }}
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="form-group mb-4">
                <label class="font-weight-bold text-muted small">TANGGAL JATUH TEMPO BARU</label>
                <input type="date" 
                    id="tanggalInput" 
                    class="form-control form-control-lg border-0 shadow-sm" 
                    style="background: #f8f9fc; border-radius: 10px;"
                    value="{{ \Carbon\Carbon::parse($defaultDate)->format('Y-m-d') }}">
                
                @if($detail && $detail->tgl_permintaan_perpanjangan)
                    <small class="text-info font-weight-bold">
                        * Anggota meminta perpanjangan hingga: {{ \Carbon\Carbon::parse($detail->tgl_permintaan_perpanjangan)->translatedFormat('d F Y') }}
                    </small>
                @endif
            </div>

            <div class="text-right">
                <button id="btnSave" class="btn btn-primary px-4 shadow" style="border-radius: 10px;">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pastikan ID Detail (UUID) terdeteksi dengan benar dari PHP
    const detailId = "{{ isset($detail) ? $detail->id : '' }}";
    const kodeTrx = "{{ $trx->kode_transaksi }}";

    document.getElementById("btnSave").addEventListener("click", async () => {
        const tanggal = document.getElementById("tanggalInput").value;
        const token = localStorage.getItem("token");

        if (!tanggal) {
            Swal.fire('Opps!', 'Pilih tanggal jatuh tempo terlebih dahulu.', 'warning');
            return;
        }

        // Dialog Konfirmasi
        const result = await Swal.fire({
            title: 'Konfirmasi Simpan',
            text: detailId ? "Hanya jatuh tempo buku ini yang akan berubah." : "Semua buku dalam transaksi ini akan diperbarui.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'var(--primary)',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        // Button Loading State
        const btn = document.getElementById("btnSave");
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm mr-2"></span> Menyimpan...`;

        try {
            // Tentukan Endpoint API berdasarkan Mode
            let url = detailId 
                ? `/api/transactions/jatuh-tempo-detail/${detailId}` 
                : `/api/transactions/${kodeTrx}/jatuh-tempo`;

            const res = await fetch(url, {
                method: "PUT",
                headers: {
                    "Authorization": `Bearer ${token}`,
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ tanggal_jatuh_tempo: tanggal })
            });

            const data = await res.json();

            if (!res.ok) throw new Error(data.message || 'Terjadi kesalahan sistem');

            await Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });

            window.location.href = "/admin/transaksi/peminjaman";

        } catch (err) {
            Swal.fire('Gagal', err.message, 'error');
            btn.disabled = false;
            btn.innerHTML = `<i class="fas fa-save mr-2"></i> Simpan`;
        }
    });
});
</script>
@endsection