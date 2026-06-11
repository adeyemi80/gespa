<?php

namespace App\Imports;

use App\Models\Matiere;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class MatieresPreviewPerSheet implements ToCollection
{
    protected $preview;
    protected $classe;

    public function __construct($preview, $classe)
    {
        $this->preview = $preview;
        $this->classe = $classe;
    }

    public function collection(Collection $rows)
    {
        $sheetName = $this->classe->nom;

        // Skip header
        $header = $rows->shift();

        foreach ($rows as $index => $row) {

            $record = [
                'row' => $index + 2,
                'nom' => $row[0] ?? null,
                'coefficient' => $row[1] ?? null,
                'type' => $row[2] ?? null,
                ///'classe' => $this->classe->id,
                'enseignant_id' => $row[3] ?? null,
                'errors' => [],
                'valid' => true,
            ];

            // VALIDATION
            $validator = Validator::make($record, [
                'nom' => 'required|string',
                'coefficient' => 'required|integer|min:1',
                'type' => 'required|in:scientifique,litteraire',
                'enseignant_id' => 'nullable|exists:enseignants,id',
            ]);

            if ($validator->fails()) {
                $record['errors'] = $validator->errors()->all();
                $record['valid'] = false;
            }

            // Vérifier doublon en BD
            if (Matiere::where('nom', $record['nom'])
                ->where('classe_id', $record['classe_id'])
                ->exists()) {
                $record['errors'][] = "Doublon: matière déjà existante.";
                $record['valid'] = false;
            }

            // On stocke pour la prévisualisation
            $this->preview->data[$sheetName][] = $record;
        }
    }
}
