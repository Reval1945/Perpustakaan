@extends('layouts.anggota')

@section('title', 'Riwayat Peminjaman')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="text-gray-800">Riwayat Peminjaman</h4>
    <div>
        <button class="btn btn-success">
            <i class="fas fa-print"></i> Cetak Riwayat
        </button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="bg-primary text-white text-center">
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Lama Pinjam</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody id="riwayat-body" class="text-center">
                <tr>
                    <td colspan="7">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
let semuaRiwayat = [];


// ================= LOAD DATA =================
async function loadRiwayat(){
    try{
        const token = localStorage.getItem('token');

        const res = await fetch('/api/transaksi-me',{
            headers:{
                'Authorization':'Bearer '+token,
                'Accept':'application/json'
            }
        });

        if(!res.ok) throw new Error(res.status);

        const result = await res.json();
        semuaRiwayat = result.data || [];

        renderRiwayat(semuaRiwayat);

    }catch(err){
        console.error(err);
        document.getElementById('riwayat-body').innerHTML=`
        <tr><td colspan="7">Gagal memuat data</td></tr>`;
    }
}



// ================= RENDER =================
function renderRiwayat(data){

    const tbody = document.getElementById('riwayat-body');
    tbody.innerHTML='';

    let no=1;

    function formatTanggal(tgl){
        const d=new Date(tgl);
        return d.toLocaleDateString('id-ID',{
            year:'numeric',
            month:'2-digit',
            day:'2-digit'
        });
    }

    data.forEach(trx=>{

        const detailRiwayat = trx.details.filter(d =>
            ['menunggu_verifikasi_kembali','dikembalikan','terlambat']
            .includes(d.status)
        );

        detailRiwayat.forEach(detail=>{

            let lama='-';
            let ket='-';

            if(detail.tanggal_kembali){
                const pinjam=new Date(trx.tanggal_pinjam);
                const kembali=new Date(detail.tanggal_kembali);

                const selisih=Math.ceil(
                    (kembali-pinjam)/(1000*60*60*24)
                );

                lama=selisih+' hari';
            }

            let badge='';

            if(detail.status==='dikembalikan')
                badge=`<span class="badge badge-success">Dikembalikan</span>`;
            else if(detail.status==='terlambat'){
                badge=`<span class="badge badge-danger">Terlambat</span>`;
                ket=`Telat ${detail.jumlah_hari_telat||0} hari`;
            }
            else
                badge=`<span class="badge badge-warning">Menunggu Verifikasi</span>`;


            tbody.innerHTML+=`
            <tr>
                <td>${no++}</td>
                <td>${detail.judul_buku}</td>
                <td>${formatTanggal(trx.tanggal_pinjam)}</td>
                <td>${detail.tanggal_kembali?formatTanggal(detail.tanggal_kembali):'-'}</td>
                <td>${lama}</td>
                <td>${badge}</td>
                <td>${ket}</td>
            </tr>`;
        });

    });

    if(tbody.innerHTML===''){
        tbody.innerHTML=`<tr>
            <td colspan="7">Tidak ditemukan data</td>
        </tr>`;
    }
}



// ================= SEARCH + FILTER =================
function applyFilter(){

    const keyword =
        document.getElementById('searchInput').value.toLowerCase();

    const tanggal =
        document.getElementById('filterTanggal').value;

    const hasil = semuaRiwayat.map(trx=>({

        ...trx,

        details: trx.details.filter(d=>{

            const cocokJudul =
                d.judul_buku.toLowerCase().includes(keyword);

            const cocokTanggal =
                !tanggal || trx.tanggal_pinjam.startsWith(tanggal);

            const statusValid =
                ['menunggu_verifikasi_kembali','dikembalikan','terlambat']
                .includes(d.status);

            return cocokJudul && cocokTanggal && statusValid;
        })

    })).filter(trx=>trx.details.length>0);

    renderRiwayat(hasil);
}



// ================= INIT =================
document.addEventListener('DOMContentLoaded',()=>{

    loadRiwayat();

    document.getElementById('searchInput')
        .addEventListener('input', applyFilter);

    document.getElementById('filterTanggal')
        .addEventListener('change', applyFilter);

});

</script>

@endsection
