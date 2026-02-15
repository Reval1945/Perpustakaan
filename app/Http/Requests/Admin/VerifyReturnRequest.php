<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class VerifyReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(['dikembalikan','terlambat','rusak','hilang']),
            ],

            'jenis_denda' => [
                'nullable',
                Rule::in(['uang','buku']),
            ],

            'denda' => 'nullable|numeric|min:0',

            'catatan' => 'nullable|string|max:255',
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Kalau status BUKAN dikembalikan normal,
            // maka jenis_denda WAJIB
            if (in_array($this->status, ['terlambat', 'rusak', 'hilang'])
                && !$this->jenis_denda
            ) {
                $validator->errors()->add(
                    'jenis_denda',
                    'Jenis denda wajib diisi untuk status ini.'
                );
            }
        });
    }
}

