<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifikasiPengembalianRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk data yang dikirim.
     */
    public function rules(): array
    {
        return [
            // Jika Anda mengirim data dalam bentuk satu objek (single verifikasi)
            'status'      => 'required|in:dikembalikan,terlambat,rusak,hilang',
            'denda'       => 'nullable|numeric|min:0',
            'jumlah_hari_telat' => 'nullable|integer|min:0',
            'jenis_denda' => 'nullable',
            'catatan'     => 'nullable|string',

            // Jika Anda mengirim data dalam bentuk array (batch verifikasi)
            'details'                => 'nullable|array',
            'details.*.id'           => 'required_with:details|exists:transaction_details,id',
            'details.*.status'       => 'required_with:details|in:dikembalikan,terlambat,rusak,hilang',
            'details.*.denda'        => 'nullable|numeric|min:0',
            'details.*.jumlah_hari_telat' => 'nullable|integer|min:0',
            'details.*.jenis_denda'  => 'nullable|in:telat,rusak,hilang',
            'details.*.catatan'      => 'nullable|string',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'status.in'      => 'Status harus salah satu dari: dikembalikan, terlambat, rusak, atau hilang.',
            'jenis_denda.in' => 'Jenis denda harus telat, rusak, atau hilang.',
            'jenis_denda.required_if' => 'Pilih jenis denda terlebih dahulu.',
        ];
    }
}