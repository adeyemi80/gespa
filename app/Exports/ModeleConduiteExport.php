<?php

namespace App\Exports;

use App\Models\Inscription;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Annee;
use App\Models\Trimestre;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ModeleConduiteExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithColumnWidths
{
    protected int $classe_id;
    protected int $annee_id;
    protected int $trimestre_id;

    public function __construct(int $classe_id, int $annee_id, int $trimestre_id)
    {
        $this->classe_id = $classe_id;
        $this->annee_id = $annee_id;
        $this->trimestre_id = $trimestre_id;
    }

    /**
     * Récupère les élèves de la classe et année sélectionnées.
     */
    public function collection()
    {
        return Inscription::with('eleve')
        ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
        ->where('inscriptions.classe_id', $this->classe_id)
            ->where('inscriptions.annee_id', $this->annee_id)
        ->orderBy('eleves.nom', 'asc')
        ->orderBy('eleves.prenom', 'asc')
        ->select('inscriptions.*')
        ->get();
    }

    /**
     * Titres des colonnes
     */
    public function headings(): array
    {
        return [
            'Matricule',
            'Nom',
            'Prénom',
            'Note de Conduite',
        ];
    }

    /**
     * Contenu de chaque ligne
     */
    public function map($inscription): array
    {
        return [
            $inscription->eleve->matricule ?? '',
            $inscription->eleve->nom ?? '',
            $inscription->eleve->prenom ?? '',
            '', // colonne vide pour la note
        ];
    }

    /**
     * Nom de la feuille Excel
     */
    public function title(): string
    {
        $classe = Classe::find($this->classe_id);
        $annee = Annee::find($this->annee_id);
        $trimestre = Trimestre::find($this->trimestre_id);

        return sprintf(
            'Cdte – %s – %s – %s',
            $classe?->nom ?? 'Classe inconnue',
            $trimestre?->nom ?? 'Trimestre inconnu',
            $annee?->nom ?? 'Année inconnue'
        );
    }

    /**
     * Largeur personnalisée des colonnes
     */
    public function columnWidths(): array
    {
        return [
            'A' => 20, // Matricule
            'B' => 25, // Nom
            'C' => 25, // Prénom
            'D' => 20, // Note de Conduite
        ];
    }
}
