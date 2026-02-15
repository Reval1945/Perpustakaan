@extends('layouts.admin')

@section('title', 'Edit Pengembalian Buku')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-gray-800">Edit Pengembalian Buku</h4>

        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <!-- TANGGAL PINJAM -->
            <div class="mb-3">
                <strong>Tanggal Pinjam :</strong>
                <div class="text-primary font-weight-bold">
                    {{ \Carbon\Carbon::parse($trx->tanggal_pinjam)->translatedFormat('d F Y') }}
                </div>
            </div>

            <hr>

            <h6 class="font-weight-bold mb-3">
                Perpanjangan / Verifikasi Pengembalian
            </h6>

            <!-- INPUT JATUH TEMPO -->
            <div class="form-group">
                <label>Tanggal Jatuh Tempo Baru</label>
                <input type="date"
                       class="form-control"
                       id="tanggalInput"
                       value="{{ \Carbon\Carbon::parse($trx->tanggal_jatuh_tempo)->format('Y-m-d') }}">
            </div>

            <div class="alert alert-info small">
                Kosongkan tanggal jika hanya ingin verifikasi tanpa perpanjangan.
            </div>

            <div class="text-right">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    Batal
                </a>

                <button id="btnSave" class="btn btn-primary">
                    Simpan / Verifikasi
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>

const kode = "{{ $trx->kode_transaksi }}";

document.getElementById("btnSave").addEventListener("click", async ()=>{

    const token = localStorage.getItem("token");

    if(!token){
        alert("Login dulu");
        location.href="/login";
        return;
    }

    const btn = document.getElementById("btnSave");
    btn.disabled = true;
    btn.innerText = "Menyimpan...";

    const tanggal = document.getElementById("tanggalInput").value;

    try{

        let url;
        let body = {};

        if(tanggal){
            url = `/api/transactions/${kode}/jatuh-tempo`;
            body.tanggal_jatuh_tempo = tanggal;
        }

        else{
            url = `/api/transactions/${kode}/verifikasi-detail`;
        }

        const res = await fetch(url,{
            method:"PUT",
            headers:{
                Authorization:`Bearer ${token}`,
                "Content-Type":"application/json",
                Accept:"application/json"
            },
            body: JSON.stringify(body)
        });

        const data = await res.json();

        if(!res.ok)
            throw new Error(data.message);

        alert(data.message);
        location.href="/admin/transaksi/peminjaman";

    }catch(err){
        console.error(err);
        alert(err.message);
    }

    btn.disabled=false;
    btn.innerText="Simpan / Verifikasi";
});
</script>
@endsection
