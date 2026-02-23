<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'kode_buku'   => $this->kode_buku,
            'judul'       => $this->judul,
            'sinopsis'    => $this->sinopsis,
            'kategori'    => $this->category->name ?? null,
            'penulis'     => $this->penulis,
            'penerbit'    => $this->penerbit,
            'tahun'       => $this->tahun,
            'rak'         => $this->rak,
            'nomor_rak'   => $this->nomor_rak,
            'image' => $this->image 
                ? asset('storage/' . $this->image) 
                : null,
           'available_stock' => $this->stocks()->where('status','tersedia')->count(),
        ];
    }
}
