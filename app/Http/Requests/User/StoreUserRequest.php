<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6',
            'role'        => 'required|in:superadmin,admin,user',
            'class'       => 'nullable|string|max:100',
            'roll_number' => 'nullable|string|max:50',
            'phone'       => 'nullable|string|max:20',
            'nisn'        => 'nullable|string|max:35',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama wajib diisi',
            'email.unique'      => 'Email sudah terdaftar',
            'role.in'           => 'Role tidak valid',
            'password.min'      => 'Password minimal 6 karakter',
        ];
    }
}
