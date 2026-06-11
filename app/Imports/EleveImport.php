<?php

namespace App\Imports;

use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class EleveImport implements ToCollection, WithMultipleSheets
{
    public $erreurs = [];
    public $valides = [];

    protected $classe_id;
    protected $annee_id;
    protected $insertion;
    protected $nomClasse;
    protected $dernierMatriculeDb;  // ✅ Le VRAI dernier matricule DB

    public function __construct($classe_id, $annee_id, $insertion = true)
    {
        $this->classe_id  = $classe_id;
        $this->annee_id   = $annee_id;
        $this->insertion  = $insertion;
        $this->nomClasse  = Classe::find($classe_id)->nom ?? '-';
        
        // ✅ Récupère le DERNIER matricule existant (le plus grand numéro)
        $dernierMatricule = DB::table('eleves')
            ->where('matricule', 'LIKE', '23122025%')
            ->orderBy('matricule', 'desc')
            ->value('matricule');
            
        if ($dernierMatricule) {
            // Extrait le numéro (ex: 231220250009 → 9)
            $this->dernierMatriculeDb = (int) substr($dernierMatricule, 8);
        } else {
            $this->dernierMatriculeDb = 0;
        }
    }

    public function sheets(): array
    {
        return [
            $this->nomClasse => $this,
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            // 1. DATE NAISSANCE
            $dateNaissanceRaw = trim($row[2] ?? '');
            try {
                if (is_numeric($dateNaissanceRaw)) {
                    $date_naissance = ExcelDate::excelToDateTimeObject($dateNaissanceRaw)->format('Y-m-d');
                } elseif (!empty($dateNaissanceRaw)) {
                    $date_naissance = Carbon::createFromFormat('d/m/Y', $dateNaissanceRaw)->format('Y-m-d');
                } else {
                    throw new \Exception('Date vide');
                }
            } catch (\Exception $e) {
                $this->erreurs[] = [
                    'ligne'   => $index + 1,
                    'data'    => $row,
                    'erreurs' => ['Date naissance invalide'],
                ];
                continue;
            }

            $data = [
                'nom'            => trim($row[0] ?? ''),
                'prenom'         => trim($row[1] ?? ''),
                'date_naissance' => $date_naissance,
                'sexe'           => trim($row[3] ?? ''),
                'nationalite'    => trim($row[4] ?? ''),
                'lieu_naissance' => trim($row[5] ?? ''),
                'statut'         => strtolower(trim($row[6] ?? 'passant')),
                'classe'         => $this->nomClasse,
                'annee'          => $this->annee_id,
            ];

            // ✅ 2. MATRICULE À PARTIR DU VRAI DERNIER
            $numero = $this->dernierMatriculeDb + 1;
            $matricule = '23122025' . str_pad($numero, 4, '0', STR_PAD_LEFT);
            $data['matricule'] = $matricule;
            $this->dernierMatriculeDb++; // Incrémente pour la ligne suivante

            // 3. VALIDATION
            $rules = [
                'nom'            => 'required|string|max:100',
                'prenom'         => 'required|string|max:100',
                'date_naissance' => 'required|date',
                'sexe'           => 'required|in:M,F',
                'lieu_naissance' => 'required|string|max:100',
                'nationalite'    => 'required|string|max:50',
                'statut'         => 'required|in:passant,redoublant',
                'matricule'      => 'required|string|size:12',
            ];

            if ($this->insertion) {
                $rules['matricule'] .= '|unique:eleves,matricule';
            }

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $this->erreurs[] = [
                    'ligne'   => $index + 1,
                    'data'    => $data,
                    'erreurs' => $validator->errors()->all(),
                ];
                continue;
            }

            // 4. DOUBLON
            $doublon = Eleve::where('nom', $data['nom'])
                ->where('prenom', $data['prenom'])
                ->where('date_naissance', $data['date_naissance'])
                ->where('classe_id', $this->classe_id)
                ->where('annee_id', $this->annee_id)
                ->exists();

            if ($doublon) {
                $this->erreurs[] = [
                    'ligne'   => $index + 1,
                    'data'    => $data,
                    'erreurs' => ["Doublon: {$data['nom']} {$data['prenom']}"],
                ];
                continue;
            }

            $this->valides[] = $data;

            // 5. INSERTION
            if ($this->insertion) {
                try {
                    $eleve = Eleve::create([
                        'matricule'      => $data['matricule'],
                        'nom'            => $data['nom'],
                        'prenom'         => $data['prenom'],
                        'date_naissance' => $data['date_naissance'],
                        'sexe'           => $data['sexe'],
                        'nationalite'    => $data['nationalite'],
                        'lieu_naissance' => $data['lieu_naissance'],
                        'statut'         => $data['statut'],
                        'classe_id'      => $this->classe_id,
                        'annee_id'       => $this->annee_id,
                    ]);

                    $eleve->inscriptions()->create([
                        'classe_id' => $this->classe_id,
                        'annee_id'  => $this->annee_id,
                    ]);
                } catch (\Exception $e) {
                    $this->erreurs[] = [
                        'ligne'   => $index + 1,
                        'data'    => $data,
                        'erreurs' => ["Erreur DB: " . $e->getMessage()],
                    ];
                }
            }
        }
    }


    /**
     * Génération matricule INCRÉMENTÉ (compteur local + check DB)
     */
    private function genererMatricule()
    {
        $this->compteurMatricule++;  // Incrémente localement (rapide)
        
        // Relecture DB toutes les 50 lignes (sécurité)
        if ($this->compteurMatricule % 50 === 0) {
            $maxReel = (int) DB::table('eleves')
                ->where('matricule', 'LIKE', '23122025%')
                ->max('numero_ordre') ?? 0;
            if ($maxReel >= $this->compteurMatricule) {
                $this->compteurMatricule = $maxReel + 1;
            }
        }
        
        return '23122025' . str_pad($this->compteurMatricule, 4, '0', STR_PAD_LEFT);
    }
}
