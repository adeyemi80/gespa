<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;


class ElevesTemplateFeuilleExport implements 
    FromCollection, WithHeadings, WithTitle, WithColumnWidths
{
    protected $classe;

    public function __construct($classe)
    {
        $this->classe = $classe;
    }

    public function collection()
    {
        return collect([
            ['DAGOUDO', 'Jean', '2009-05-21', 'M', 'COTONOU', 'BENINOISE', 'PASSANT']
        ]);
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
            'statut'
        ];
    }

    public function title(): string
    {
        return substr($this->classe->nom, 0, 31);
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
        
        ];
    }
}
