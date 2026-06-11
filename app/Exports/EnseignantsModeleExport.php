<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
//use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class EnseignantsModeleExport implements WithHeadings, WithColumnWidths, WithColumnFormatting
{
    public function headings(): array
    {
        return [
            'nom', 'prenom', 'date_naissance', 'sexe', 'adresse',
            'telephone', 'email', 'specialite',
            'grade', 'date_embauche', 'statut', 'cycle', 'matieres', 'classes'
        ];
    }

    /**public function array(): array
    {
        return [
            [
                'Doe', 'Jean', '1990-05-12', 'M', 'Cotonou',
                '97000000', 'jean@email.com', 'ENS001', 'Mathématiques',
                'Titulaire', '2022-10-01', 'actif', 1, 2
            ]
        ];
    }*/
public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, // date naissance
            'F' => NumberFormat::FORMAT_TEXT, // téléphone
            'G' => NumberFormat::FORMAT_TEXT, // email
            
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 15, // nom
            'B' => 20, // prenom
            'C' => 18, // date_naissance
            'D' => 10, // sexe
            'E' => 15, // adresse
            'F' => 15, // telephone
            'G' => 20, // email
            'H' => 15, // specialite
            'I' => 15, //  grade
            'J' => 10, // date_embauche
            'K' => 15, // statut
            'L' => 15, // cycle
            'M' => 15, // matiere
            'N' => 15, // classeS
            
            
        ];
    }
}
