<?php

namespace App\Imports;

use App\Models\Enseignant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class EnseignantImport implements ToCollection
{
    public $valides = [];
    public $erreurs = [];
    protected $insertion = true;

    public function __construct($insertion = true)
    {
        $this->insertion = $insertion;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // ignorer l’en-tête

            $data = [
                'matricule'      => trim($row[0] ?? ''),
                'nom'            => trim($row[1] ?? ''),
                'prenom'         => trim($row[2] ?? ''),
                'date_naissance' => is_numeric($row[3] ?? '') 
                    ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])->format('Y-m-d')
                    : trim($row[3] ?? ''),
                'sexe'           => trim($row[4] ?? ''),
                'adresse'        => trim($row[5] ?? ''),
                'telephone'      => trim($row[6] ?? ''),
                'email'          => trim($row[7] ?? ''),
            ];

            $validator = Validator::make($data, [
                'matricule'      => 'required|unique:enseignants,matricule',
                'nom'            => 'required|string',
                'prenom'         => 'required|string',
                'date_naissance' => 'required|date',
                'sexe'           => 'required|in:M,F',
                'adresse'        => 'nullable|string',
                'telephone'      => 'nullable|string',
                'email'          => 'required|email|unique:enseignants,email',
            ]);

            if ($validator->fails()) {
                $this->erreurs[] = [
                    'ligne' => $index + 1,
                    'data' => $data,
                    'erreurs' => $validator->errors()->all(),
                ];
                continue;
            }

           // Vérification des doublons nom + prénom + téléphone
            $exists = Enseignant::where('nom', $data['nom'])
                ->where('prenom', $data['prenom'])
                ->where(function ($query) use ($data) {
                    if (!empty($data['telephone'])) {
                        $query->where('telephone', $data['telephone']);
                    } else {
                        $query->whereNull('telephone');
                    }
                })
                ->exists();

            if ($exists) {
                $this->erreurs[] = [
                    'ligne' => $index + 1,
                    'data' => $data,
                    'erreurs' => [
                        "L'Enseignant {$data['nom']} {$data['prenom']} avec le téléphone " . ($data['telephone'] ?: 'N/A') . " a déjà été importé.",
                    ],
                ];
                continue;
            }

            $this->valides[] = $data;

            if ($this->insertion) {
                Enseignant::create($data);
            }
        }
    }
}
