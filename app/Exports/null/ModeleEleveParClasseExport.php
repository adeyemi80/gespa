<?php

namespace App\Exports;

use App\Models\Classe;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ModeleEleveParClasseExport implements WithMultipleSheets
{
    protected $classes;

    public function __construct($classes = null)
    {
        // Tu peux passer les classes ou les récupérer ici
        $this->classes = $classes ?? Classe::all();
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->classes as $classe) {
            $sheets[] = new ModeleEleveSheetExport($classe);
        }

        return $sheets;
    }
}