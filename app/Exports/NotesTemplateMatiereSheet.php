<?php

namespace App\Exports;

use App\Models\Inscription;
use App\Models\Matiere;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class NotesTemplateMatiereSheet implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    WithColumnWidths
{
    protected $anneeId;
    protected $classeId;
    protected $matiere;

    public function __construct($anneeId, $classeId, Matiere $matiere)
    {
        $this->anneeId  = $anneeId;
        $this->classeId = $classeId;
        $this->matiere  = $matiere;
    }

    public function title(): string
    {
        return mb_strtoupper($this->matiere->nom);
    }

    public function collection()
    {
        return Inscription::with('eleve')
            ->where('annee_id', $this->anneeId)
            ->where('classe_id', $this->classeId)
            ->get();
    }

    public function headings(): array
    {
        return [
            'matricule',
            'nom',
            'prenom',
            'interrogation1',
            'interrogation2',
            'interrogation3',
            'devoir1',
            'devoir2',
            
        ];
    }

    public function map($inscription): array
    {
        return [
            $inscription->eleve->matricule,
            $inscription->eleve->nom,
            $inscription->eleve->prenom,
            '', '', '', '', '', ''
        ];
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
           
        ];
    }
}
