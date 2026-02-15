<?php

namespace App\Http\Requests\Pengunjung;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengunjungRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'              => 'required|string|max:255',
            'kelas'             => 'nullable|string|max:50',
            'nisn'              => 'nullable|string|max:20',
            'keperluan'         => 'nullable|string',
            'tanggal_kunjungan' => 'required|date',
        ];
    }
}
