<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul'       => 'required|string|max:255',
            'sinopsis'    => 'required|string|max:255',
            'kode_buku'   => [
                'required',
                'string',
                Rule::unique('books', 'kode_buku')
                    ->whereNull('deleted_at'),
            ],
            'category_id' => 'required|exists:categories,id',
            'penulis'     => 'required|string|max:255',
            'penerbit'    => 'required|string|max:255',
            'tahun'       => 'required|digits:4',
            'rak'         => 'required|string|max:255',
            'nomor_rak'   => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul buku wajib diisi',
            'sinopsis.required' => 'Sinopsis buku wajib diisi',
            'rak.required'   => 'Rak wajib diisi',
            'nomor_rak.required' => 'Nomor rak wajib diisi',
            'category_id.required' => 'Kategori wajib diisi',
            'category_id.exists' => 'Kategori tidak ditemukan',
            'kode_buku.unique' => 'Kode buku sudah terdaftar',
            'kode_buku.required' => 'Kode buku wajib diisi',
            'tahun.digits' => 'Tahun terbit harus berupa 4 digit',
            'tahun.required' => 'Tahun terbit wajib diisi',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpg, jpeg, atau png',
            'image.max'   => 'Ukuran gambar maksimal 2MB',
        ];
    }
}
