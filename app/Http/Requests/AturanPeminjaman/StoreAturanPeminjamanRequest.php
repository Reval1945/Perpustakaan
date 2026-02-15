<?php

namespace App\Http\Requests\AturanPeminjaman;

use Illuminate\Foundation\Http\FormRequest;

class StoreAturanPeminjamanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'maks_hari_pinjam' => 'required|integer|min:1',
            'denda_per_hari'   => 'required|numeric|min:0',
            'aktif'            => 'required|boolean',
            'keterangan'       => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'maks_hari_pinjam.required' => 'Maksimal hari pinjam wajib diisi',
            'denda_per_hari.required'   => 'Denda per hari wajib diisi',
            'aktif.required'            => 'Aktif wajib diisi',
            'aktif.boolean'             => 'Aktif harus bernilai true (1) atau false (0)'
        ];
    }
}
