<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class AuthService
{
    public function login($request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah']
            ]);
        }

        // Hapus token lama
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'user'  => [
                'id'        => $user->id,
                'kode_user' => $user->kode_user,
                'nama'      => $user->name,
                'role'      => $user->role,
            ]
        ];
    }

    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'id'          => Str::uuid(),
                'kode_user'   => User::generateKode('user'),
                'name'        => $data['name'],
                'email'       => $data['email'],
                'password'    => Hash::make($data['password']),
                'class'       => $data['class'] ?? null,
                'roll_number' => $data['roll_number'] ?? null,
                'nisn'        => $data['nisn'] ?? null,
                'phone'       => $data['phone'] ?? null,
                'role'        => 'user'
            ]);

            return [
                'message' => 'Registrasi berhasil',
                'token'   => $user->createToken('auth_token')->plainTextToken,
                'user'    => [
                    'id'        => $user->id,
                    'kode_user' => $user->kode_user,
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'role'      => $user->role
                ]
            ];
        });
    }

    public function logout($request): array
    {
        $request->user()->currentAccessToken()->delete();
        // $request->user()->tokens()->delete();

        return [
            'message' => 'Logout berhasil'
        ];
    }

    public function verifyUserForReset(array $data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return ['status' => 422, 'errors' => $validator->errors()];
        }

        $user = User::where('email', $data['email'])
                    ->first();

        if (!$user) {
            return [
                'status' => 404, 
                'message' => 'Kombinasi Email dan NISN tidak ditemukan.'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Verifikasi berhasil.',
            'user_id' => $user->id
        ];
    }

    public function resetPassword(array $data)
    {
        $validator = Validator::make($data, [
            'user_id'  => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return ['status' => 422, 'errors' => $validator->errors()];
        }

        $user = User::find($data['user_id']);
        
        if (!$user) {
            return ['status' => 404, 'message' => 'User tidak valid.'];
        }

        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        return [
            'status' => 200,
            'message' => 'Password berhasil diubah, silakan login.'
        ];
    }

}
