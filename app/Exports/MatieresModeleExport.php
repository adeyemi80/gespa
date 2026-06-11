<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use App\Models\Classe;
use App\Models\Cycle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class MatieresModeleExport implements WithMultipleSheets
{
    protected $classes;
    protected $cycle;

    public function __construct($classes, $cycle)
    {
        $this->classes = collect($classes)
            ->sortBy('ordre')
            ->values();

        $this->cycle = $cycle;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->classes as $classe) {
            $sheets[] = new ClasseSheetExport($classe);
        }

        return $sheets;
    }
}

/**
 * Classe représentant une feuille Excel pour une classe spécifique
 */
class ClasseSheetExport implements FromArray, WithTitle, WithHeadings, WithColumnWidths
{
    protected $classe;

    public function __construct(Classe $classe)
    {
        $this->classe = $classe;
    }

    public function array(): array
    {
        return [];
    }

    public function title(): string
    {
        return substr($this->classe->nom, 0, 31);
    }

    public function headings(): array
    {
        return [
            ['Nom', 'Type', 'Coefficient', 'Niveau']
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 25,
            'C' => 25,
            'D' => 25,
        ];
    }
}