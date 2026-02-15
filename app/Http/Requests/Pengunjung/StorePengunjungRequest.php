<?php

namespace App\Http\Requests\Pengunjung;

use Illuminate\Foundation\Http\FormRequest;

class StorePengunjungRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keperluan' => 'required|string|max:255',
            'tanggal_kunjungan' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'keperluan.required' => 'Keperluan harus diisi.',
            'tanggal_kunjungan.date' => 'Format tanggal tidak valid.',
        ];
    }
}
