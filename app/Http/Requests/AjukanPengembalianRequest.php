<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AjukanPengembalianRequest extends FormRequest
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
            'detail_ids' => 'required|array',
            'detail_ids.*' => 'exists:transaction_details,id',
        ];
    }
}
