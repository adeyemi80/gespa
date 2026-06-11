<?php

namespace App\Exports;

use App\Models\Annee;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ModeleEleveExport implements WithMultipleSheets
{
    protected $annee_id;
    protected $cycle_id;

    public function __construct($annee_id, $cycle_id)
    {
        $this->annee_id = $annee_id;
        $this->cycle_id = $cycle_id;
    }

    public function sheets(): array
    {
        $sheets = [];

        // ✅ Charger l'année avec ses classes (pivot)
        $annee = Annee::with('classes')->findOrFail($this->annee_id);

        // ✅ Filtrer les classes par cycle
       $classes = $annee->classes()
    ->where('cycle_id', $this->cycle_id)
    ->orderByNiveau()
    ->get();

        foreach ($classes as $classe) {
            $sheets[] = new ModeleEleveSheetExport($classe);
        }

        return $sheets;
    }
}