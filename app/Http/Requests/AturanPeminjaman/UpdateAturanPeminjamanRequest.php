<?php

namespace App\Http\Requests\AturanPeminjaman;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAturanPeminjamanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'maks_hari_pinjam' => 'sometimes|integer|min:1',
            'denda_per_hari'   => 'sometimes|numeric|min:0',
            'aktif'            => 'sometimes|boolean',
            'keterangan'       => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'maks_hari_pinjam.required' => 'Maksimal hari pinjam wajib diisi',
            'denda_per_hari.required'   => 'Denda per hari wajib diisi',
        ];
    }
}
