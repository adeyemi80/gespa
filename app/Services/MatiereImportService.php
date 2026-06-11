<?php
// app/Services/MatiereImportService.php

namespace App\Services;

use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Annee;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MatiereImport;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MatiereImportService
{
    // app/Services/MatiereImportService.php

public function getClassesByAnnee($anneeId)
{
    // ✅ PROTECTION POSTGRESQL
    if (!is_numeric($anneeId)) {
        return collect();
    }
    
    return Classe::whereHas('annee', function($q) use ($anneeId) {
        $q->where('id', $anneeId); // ID numérique garanti
    })->get();
}


    public function generateTemplate($classes)
    {
        $spreadsheet = new Spreadsheet();
        
        foreach ($classes as $index => $classe) {
            $sheet = $spreadsheet->createSheet($index);
            $sheet->setTitle($classe->nom);
            
            // Headers
            $sheet->setCellValue('A1', 'Nom Matière');
            $sheet->setCellValue('B1', 'Coefficient');
            $sheet->setCellValue('C1', 'Type');
            $sheet->setCellValue('D1', 'Enseignant ID (optionnel)');
            
            // Styles
            $headerStyle = [
                'font' => ['bold' => true],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F4FD']]
            ];
            $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);
            $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal('center');
        }

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        
        $filename = 'template_matieres_' . now()->format('Y-m-d_H-i') . '.xlsx';
        $filePath = 'imports/' . $filename;
        
        Storage::disk('public')->put($filePath, $writer->getContents());
        
        return response()->download(
            Storage::disk('public')->path($filePath),
            $filename
        )->deleteFileAfterSend(true);
    }

    public function previewImport($file, $anneeId)
    {
        $path = $file->store('imports/temp');
        $fullPath = Storage::disk('local')->path($path);

        Excel::import(new MatiereImport($anneeId, true), $fullPath);

        $data = session('import_preview_data', []);
        $errors = session('import_errors', collect());

        return [
            'data' => $data,
            'errors' => $errors,
            'filePath' => $path
        ];
    }

    public function processImport($anneeId, $filePath)
    {
        $fullPath = Storage::disk('local')->path($filePath);
        
        Excel::import(new MatiereImport($anneeId, false), $fullPath);
        
        $results = session('import_results', ['success' => 0, 'errors' => 0]);
        Storage::delete($filePath);
        
        return $results;
    }
    
}
