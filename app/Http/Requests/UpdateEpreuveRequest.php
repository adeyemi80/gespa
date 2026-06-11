<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEpreuveRequest extends FormRequest
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
            'classe_id' => 'required|integer|max:250',
            'trimestre' => 'required|string|max:250',
            'matiere' => 'required|string|max:250',
            'nature' => 'required|string|max:250',
            'file' => 'required|file|max:2500',
        ];
    }
}
