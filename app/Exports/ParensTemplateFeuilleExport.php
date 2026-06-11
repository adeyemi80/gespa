<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ParensTemplateFeuilleExport implements FromCollection, WithHeadings, WithTitle, WithColumnWidths
{
    protected $classe_id;
    protected $classe_nom;
    protected $inscriptions;

    /**
     * @param int $classe_id
     * @param string $classe_nom
     * @param \Illuminate\Support\Collection $inscriptions
     */
    public function __construct($classe_id, $classe_nom, $inscriptions)
    {
        $this->classe_id      = $classe_id;
        $this->classe_nom     = $classe_nom;
        $this->inscriptions   = $inscriptions;
    }

    /**
     * Données à exporter
     *
     * Chaque ligne : élève existant + colonnes parent vides
     */
    public function collection()
    {
        return $this->inscriptions->map(function ($inscription) {
            $eleve = $inscription->eleve;

            return [
                'matricule'        => $eleve->matricule,
                'nom'              => $eleve->nom,
                'prenom'           => $eleve->prenom,
                'nom_parent'       => '', // à remplir
                'prenom_parent'    => '',
                'telephone_parent' => '',
                'adresse_parent'   => '',
            ];
        });
    }

    /**
     * Entêtes des colonnes
     */
    public function headings(): array
    {
        return [
            'matricule',
            'nom',
            'prenom',
            'nom_parent',
            'prenom_parent',
            'telephone_parent',
            'adresse_parent',
        ];
    }

    /**
     * Nom de la feuille
     */
    public function title(): string
    {
        return $this->classe_nom;
    }

    /**
     * Largeur des colonnes
     */
    public function columnWidths(): array
    {
        return [
            'A' => 20, // Matricule
            'B' => 25, // Nom
            'C' => 25, // Prénom
            'D' => 25, // Nom parent
            'E' => 25, // Prénom parent
            'F' => 20, // Téléphone parent
            'G' => 40, // Adresse parent
        ];
    }
}



