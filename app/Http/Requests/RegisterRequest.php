<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'class'       => 'nullable|string|max:100',
            'roll_number' => 'nullable|string|max:50',
            'phone'       => 'nullable|string|max:20',
            "nisn"     => 'nullable|string|max:35',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama wajib diisi',
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Format email tidak valid',
            'email.unique'      => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min'      => 'Password minimal 6 karakter',
            'class.max'         => 'Nama kelas terlalu panjang',
            'roll_number.max'   => 'Nomor absen terlalu panjang',
            'phone.max'         => 'Nomor telepon terlalu panjang',
        ];
    }
}
