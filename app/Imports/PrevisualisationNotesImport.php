<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Eleve;

class PrevisualisationNotesImport implements ToCollection
{
    protected $matiere_id, $trimestre_id, $annee_id;
    protected $valides = [], $invalides = [];
     

    public function __construct($matiere_id, $trimestre_id, $annee_id, $sheetName)
    {
        $this->matiere_id = $matiere_id;
        $this->trimestre_id = $trimestre_id;
        $this->annee_id = $annee_id;
        $this->sheetName = $sheetName;
    }

    public function title(): string
    {
        return $this->sheetName;
    }


    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // ignorer l’en-tête

            $ligne = [
                'ligne'      => $index + 1,
                'matricule'  => $row[0] ?? '',
                'nom'        => $row[1] ?? '',
                'prenom'     => $row[2] ?? '',
                'moyenne_interro'    => $row[3] ?? null,
                'devoir1'    => $row[4] ?? null,
                'devoir2'    => $row[5] ?? null,
            ];

            // Vérification de l'élève
            $eleve = Eleve::where('matricule', $ligne['matricule'])
                          ->where('annee_id', $this->annee_id)
                          ->first();

            if (!$eleve) {
                $ligne['erreur'] = "Élève non trouvé avec le matricule '{$ligne['matricule']}'";
                $this->invalides[] = $ligne;
                continue;
            }

            // Vérification des champs numériques
            if (!is_numeric($ligne['moyenne_interro']) || !is_numeric($ligne['devoir1']) || !is_numeric($ligne['devoir2'])) {
                $ligne['erreur'] = "Les notes doivent être numériques.";
                $this->invalides[] = $ligne;
                continue;
            }

            // ✅ Ligne valide
            $ligne['eleve_id'] = $eleve->id;
            $this->valides[] = $ligne;
        }
    }

    public function getResultats()
    {
        return [
            'valides' => $this->valides,
            'invalides' => $this->invalides
        ];
    }

    public function getValidRows()
{
    return $this->valides;
}

public function getInvalidRows()
{
    return $this->invalides;
}

public function valides()
    {
        return $this->valides;
    }

    public function invalides()
    {
        return $this->invalides;
    }

}
