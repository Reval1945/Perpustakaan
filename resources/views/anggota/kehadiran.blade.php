@extends('layouts.anggota')

@section('title', 'Kehadiran')

@section('content')
<div class="container-fluid animate-up">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Presensi Pengunjung</h1>
            <p class="text-muted small mb-0">Silakan isi form di bawah ini saat tiba di perpustakaan.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card modern-card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-4 border-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-success-soft mr-3">
                            <i class="fas fa-id-card text-success"></i>
                        </div>
                        <h6 class="m-0 font-weight-bold text-gray-800" style="font-size: 1.1rem;">Form Kehadiran</h6>
                    </div>
                </div>

                <div class="card-body px-4 pb-4">
                    <div id="successMessage" class="alert badge-success-soft d-none mb-4 animate-up">
                        <i class="fas fa-check-circle mr-2"></i> Kehadiran Anda berhasil dicatat hari ini!
                    </div>

                    <form id="formKehadiran">
                        <div class="form-group mb-4">
                            <label class="text-overline text-primary">Tanggal Kunjungan</label>
                            <div class="input-group bg-light rounded-pill px-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-0"><i class="far fa-calendar-alt text-muted"></i></span>
                                </div>
                                <input type="text" class="form-control bg-transparent border-0 font-weight-bold text-gray-800" 
                                    value="{{ now()->format('d F Y') }}" readonly style="cursor: default;">
                            </div>
                            <input type="hidden" id="tanggal_kunjungan" value="{{ now()->toDateString() }}">
                        </div>

                        <div class="form-group mb-4">
                            <label class="text-overline text-primary">Kegiatan / Keperluan</label>
                            <textarea name="keperluan" class="form-control modern-input" rows="4" 
                                placeholder="Misal: Meminjam buku paket, atau belajar..." required></textarea>
                        </div>

                        <button type="submit" id="btnSubmit" class="btn btn-primary btn-block py-3 font-weight-bold shadow-sm">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Kehadiran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Reuse Root & Variables */
    .modern-card {
        border-radius: 20px !important;
        overflow: hidden;
    }

    .text-overline {
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        font-size: 0.7rem;
        display: block;
        margin-bottom: 8px;
    }

    /* Input Styling */
    .modern-input {
        border-radius: 15px;
        border: 2px solid #eaecf4;
        padding: 15px;
        transition: all 0.3s ease;
    }

    .modern-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
        color: #495057;
    }

    /* Icon circles (Soft Colors) */
    .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .bg-success-soft { background-color: rgba(28, 200, 138, 0.1) !important; }
    .badge-success-soft { 
        background-color: #dffff3; 
        color: #155724; 
        border-radius: 12px;
        border: none;
        padding: 15px;
    }

    /* Animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-up { animation: fadeInUp 0.5s ease-out forwards; }

    /* Button Hover */
    #btnSubmit {
        transition: all 0.3s ease;
        border-radius: 15px;
    }
    #btnSubmit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4) !important;
    }
</style>

<script>
document.getElementById('formKehadiran').addEventListener('submit', function (e) {
    e.preventDefault();
    const btn = document.getElementById('btnSubmit');
    const successBox = document.getElementById('successMessage');
    
    // Loading State
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';

    const token = localStorage.getItem('token');
    const data = {
        keperluan: document.querySelector('[name="keperluan"]').value,
        tanggal_kunjungan: document.getElementById('tanggal_kunjungan').value
    };

    fetch('http://127.0.0.1:8000/api/pengunjung', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        // Tampilkan pesan sukses secara visual
        successBox.classList.remove('d-none');
        document.getElementById('formKehadiran').reset();
        
        // Kembalikan tombol
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Kirim Kehadiran';
        
        // Sembunyikan pesan setelah 5 detik
        setTimeout(() => {
            successBox.classList.add('d-none');
        }, 5000);
    })
    .catch(err => {
        console.error(err);
        alert('Gagal mencatat kehadiran, silakan coba lagi.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Kirim Kehadiran';
    });
});
</script>
@endsection