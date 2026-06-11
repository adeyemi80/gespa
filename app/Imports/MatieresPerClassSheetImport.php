<?php

namespace App\Imports;

use App\Models\Matiere;
use App\Models\Classe;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class MatieresPerClassSheetImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation, 
    SkipsOnFailure
{
    use SkipsFailures;

    protected $classe;

    public function __construct($classe)
    {
        $this->classe = $classe;
    }

    public function model(array $row)
    {
        return Matiere::create([
            'nom' => $row['nom'],
            'coefficient' => $row['coefficient'],
            'type' => $row['type'],
            //'classe_id' => $this->classe->id,
            'enseignant_id' => $row['enseignant_id'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nom' => [
                'required',
                Rule::unique('matieres', 'nom')->where('classe_id', $this->classe->id),
            ],
            '*.coefficient' => 'required|integer|min:1',
            '*.type' => 'required|in:scientifique,litteraire',
            '*.enseignant_id' => 'nullable|exists:enseignants,id',
        ];
    }
}

