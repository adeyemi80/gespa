<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PreviewMatieresImport implements WithMultipleSheets
{
    public $sheetsData = [];

    public function sheets(): array
    {
        return [];
    }

    public function __construct()
    {
        $this->sheetsData = [];
    }

    public function onSheet($sheetName, $sheetData)
    {
        $this->sheetsData[$sheetName] = $sheetData;
    }
}
