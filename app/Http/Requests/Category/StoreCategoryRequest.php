<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255||unique:categories,name',
            'deskripsi' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi',
            'name.max'      => 'Nama kategori terlalu panjang',
            'name.unique'   => 'Kategori sudah terdaftar'
        ];
    }
}
