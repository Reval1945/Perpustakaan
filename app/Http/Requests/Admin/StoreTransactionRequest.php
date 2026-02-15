<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'   => 'required|exists:users,id',

            'book_ids'  => 'required|array|min:1',
            'book_ids.*'=> 'required|exists:books,id',
        ];
    }

    public function messages(): array
    {
        return [
            'book_ids.required' => 'Minimal pilih 1 buku',
            'book_ids.array'    => 'Format buku tidak valid',
            'book_ids.*.exists' => 'Buku tidak ditemukan',
        ];
    }
}
