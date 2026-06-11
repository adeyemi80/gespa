<?php

namespace App\Exports;

use App\Models\Classe;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NotesTemplateMultiSheetsExport implements WithMultipleSheets
{
    protected $anneeId;
    protected $classeId;

    public function __construct($anneeId, $classeId)
    {
        $this->anneeId  = $anneeId;
        $this->classeId = $classeId;
    }

    public function sheets(): array
    {
        $sheets = [];

        $classe = Classe::with('matieres')->findOrFail($this->classeId);

        foreach ($classe->matieres as $matiere) {
            $sheets[] = new NotesTemplateMatiereSheet(
                $this->anneeId,
                $this->classeId,
                $matiere
            );
        }

        return $sheets;
    }
}
