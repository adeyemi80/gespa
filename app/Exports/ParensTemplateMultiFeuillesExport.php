<?php

namespace App\Exports;

 use Illuminate\Support\Collection;
use App\Models\Classe;
use App\Models\Inscription;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ParensTemplateMultiFeuillesExport implements WithMultipleSheets
{
    protected $classe_ids;
    protected $annee_id;

    /**
     * @param array|int $classe_ids
     * @param int $annee_id
     */
    public function __construct($classe_ids, $annee_id)
    {
        $this->classe_ids = is_array($classe_ids) ? $classe_ids : [$classe_ids];
        $this->annee_id   = $annee_id;
    }

    /**
     * Crée les feuilles Excel pour chaque classe
     */

public function sheets(): array
{
    $sheets = [];

    $classes = Classe::whereIn('id', $this->classe_ids)->get();

    foreach ($classes as $classe) {

        $inscriptions = Inscription::with('eleve')
            ->where('classe_id', $classe->id)
            ->where('annee_id', $this->annee_id)
            ->get();

        if ($inscriptions->isEmpty()) {
            continue;
        }

        $sheets[] = new \App\Exports\ParensTemplateFeuilleExport(
            $classe->id,
            $classe->nom,
            $inscriptions
        );
    }

    /**
     * 🔥 AUCUNE FEUILLE → FEUILLE DE SECOURS
     */
    if (empty($sheets)) {
        $sheets[] = new \App\Exports\ParensTemplateFeuilleExport(
            0,
            'Aucune inscription',
            collect()
        );
    }

    return $sheets;
}

}
