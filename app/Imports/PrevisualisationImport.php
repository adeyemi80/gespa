<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PrevisualisationImport implements ToCollection, WithHeadingRow
{
    public $validatedRows = [];
    public $errors = [];
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rules = $this->rulesParType($this->type);

            $validator = Validator::make($row->toArray(), $rules);

            if ($validator->fails()) {
                $this->errors[] = [
                    'ligne' => $index + 2, // ligne Excel (1-based + heading)
                    'messages' => $validator->errors()->all(),
                ];
            } else {
                $this->validatedRows[] = $row->toArray();
            }
        }
    }

    protected function rulesParType($type)
    {
        return match($type) {
            'eleves' => [
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'date_naissance' => 'nullable|date',
                'sexe' => 'required|in:M,F',
                'adresse' => 'nullable|string',
                'telephone' => 'nullable|string',
                'email' => 'required|email',
                'paren_id' => 'nullable|numeric|exists:parens,id',
                'lieu_naissance' => 'required|string',
                'matricule' => 'required|string',
            ],

            'parents' => [
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'telephone' => 'required|string',
                'email' => 'required|email',
                'profession' => 'nullable|string',
                'adresse' => 'nullable|string',
            ],

            'notes' => [
                'eleve_id' => 'required|exists:eleves,id',
                'matiere_id' => 'required|exists:matieres,id',
                'note' => 'required|numeric|min:0|max:20',
                'periode' => 'required|string',
            ],

            default => []
        };
    }
}
