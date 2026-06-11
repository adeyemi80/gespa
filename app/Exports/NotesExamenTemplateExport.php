<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use App\Models\ExamenBlanc;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
  use Carbon\Carbon;

class NotesExamenTemplateExport implements FromArray, WithColumnWidths,  WithTitle
{
    protected $examen;

    public function __construct(ExamenBlanc $examen)
    {
        $this->examen = $examen;
    }

public function array(): array
{
    $rows = [];

    // 🔥 Charger toutes les relations nécessaires
    $this->examen->load([
        'classes.matieres',
        'participants.inscription.eleve',
        'participants.notes'
    ]);

    $participants = $this->examen->participants;

    // 🔥 Récupérer les matières via les classes de l'examen
   $matieres = $this->examen->classes
    ->flatMap(fn($classe) => $classe->matieres)
    ->unique('id')
    ->sortBy('nom')
    ->values();

    // 🧪 Sécurité
    if ($matieres->isEmpty()) {
        return [
            ['numero_table', 'nom', 'prenom', 'Aucune matière trouvée']
        ];
    }

    // 🔹 HEADER
    $header = ['numero_table', 'nom', 'prenom'];

    foreach ($matieres as $matiere) {
        $header[] = $matiere->nom;
    }

    $rows[] = $header;

    // 🔹 LIGNES
    foreach ($participants as $p) {

        $row = [
            $p->numero_table,
            optional($p->inscription->eleve)->nom,
            optional($p->inscription->eleve)->prenom,
        ];

        // 🔥 Indexation rapide des notes
        $notesByMatiere = $p->notes->keyBy('matiere_id');

        foreach ($matieres as $matiere) {
            $note = $notesByMatiere->get($matiere->id);
            $row[] = $note ? $note->note : '';
        }

        $rows[] = $row;
    }

    return $rows;
}
 /**
     * Définit le nom de la feuille Excel
     */

public function title(): string
{
    return 'Notes EB ' . $this->examen->type . ' - ' .
        Carbon::now()->locale('fr')->translatedFormat('F Y');
}

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 20,
            'C' => 20,
            'D' => 18,
            'E' => 18,
            'F' => 18,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 15,
           
        ];
    }
    
}