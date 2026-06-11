<?php

namespace App\Exports;

use App\Models\Classe;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ElevesTemplateMultiFeuillesExport implements WithMultipleSheets
{
    public function sheets(): array
    {
       $sheets = [];
$classes = Classe::all();

if ($classes->isEmpty()) {
    $sheets[] = new ElevesTemplateFeuilleExport(null); // feuille vide
} else {
    foreach ($classes as $classe) {
        $sheets[] = new ElevesTemplateFeuilleExport($classe);
    }
}

return $sheets;

    }
}
