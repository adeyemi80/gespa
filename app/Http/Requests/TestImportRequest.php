<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestImportRequest extends FormRequest
{
    public function authorize()
    {
        // Autorise l'accès — adapte selon ton système d'auth
        return auth()->check();
    }

    public function rules()
    {
        return [
            'annee_id'     => ['required', 'integer', 'exists:annees,id'],
            'trimestre_id' => ['required', 'integer', 'exists:trimestres,id'],
            'date'         => ['required', 'date'],
            'titre'        => ['required', 'string', 'max:255'],
            'type'         => ['required', 'string', 'max:100'],
            'matiere_id'   => ['required', 'integer', 'exists:matieres,id'],
            'description'  => ['nullable', 'string'],
            'fichiers'     => ['required', 'array', 'min:1'],
            'fichiers.*'   => ['file', 'mimes:pdf,doc,docx', 'max:20480'], // max 20MB par fichier (ajuste si besoin)
        ];
    }

    public function messages()
    {
        return [
            'fichiers.*.mimes' => 'Chaque fichier doit être au format PDF, DOC ou DOCX.',
            'fichiers.*.max'   => 'Chaque fichier ne doit pas dépasser 20 MB.',
        ];
    }
}
