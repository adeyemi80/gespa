<?php

namespace App\Imports;

use App\Models\Eleve;
use App\Models\Note;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class NotesSheetImport implements ToCollection
{
    protected $annee_id;
    protected $trimestre_id;
    protected $matiere_id;
    protected $sheetName;
    public $errors = []; // Pour stocker les erreurs

    /**
     * Constructeur
     *
     * @param int $annee_id
     * @param int $trimestre_id
     * @param int $matiere_id
     * @param string|null $sheetName
     */
    public function __construct($annee_id, $trimestre_id, $matiere_id, $sheetName = null)
    {
        $this->annee_id = $annee_id;
        $this->trimestre_id = $trimestre_id;
        $this->matiere_id = $matiere_id;
        $this->sheetName = $sheetName;
    }

    /**
     * Traiter les lignes du fichier Excel
     *
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            // Ignorer la ligne d'en-tête
            if ($index === 0) continue;

            $matricule = trim($row[0] ?? null);
            $moyenne_interro = $row[3] ?? null;
            $devoir1 = $row[4] ?? null;
            $devoir2 = $row[5] ?? null;

            // Vérification des champs obligatoires
            if (!$matricule) {
                $this->errors[] = "Ligne ".($index+1).": matricule manquant.";
                continue;
            }

            // Recherche de l'élève
            $eleve = Eleve::where('matricule', $matricule)->first();
            if (!$eleve) {
                $this->errors[] = "Ligne ".($index+1).": élève avec matricule '$matricule' introuvable.";
                continue;
            }

            // Vérification des notes (doivent être numériques)
            $moyenne_interro = is_numeric($moyenne_interro) ? $moyenne_interro : null;
            $devoir1 = is_numeric($devoir1) ? $devoir1 : null;
            $devoir2 = is_numeric($devoir2) ? $devoir2 : null;

            // Si toutes les notes sont null, signaler erreur
            if (is_null($moyenne_interro) && is_null($devoir1) && is_null($devoir2)) {
                $this->errors[] = "Ligne ".($index+1).": aucune note valide pour l'élève '$matricule'.";
                continue;
            }

            // Insertion ou mise à jour de la note
            Note::updateOrCreate([
                'eleve_id' => $eleve->id,
                'matiere_id' => $this->matiere_id,
                'annee_id' => $this->annee_id,
                'trimestre_id' => $this->trimestre_id,
            ], [
                'moyenne_interro' => $moyenne_interro,
                'devoir1' => $devoir1,
                'devoir2' => $devoir2,
            ]);
        }
    }
}
