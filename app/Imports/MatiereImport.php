<?php
// app/Imports/MatiereImport.php

namespace App\Imports;

use App\Models\Matiere;
use App\Models\Classe;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class MatiereImport implements ToCollection, WithStartRow, WithHeadingRow, 
    SkipsOnFailure, WithValidation
{
    use SkipsFailures;

    protected $anneeId;
    protected $isPreview;
    protected $classeName;

    public function __construct($anneeId, $isPreview = false)
    {
        $this->anneeId = $anneeId;
        $this->isPreview = $isPreview;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'coefficient' => ['required', 'numeric', 'min:1', 'max:10'],
            'type' => ['required', Rule::in(['scientifique', 'litteraire', 'autres'])],
            'enseignant_id' => ['nullable', 'exists:enseignants,id']
        ];
    }

    public function collection(Collection $rows)
    {
        $data = [];
        $results = ['success' => 0, 'errors' => 0];

        foreach ($rows as $row) {
            $this->classeName = $this->getCurrentSheetName();
            $classe = Classe::where('nom', $this->classeName)
                ->whereHas('annee', fn($q) => $q->where('id', $this->anneeId))
                ->first();

            if (!$classe) continue;

            $matiereData = [
                'nom' => $row['nom'],
                'niveau' => $classe->niveau,
                'coefficient' => $row['coefficient'],
                'type' => $row['type'],
                'enseignant_id' => $row['enseignant_id'] ?? null,
                'classe_id' => $classe->id
            ];

            $exists = Matiere::where('nom', $matiereData['nom'])
                ->where('niveau', $matiereData['niveau'])
                ->exists();

            if (!$exists) {
                $data[] = $matiereData;
                $results['success']++;
            } else {
                $results['errors']++;
            }
        }

        if ($this->isPreview) {
            session(['import_preview_data' => $data]);
        } else {
            foreach ($data as $matiereData) {
                Matiere::create($matiereData);
            }
            session(['import_results' => $results]);
        }
    }

    private function getCurrentSheetName()
    {
        return request()->input('sheet_name', 'Classe par défaut');
    }
}
