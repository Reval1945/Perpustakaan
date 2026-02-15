<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'kode_user'   => $this->kode_user,
            'name'        => $this->name,
            'email'       => $this->email,
            'role'        => $this->role,
            'class'       => $this->class,
            'roll_number' => $this->roll_number,
            'phone'       => $this->phone,
            'created_at'  => $this->created_at?->format('Y-m-d H:i:s')
        ];
    }
}
