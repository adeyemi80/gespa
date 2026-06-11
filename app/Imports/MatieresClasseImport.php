<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MatieresClasseImport implements ToCollection
{
    private array $data = [];
    private int $classe_id;

    public function __construct(int $classe_id)
    {
        $this->classe_id = $classe_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index == 0) continue;

            $this->data[] = [
                'nom' => $row[0] ?? null,
                'coefficient' => $row[1] ?? 1,
                'type' => $row[2] ?? 'scientifique',
                'enseignant_id' => $row[3] ?? null,
                'classe_id' => $this->classe_id,
            ];
        }
    }

    public function getData()
    {
        return $this->data;
    }

    // ✅ SOLUTION : Ajoutez des vérifications de type
public function model(array $row) 
{
    // Vérifiez que les valeurs ne sont pas des strings vides
    $nom = trim($row[0] ?? ''); // ou $row['nom']
    if (empty($nom)) return null;

    return new Matiere([
        'nom' => $nom,
        'coefficient' => (float)($row[1] ?? 1),
        'type' => $row[2] ?? 'scientifique',
    ]);
}

}

