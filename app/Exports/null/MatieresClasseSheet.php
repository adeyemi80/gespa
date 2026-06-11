<?php


namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use App\Models\Classe;

class MatieresClasseSheet implements FromArray, WithTitle, WithColumnWidths
{
    protected $classe;

    public function __construct($classe)
    {
        $this->classe = $classe;
    }

    public function array(): array
    {
         $classes = Classe::orderByNiveau()->get();
        return [
            ['nom', 'type', 'coefficient'],
            //['Mathématiques', 'scientifique', 4],
            //['Français', 'litteraire', 3],
        ];
    }
   public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 15,
            'C' => 25,
            'D' => 18,
        ];
    }
    public function title(): string
    {
        return $this->classe->nom; // nom de la feuille
    }

    public function sheets(): array
{
    $sheets = [];

    $classes = Classe::orderByNiveau()->get(); // 🔥 IMPORTANT

    foreach ($classes as $classe) {
        $sheets[] = new MatieresClasseSheet($classe);
    }

    return $sheets;
}
}