<?php
namespace App\Exports;
use App\Models\Inscription;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class NotesTemplateFeuilleExport implements FromCollection, WithHeadings, WithTitle, WithColumnWidths
{
    protected $classe_id;
    protected $matiere_id;
    protected $matiere_nom;
    protected $annee_id; // ✅ ajouté

    public function __construct($classe_id, $matiere_id, $matiere_nom, $annee_id) // ✅ ajouté
    {
        $this->classe_id   = $classe_id;
        $this->matiere_id  = $matiere_id;
        $this->matiere_nom = $matiere_nom;
        $this->annee_id    = $annee_id; // ✅ ajouté
    }

    public function collection()
    {
        return Inscription::with('eleve')
            ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
            ->where('inscriptions.classe_id', $this->classe_id)
            ->where('inscriptions.annee_id', $this->annee_id) // ✅ ajouté
            ->orderBy('eleves.nom', 'asc')
            ->orderBy('eleves.prenom', 'asc')
            ->select('inscriptions.*')
            ->get()
            ->map(function ($inscription) {
                return [
                    'Matricule' => $inscription->eleve->matricule,
                    'Nom'       => $inscription->eleve->nom,
                    'Prénom'    => $inscription->eleve->prenom,
                    'Interro'   => '',
                    'Devoir1'   => '',
                    'Devoir2'   => '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Nom',
            'Prénom',
            'Moyenne Interrogation',
            'Devoir1',
            'Devoir2',
        ];
    }

    public function title(): string
    {
        return $this->matiere_nom;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 25,
            'C' => 25,
            'D' => 20,
            'E' => 20,
            'F' => 20,
        ];
    }
}
