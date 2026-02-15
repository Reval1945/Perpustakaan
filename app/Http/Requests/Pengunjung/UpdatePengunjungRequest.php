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
            'keperluan' => 'required|string|max:255',
            'tanggal_kunjungan' => 'required|date'
        ];
    }
}
