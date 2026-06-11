<?php


// app/Exports/PaiementsExport.php

namespace App\Exports;

use App\Models\Paiement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaiementsExport implements FromCollection, WithHeadings
{
    public function collection()
{
    return Paiement::with('inscription.eleve', 'inscription.classe', 'inscription.annee', 'frais')
        ->get()
        ->map(function ($p) {

            return [
                'Élève' => $p->inscription->eleve->nom . ' ' . $p->inscription->eleve->prenom ?? '',
                'Classe' => $p->inscription->classe->nom ?? '',
                'Année' => $p->inscription->annee->libelle ?? '',
                'Frais' => $p->frais->description ?? '',
                'Montant Versé' => $p->montant_verse,
                'Mode Paiement' => $p->mode_paiement,
                'N° Reçu' => $p->numero_recu,
                'Date' => $p->date_paiement,
            ];
        });
}

    public function headings(): array
    {
        return [
            'Élève',
            'Classe',
            'Année',
            'Frais',
            'Montant Versé',
            'Mode Paiement',
            'N° Reçu',
            'Date',
        ];
    }
}
