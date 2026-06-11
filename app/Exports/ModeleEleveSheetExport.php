<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class ModeleEleveSheetExport implements FromCollection, WithHeadings, WithTitle,  WithColumnWidths

{
    protected $classe;

    public function __construct($classe)
    {
        $this->classe = $classe;
    }

    public function collection()
    {
        // 20 lignes vides pour saisie
        return collect(array_fill(0, 20, [
            '', '', '', '', '', '', '', '', '', ''
        ]));
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Prénom',
            'Date de Naissance',
            'Sexe',
            'Nationalite',
            'Lieu de Naissance',
            'statut',
            'nom_pere',
            'prenom_pere',
            'telephone_pere',
            'nom_mere',
            'prenom_mere',
            'telephone_mere',
        ];
    }

    public function title(): string
    {
        // Nom de la feuille = nom de la classe
        return $this->classe->nom ?? 'Classe';
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
            'G' => 20,
            'H' => 20,
             'I' => 20,
              'J' => 20,
            
        ];
    }
}