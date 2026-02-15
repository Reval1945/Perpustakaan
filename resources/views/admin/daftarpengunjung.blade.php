@extends('layouts.admin')

@section('title', 'Daftar Pengunjung')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="text-gray-800 fw-bold">Daftar Pengunjung</h4>

    <button id="btnCetakAnggota" class="btn btn-success shadow-sm">
        <i class="fas fa-file-excel"></i> Export Excel
    </button>
</div>


<div class="card shadow">
<div class="card-body">

<div class="table-responsive">
<table class="table table-bordered table-hover text-center align-middle">

<thead class="bg-primary text-white">
<tr>
    <th>Nama</th>
    <th>Kelas</th>
    <th>NISN</th>
    <th>Keperluan</th>
    <th>Tanggal</th>
    <th width="140">Aksi</th>
</tr>
</thead>

<tbody id="pengunjung">
<tr>
<td colspan="6" class="text-muted">Memuat data...</td>
</tr>
</tbody>

</table>
</div>

</div>
</div>



{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEdit">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Edit Pengunjung</h5>
<button class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

<input type="hidden" id="editId">

<label>Keperluan</label>
<input type="text" id="editKeperluan" class="form-control mb-3">

<label>Tanggal Kunjungan</label>
<input type="date" id="editTanggal" class="form-control">

</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-dismiss="modal">Batal</button>
<button class="btn btn-primary" id="btnUpdate">Simpan Perubahan</button>
</div>

</div>
</div>
</div>

@endsection



@section('scripts')
<script>

const tbody = document.getElementById("pengunjung");
const token = localStorage.getItem("token");


// ================= LOAD DATA =================
document.addEventListener("DOMContentLoaded", loadData);

async function loadData(){

    if(!token){
        tbody.innerHTML=`<tr><td colspan="6">Login terlebih dahulu</td></tr>`;
        return;
    }

    try{

        const res = await fetch("/api/pengunjung",{
            headers:{
                "Authorization":"Bearer "+token,
                "Accept":"application/json"
            }
        });

        if(!res.ok) throw new Error("Gagal mengambil data");

        const json = await res.json();
        const data = json.data;

        if(!data.length){
            tbody.innerHTML=`<tr><td colspan="6" class="text-muted">Belum ada data pengunjung</td></tr>`;
            return;
        }

        let html="";

        data.forEach(p=>{
            html+=`
            <tr>
                <td class="fw-bold">${p.nama}</td>
                <td>${p.kelas}</td>
                <td>${p.nisn}</td>
                <td>${p.keperluan}</td>
                <td>${formatTanggal(p.tanggal_kunjungan)}</td>
                <td>

                    <button class="btn btn-sm btn-warning mr-1"
                        onclick="editData('${p.id}','${p.keperluan}','${p.tanggal_kunjungan}')">
                        <i class="fas fa-edit"></i>
                    </button>

                    <button class="btn btn-sm btn-danger"
                        onclick="hapusData('${p.id}')">
                        <i class="fas fa-trash"></i>
                    </button>

                </td>
            </tr>
            `;
        });

        tbody.innerHTML=html;

    }catch(err){
        tbody.innerHTML=`<tr><td colspan="6" class="text-danger">${err.message}</td></tr>`;
    }
}



// ================= FORMAT TANGGAL =================
function formatTanggal(tgl){
    return new Date(tgl).toLocaleDateString("id-ID",{
        day:"2-digit",
        month:"long",
        year:"numeric"
    });
}



// ================= EDIT =================
function editData(id,keperluan,tanggal){
    document.getElementById("editId").value=id;
    document.getElementById("editKeperluan").value=keperluan;
    document.getElementById("editTanggal").value=tanggal;
    $("#modalEdit").modal("show");
}



// ================= UPDATE =================
document.getElementById("btnUpdate").onclick = async ()=>{

    const id=document.getElementById("editId").value;

    const body={
        keperluan:document.getElementById("editKeperluan").value,
        tanggal_kunjungan:document.getElementById("editTanggal").value
    };

    const btn=this;
    btn.disabled=true;
    btn.innerText="Menyimpan...";

    const res=await fetch(`/api/pengunjung/${id}`,{
        method:"PUT",
        headers:{
            "Authorization":"Bearer "+token,
            "Content-Type":"application/json",
            "Accept":"application/json"
        },
        body:JSON.stringify(body)
    });

    btn.disabled=false;
    btn.innerText="Simpan Perubahan";

    if(res.ok){
        $("#modalEdit").modal("hide");
        loadData();
    }else{
        alert("Gagal update data");
    }
};



// ================= DELETE =================
async function hapusData(id){

    if(!confirm("Yakin ingin menghapus data ini?")) return;

    const res=await fetch(`/api/pengunjung/${id}`,{
        method:"DELETE",
        headers:{
            "Authorization":"Bearer "+token,
            "Accept":"application/json"
        }
    });

    if(res.ok){
        loadData();
    }else{
        alert("Gagal menghapus data");
    }
}



// ================= EXPORT EXCEL =================
document.getElementById("btnCetakAnggota").addEventListener("click", async ()=>{

    const res = await fetch("/api/pengunjung/export",{
        headers:{
            "Authorization":"Bearer "+token
        }
    });

    if(!res.ok){
        alert("Gagal export");
        return;
    }

    const blob = await res.blob();

    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "data-pengunjung.xlsx";
    a.click();
});


</script>
@endsection
