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
            'user_id'           => ['required', 'exists:users,id'],
            'book_ids'          => ['required', 'array', 'min:1'],
            'book_ids.*'        => ['required', 'exists:books,id'],
            'book_stock_ids'    => ['nullable', 'array'],
            'book_stock_ids.*'  => ['nullable', 'exists:book_stocks,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_ids.required'         => 'Buku harus dipilih',
            'book_ids.array'            => 'Format buku tidak valid',
            'book_ids.*.exists'         => 'Buku tidak ditemukan',
            'book_stock_ids.array'      => 'Format eksemplar tidak valid',
            'book_stock_ids.*.exists'   => 'Eksemplar buku tidak ditemukan',
        ];
    }
}
