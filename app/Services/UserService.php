<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function prepareCreateData(array $data): array
    {
        return [
            'id'          => Str::uuid(),
            'kode_user'   => User::generateKode($data['role']),
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'role'        => $data['role'],
            'class'       => $data['class'] ?? null,
            'roll_number' => $data['roll_number'] ?? null,
            'phone'       => $data['phone'] ?? null,
        ];
    }

    public function prepareUpdateData(array $data): array
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $data;
    }
}
