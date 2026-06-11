<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

// app/Exports/ElevesSheetExport.php
//use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ElevesSheetExport implements FromCollection, WithTitle, WithHeadings
{
    protected $classe;

    public function __construct($classe)
    {
        $this->classe = $classe;
    }

    public function collection()
    {
        return $this->classe->eleves()->select('matricule', 'nom', 'prenom', 'date_naissance', 'lieu_naissance')->orderBy('nom')->get();
    }

    public function title(): string
    {
        return $this->classe->nom;
    }

    public function headings(): array
    {
        return ['Matricule', 'Nom', 'Prénom', 'Date de Naissance', 'Lieu de Naissance', 'numeducmaster'];
    }
}
