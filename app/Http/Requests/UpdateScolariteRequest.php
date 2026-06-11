<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScolariteRequest extends FormRequest
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
            'inscription' => 'required|string|max:50',
            'classe' => 'required|string|max:50',
            'montant' => 'required|string|max:50',
            'mpaye' => 'required|string|max:50',
            'reste' => 'required|string|max:50',
            'inscription_id' => 'required|integer|max:250',
        ];
    }
}
