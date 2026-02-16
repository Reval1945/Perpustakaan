<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function rules()
    {
        return [
            'kode_buku'   => 'required|string|unique:books,kode_buku,' . $this->book,
            'judul'       => 'required|string',
            'sinopsis'    => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'penulis'     => 'required|string',
            'penerbit'    => 'required|string',
            'tahun'       => 'required|digits:4',
            'stok'        => 'required|integer|min:0',
            'rak'         => 'required|string',
            'nomor_rak'   => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'judul.required' => 'Judul buku wajib diisi',
            'sinopsis.required' => 'Sinopsis wajib diisi',
            'stok.min'       => 'Stok tidak boleh negatif',
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
