<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'superadmin@gmail.com';

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'id'        => Str::uuid(),
                'kode_user' => 'SA000000001',
                'name'      => 'Super Admin',
                'email'     => $email,
                'password'  => Hash::make('password'),
                'role'      => 'superadmin',
            ]);
        }
    }
}
