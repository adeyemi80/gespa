<?php

namespace App\Exports;

use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Eleve;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NotesTemplateMultiFeuillesExport implements WithMultipleSheets
{
    protected $classe_id;
    protected $trimestre_id;

    public function __construct($classe_id, $trimestre_id)
    {
        $this->classe_id = $classe_id;
        $this->trimestre_id = $trimestre_id;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Charger la classe avec ses matières
        $classe = Classe::with('matieres')->findOrFail($this->classe_id);

        foreach ($classe->matieres as $matiere) {
            // Chaque matière génère une feuille
            $sheets[] = new NotesTemplateFeuilleExport(
                $this->classe_id,
                $matiere->id,
                $matiere->nom
            );
        }

        return $sheets;
    }
}
