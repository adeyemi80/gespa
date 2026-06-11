<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Classe;

class MatieresTemplateExport implements WithMultipleSheets
{
    protected $classes;

    public function __construct($classes)
    {
        $this->classes = $classes;
    }

    public function sheets(): array
{
    $sheets = [];

    $classes = Classe::orderByNiveau()->get();

    foreach ($classes as $classe) {
        $sheets[] = new MatieresClasseSheet($classe);
    }

    return $sheets;
}
}
