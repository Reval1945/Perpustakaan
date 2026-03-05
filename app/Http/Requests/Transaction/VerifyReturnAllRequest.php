<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class VerifyReturnAllRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status'      => 'required|in:dikembalikan,terlambat,rusak,hilang',
            'jenis_denda' => 'nullable|in:telat,rusak,hilang',
            'jumlah_hari_telat' => 'nullable|integer|min:0',
            'denda'       => 'nullable|numeric|min:0',
            'catatan'     => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_denda.in' => 'Pilih jenis denda yang benar (telat/rusak/hilang)'
        ];
    }
}
