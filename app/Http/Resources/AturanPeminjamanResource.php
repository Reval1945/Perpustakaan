<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class AturanPeminjamanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'maks_hari_pinjam'  => $this->maks_hari_pinjam,
            'denda_per_hari'    => $this->denda_per_hari,
            'aktif'             => (bool) $this->aktif,
            'keterangan'        => $this->keterangan,
            'created_at'        => $this->created_at
        ];
    }
}