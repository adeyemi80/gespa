<?php

namespace App\Imports;

use App\Models\Annee;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NotesImport implements ToArray, WithHeadingRow
{
    public function array(array $array)
    {
        return $array;
    }
}
