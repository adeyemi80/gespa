<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Enseignant;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Matiere;
use App\Exports\EnseignantsModeleExport;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportEnseignants extends Component
{
    use WithFileUploads;

    public $fichier;
    public $preview = [];
    public $headers = [];
    public $erreurs = [];

    public function updatedFichier()
    {
        $this->reset(['preview', 'headers', 'erreurs']);

        $rows = Excel::toArray([], $this->fichier)[0] ?? [];

        $this->headers = $rows[0] ?? [];
        unset($rows[0]);

        $this->preview = array_values($rows);

        $this->validerDonnees();
    }

    private function validerDonnees()
    {
        foreach ($this->preview as $index => $row) {

            $ligne = $index + 2;

            $nom = trim($row[0] ?? '');
            $prenom = trim($row[1] ?? '');
            $date_naissance = $row[2] ?? null;
            $sexe = $row[3] ?? null;
            $email = strtolower(str_replace(',', '.', trim($row[6] ?? '')));
            $matricule = trim($row[7] ?? '');
            $classes = $row[14] ?? null; // 🔥 classes multiples

            // NOM
            if ($nom === '') {
                $this->erreurs[] = "Ligne $ligne : nom obligatoire";
            }

            // PRENOM
            if ($prenom === '') {
                $this->erreurs[] = "Ligne $ligne : prénom obligatoire";
            }

            // EMAIL
            if ($email === '') {
                $this->erreurs[] = "Ligne $ligne : email obligatoire";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->erreurs[] = "Ligne $ligne : email invalide ($email)";
            }

            // SEXE
            if (!in_array($sexe, ['M', 'F'])) {
                $this->erreurs[] = "Ligne $ligne : sexe doit être M ou F";
            }

            // MATRICULE UNIQUE
            if ($matricule !== '' && Enseignant::where('matricule', $matricule)->exists()) {
                $this->erreurs[] = "Ligne $ligne : matricule déjà existant";
            }

            // CLASSES VALIDATION
            if (!empty($classes)) {
                $classList = array_map('trim', explode(',', $classes));

                foreach ($classList as $classeNom) {
                    if (!Classe::where('nom', $classeNom)->exists()) {
                        $this->erreurs[] = "Ligne $ligne : classe introuvable ($classeNom)";
                    }
                }
            }

            // DATE NAISSANCE
            if (!empty($date_naissance)) {

                if (is_numeric($date_naissance)) {
                    try {
                        Date::excelToDateTimeObject($date_naissance)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $this->erreurs[] = "Ligne $ligne : date naissance invalide";
                    }
                } elseif (!strtotime($date_naissance)) {
                    $this->erreurs[] = "Ligne $ligne : date naissance invalide";
                }
            }
        }
    }

    public function importer()
    {
        if (count($this->erreurs) > 0) {
            session()->flash('error', '❌ Corrigez les erreurs avant import');
            return;
        }

        $imported = 0;

        foreach ($this->preview as $row) {

            $dateNaissance = $row[2] ?? null;
            $dateEmbauche = $row[10] ?? null;

            // Dates
            if (is_numeric($dateNaissance)) {
                $dateNaissance = Date::excelToDateTimeObject($dateNaissance)->format('Y-m-d');
            } else {
                $dateNaissance = date('Y-m-d', strtotime($dateNaissance));
            }

            if (is_numeric($dateEmbauche)) {
                $dateEmbauche = Date::excelToDateTimeObject($dateEmbauche)->format('Y-m-d');
            } else {
                $dateEmbauche = !empty($dateEmbauche)
                    ? date('Y-m-d', strtotime($dateEmbauche))
                    : null;
            }

            // 🔥 CREATION ENSEIGNANT
            $enseignant = Enseignant::create([
                'nom' => $row[0],
                'prenom' => $row[1],
                'date_naissance' => $dateNaissance,
                'sexe' => $row[3],
                'adresse' => $row[4],
                'telephone' => $row[5],
                'email' => strtolower(str_replace(',', '.', $row[6] ?? '')),
                'specialite' => $row[7],
                'grade' => $row[8],
                'date_embauche' => $dateEmbauche,
                'statut' => $row[10] ?? 'actif',

                // relations simples
                'cycle_id' => Cycle::where('nom', $row[11] ?? null)->value('id'),
                'matiere_id' => Matiere::where('nom', $row[12] ?? null)->value('id'),
            ]);

            // 🔥 CLASSES (MANY TO MANY)
            if (!empty($row[13])) {
                $classeIds = Classe::whereIn('nom', array_map('trim', explode(',', $row[13])))
                    ->pluck('id')
                    ->toArray();

                $enseignant->classes()->sync($classeIds);
            }

            $imported++;
        }

        session()->flash('success', "$imported enseignants importés ✅");

        $this->reset(['fichier', 'preview', 'headers']);
    }

    public function telechargerModele()
    {
        return Excel::download(
            new EnseignantsModeleExport,
            'modele_import_enseignants.xlsx'
        );
    }

    public function render()
    {
        return view('livewire.import-enseignants');
    }
}