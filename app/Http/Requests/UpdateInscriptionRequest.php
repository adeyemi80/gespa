<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInscriptionRequest extends FormRequest
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
            'nom' => 'required|string|max:250',
            'sexe' => 'required|string|max:250',
            'date' => 'required|date|max:25',
            'lieu' => 'required|string|max:250',
            'nationalite' => 'required|string|max:20',
            'telephone' => 'required|string|max:30',
            'frais' => 'required|string|max:50',
            'classe_id' => 'required|integer|max:250',
            
        ];
    }
}
