<?php
namespace App\Exports;
use App\Models\Classe;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NotesTemplateMultiFeuillesExport implements WithMultipleSheets
{
    protected $classe_id;
    protected $trimestre_id;
    protected $annee_id; // ✅ ajouté

    public function __construct($classe_id, $trimestre_id, $annee_id) // ✅ ajouté
    {
        $this->classe_id   = $classe_id;
        $this->trimestre_id = $trimestre_id;
        $this->annee_id    = $annee_id; // ✅ ajouté
    }

    public function sheets(): array
    {
        $sheets = [];
        $classe = Classe::with('matieres')->findOrFail($this->classe_id);

        foreach ($classe->matieres as $matiere) {
            $sheets[] = new NotesTemplateFeuilleExport(
                $this->classe_id,
                $matiere->id,
                $matiere->nom,
                $this->annee_id // ✅ passé à chaque feuille
            );
        }
        return $sheets;
    }
}
