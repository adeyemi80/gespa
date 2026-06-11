<?php

namespace App\Exports;

use App\Models\Eleve;
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

    public function __construct($classe_id, $matiere_id, $matiere_nom)
    {
        $this->classe_id = $classe_id;
        $this->matiere_id = $matiere_id;
        $this->matiere_nom = $matiere_nom;
    }

    /**
     * Données à exporter (liste des élèves).
     */
  public function collection()
{
    return Inscription::with('eleve')
        ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
        ->where('inscriptions.classe_id', $this->classe_id)
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
    /**
     * Entêtes des colonnes.
     */
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

    /**
     * Nom de la feuille (matière).
     */
    public function title(): string
    {
        return $this->matiere_nom;
    }

     public function columnWidths(): array
    {
        return [
            'A' => 20, // Colonne "Numéro" (large)
            'B' => 25, // Colonne "Nom"
            'C' => 25, // Colonne "Prénom"
            'D' => 20, // Colonne "moyenne interrogation"
            'E' => 20,
            'F' => 20,
        ];
    }
}
