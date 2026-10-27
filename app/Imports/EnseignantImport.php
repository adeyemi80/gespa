<?php

namespace App\Imports;

use App\Models\Enseignant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class EnseignantImport implements ToCollection
{
    /**
     * Données valides.
     */
    public $valides = [];

    /**
     * Erreurs détectées pendant l'import.
     */
    public $erreurs = [];

    /**
     * Indique si les données doivent être réellement insérées.
     */
    protected $insertion = true;

    public function __construct($insertion = true)
    {
        $this->insertion = $insertion;
    }

    /**
     * Traite les lignes du fichier Excel/CSV.
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            /*
            |--------------------------------------------------------------------------
            | 1. Ignorer l'en-tête
            |--------------------------------------------------------------------------
            */
            if ($index === 0) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | 2. Convertir la ligne en tableau
            |--------------------------------------------------------------------------
            */
            $row = $row->toArray();

            /*
            |--------------------------------------------------------------------------
            | 3. Ignorer complètement les lignes vides
            |--------------------------------------------------------------------------
            */
            $ligneVide = true;

            foreach ($row as $cellule) {
                if (trim((string) $cellule) !== '') {
                    $ligneVide = false;
                    break;
                }
            }

            if ($ligneVide) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | 4. Préparation de la date de naissance
            |--------------------------------------------------------------------------
            */
            $dateNaissance = $row[3] ?? null;

            if ($dateNaissance !== null && trim((string) $dateNaissance) !== '') {

                try {

                    if (is_numeric($dateNaissance)) {

                        $dateNaissance = ExcelDate::excelToDateTimeObject(
                            $dateNaissance
                        )->format('Y-m-d');

                    } else {

                        $dateNaissance = trim((string) $dateNaissance);
                    }

                } catch (\Throwable $e) {

                    $dateNaissance = trim((string) $dateNaissance);
                }

            } else {

                $dateNaissance = null;
            }

            /*
            |--------------------------------------------------------------------------
            | 5. Préparation de l'email
            |--------------------------------------------------------------------------
            |
            | Une cellule Excel vide devient NULL.
            |
            | C'est important car la colonne email est maintenant nullable
            | dans PostgreSQL.
            |--------------------------------------------------------------------------
            */
            $email = trim((string) ($row[7] ?? ''));

            $email = $email !== ''
                ? $email
                : null;

            /*
            |--------------------------------------------------------------------------
            | 6. Préparation des données
            |--------------------------------------------------------------------------
            */
            $data = [

                'matricule' => trim((string) ($row[0] ?? '')),

                'nom' => trim((string) ($row[1] ?? '')),

                'prenom' => trim((string) ($row[2] ?? '')),

                'date_naissance' => $dateNaissance,

                'sexe' => strtoupper(
                    trim((string) ($row[4] ?? ''))
                ),

                'adresse' => trim((string) ($row[5] ?? '')) ?: null,

                'telephone' => trim((string) ($row[6] ?? '')) ?: null,

                'email' => $email,
            ];

            /*
            |--------------------------------------------------------------------------
            | 7. Validation
            |--------------------------------------------------------------------------
            |
            | L'email n'est PLUS obligatoire.
            |
            | nullable = accepte NULL / vide
            | email   = vérifie le format uniquement s'il existe
            |--------------------------------------------------------------------------
            */
            $validator = Validator::make(
                $data,
                [

                    'matricule' => [
                        'required',
                        'string',
                        'unique:enseignants,matricule',
                    ],

                    'nom' => [
                        'required',
                        'string',
                    ],

                    'prenom' => [
                        'required',
                        'string',
                    ],

                    'date_naissance' => [
                        'required',
                        'date',
                    ],

                    'sexe' => [
                        'required',
                        'in:M,F',
                    ],

                    'adresse' => [
                        'nullable',
                        'string',
                    ],

                    'telephone' => [
                        'nullable',
                        'string',
                    ],

                    'email' => [
                        'nullable',
                        'email',
                        'unique:enseignants,email',
                    ],
                ],
                [

                    'matricule.required' =>
                        'Le matricule est obligatoire.',

                    'matricule.unique' =>
                        'Ce matricule existe déjà.',

                    'nom.required' =>
                        'Le nom est obligatoire.',

                    'prenom.required' =>
                        'Le prénom est obligatoire.',

                    'date_naissance.required' =>
                        'La date de naissance est obligatoire.',

                    'date_naissance.date' =>
                        'La date de naissance est invalide.',

                    'sexe.required' =>
                        'Le sexe est obligatoire.',

                    'sexe.in' =>
                        'Le sexe doit être M ou F.',

                    'email.email' =>
                        'L’adresse email est invalide.',

                    'email.unique' =>
                        'Cette adresse email existe déjà.',
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 8. En cas d'erreur de validation
            |--------------------------------------------------------------------------
            */
            if ($validator->fails()) {

                $this->erreurs[] = [

                    'ligne' => $index + 1,

                    'data' => $data,

                    'erreurs' => $validator->errors()->all(),
                ];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | 9. Vérification d'un doublon
            |--------------------------------------------------------------------------
            |
            | On recherche un enseignant ayant :
            | - le même nom
            | - le même prénom
            | - le même téléphone
            |
            | Si aucun téléphone n'est fourni, on recherche telephone = NULL.
            |--------------------------------------------------------------------------
            */
            $exists = Enseignant::where(
                    'nom',
                    $data['nom']
                )
                ->where(
                    'prenom',
                    $data['prenom']
                )
                ->where(function ($query) use ($data) {

                    if (!empty($data['telephone'])) {

                        $query->where(
                            'telephone',
                            $data['telephone']
                        );

                    } else {

                        $query->whereNull('telephone');
                    }
                })
                ->exists();

            /*
            |--------------------------------------------------------------------------
            | 10. Doublon trouvé
            |--------------------------------------------------------------------------
            */
            if ($exists) {

                $telephone = $data['telephone'] ?: 'N/A';

                $this->erreurs[] = [

                    'ligne' => $index + 1,

                    'data' => $data,

                    'erreurs' => [
                        "L'enseignant {$data['nom']} {$data['prenom']} avec le téléphone {$telephone} existe déjà."
                    ],
                ];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | 11. Ajouter aux données valides
            |--------------------------------------------------------------------------
            */
            $this->valides[] = $data;

            /*
            |--------------------------------------------------------------------------
            | 12. Insertion en base
            |--------------------------------------------------------------------------
            */
            if ($this->insertion) {

                Enseignant::create($data);
            }
        }
    }
}