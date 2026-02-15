<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'             => 'sometimes|exists:users,id',
            'book_id'             => 'sometimes|exists:books,id',
            'tanggal_pinjam'      => 'sometimes|date',
            'tanggal_jatuh_tempo' => 'sometimes|date|after_or_equal:tanggal_pinjam',
            'tanggal_kembali'     => 'nullable|date|after_or_equal:tanggal_pinjam',
            'status'              => [
                'sometimes',
                Rule::in(['dipinjam', 'dikembalikan', 'terlambat', 'hilang'])
            ],
            'kode_buku'           => 'sometimes|string|max:50',
            'judul_buku'          => 'sometimes|string|max:255',
        ];
    }
}
