<?php

namespace App\Exports;

use App\Models\Inscription;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TdPaiementsExport implements FromCollection, WithHeadings
{
    protected $classe_id;
    protected $date;

    public function __construct($classe_id, $date = null)
    {
        $this->classe_id = $classe_id;
        $this->date = $date ?? now()->toDateString();
    }

    public function collection()
    {
        // Récupérer toutes les inscriptions de la classe
        $inscriptions = Inscription::with(['eleve', 'tdParticipations.paiements' => function($q){
            $q->where('paye', true);
        }])
        ->where('classe_id', $this->classe_id)
        ->get();

        // Construire le tableau pour Excel
        $data = [];
        foreach ($inscriptions as $insc) {
            $totalPaye = $insc->tdParticipations->sum(function($p){
                return $p->paiements->sum('montant');
            });

            $totalNonPaye = $insc->tdParticipations->sum(function($p){
                return $p->paiements->where('paye', false)->sum('montant');
            });

            $data[] = [
                'Élève' => $insc->eleve->nom,
                'Total payé' => $totalPaye,
                'Total non payé' => $totalNonPaye
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return ['Élève', 'Total payé (FCFA)', 'Total non payé (FCFA)'];
    }
}
