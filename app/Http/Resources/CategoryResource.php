<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'deskripsi'  => $this->deskripsi,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s')
        ];
    }
}
