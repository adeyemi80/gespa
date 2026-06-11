<?php

namespace App\Imports;

use App\Models\Classe;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MatieresPreviewImport implements WithMultipleSheets
{
    public $data = [];

    public function sheets(): array
    {
        $sheets = [];

        foreach (Classe::all() as $classe) {
            $sheets[$classe->nom] = new MatieresPreviewPerSheet($this, $classe);
        }

        return $sheets;
    }
}
