<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ElevesImportTemplateExport implements FromArray, WithHeadings, WithColumnWidths
{
    public function array(): array
    {
        return [
            // Exemple de ligne vide ou avec valeurs fictives
            [
                'DURAND', 'Jean', '2009-05-21', 'M', 'Paris', 'Française', 'PASSANT', 'ID_PARENT'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'nom',
            'prenoms',
            'date_naissance',
            'sexe',
            'lieu_naissance',
            'nationalite',
            'statut',
            'numeducmaster',
            'paren_id'
        ];
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
            
        ];
    }
}
