<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Trimestre;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Eleve;
use App\Models\Inscription;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class NotesImport extends Component
{
    use WithFileUploads;

    public $annees = [];
    public $classes = [];
    public $cycles = [];
    public $trimestres = [];
    public $matieres = [];

    public $annee_id;
    public $classe_id;
    public $cycle_id;
    public $trimestre_id;
    public $matiere_id;

    public $fichier;
    public $fichierPath;
    public $fichierName;

    public $preview = [];
    public $headers = [];
    public $feuilleSelectionnee = '';

    // 🔥 CONFIRMATION IMPORT
    public $confirmationImport = false;
    public $messageConfirmation = '';

    public function mount()
    {
        $this->cycles = Cycle::all();
        $this->annees = Annee::all();
    }

    /**
     * 📂 STOCKAGE FICHIER
     */
    public function updatedFichier()
    {
        if ($this->fichier) {
            $this->fichierPath = $this->fichier->store('imports');
            $this->fichierName = $this->fichier->getClientOriginalName();
        }
    }

    /**
     * 🔍 CHECK DOUBLON GLOBAL
     */
    private function verifierDoublonGlobal()
    {
        return Note::where('matiere_id', $this->matiere_id)
            ->where('trimestre_id', $this->trimestre_id)
            ->whereHas('inscription', function ($q) {
                $q->where('classe_id', $this->classe_id)
                  ->where('annee_id', $this->annee_id);
            })
            ->exists();
    }

    /**
     * 📊 PREVIEW FILE
     */
    public function previewFile()
    {
        foreach (['annee_id', 'classe_id', 'trimestre_id', 'matiere_id'] as $field) {
            if ($this->$field === "") {
                $this->$field = null;
            }
        }

        $this->validate([
            'annee_id'     => 'required|integer|exists:annees,id',
            'classe_id'    => 'required|integer|exists:classes,id',
            'trimestre_id' => 'required|integer|exists:trimestres,id',
            'matiere_id'   => 'required|integer|exists:matieres,id',
            'fichier'      => 'nullable|file|mimes:xlsx',
        ]);

        // 🔥 SI DOUBLON GLOBAL → CONFIRMATION
        if ($this->verifierDoublonGlobal()) {

            $classe = Classe::find($this->classe_id);
            $matiere = Matiere::find($this->matiere_id);
            $trimestre = Trimestre::find($this->trimestre_id);

            $this->confirmationImport = true;

            $this->messageConfirmation =
                "❌ Les notes de {$matiere->nom} de la classe {$classe->nom} pour le {$trimestre->nom} sont déjà importées. Voulez-vous les remplacer ?";

            return;
        }

        try {

            if (!$this->fichierPath) {
                session()->flash('error', "❌ Aucun fichier chargé");
                return;
            }

            $chemin = storage_path('app/' . $this->fichierPath);
            $spreadsheet = IOFactory::load($chemin);

            $matiere = Matiere::find($this->matiere_id);
            $nomFeuilleRecherche = strtolower(trim($matiere->nom));

            $feuilleTrouvee = null;

            foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
                $nomFeuille = strtolower(trim($sheet->getTitle()));

                if (str_contains($nomFeuille, $nomFeuilleRecherche)) {
                    $feuilleTrouvee = $sheet;
                    break;
                }
            }

            if (!$feuilleTrouvee) {
                session()->flash('error', "❌ Feuille introuvable");
                return;
            }

            $rows = $feuilleTrouvee->toArray();

            if (empty($rows)) {
                session()->flash('error', "❌ Feuille vide");
                return;
            }

            $this->processerDonnees($rows);

            $this->feuilleSelectionnee = $feuilleTrouvee->getTitle();

            session()->flash('success', "✅ Fichier chargé avec succès");

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    /**
     * 🔄 PROCESS DATA
     */
    private function processerDonnees($rows)
    {
        $this->headers = $rows[0] ?? [];
        unset($rows[0]);

        $this->preview = [];

        foreach ($rows as $row) {

            if (count($row) < 3) continue;

            $dataRow = array_combine($this->headers, $row);
            $matricule = trim($dataRow['Matricule'] ?? '');

            $notes = [];
            $champs = ['Moyenne Interrogation', 'Devoir1', 'Devoir2'];

            foreach ($champs as $champ) {
                $val = $dataRow[$champ] ?? null;

                if ($val !== null && $val !== '' && $val !== '0') {
                    $notes[$champ] = (float) $val;
                }
            }

            $this->preview[] = [
                'data' => $dataRow,
                'notes' => $notes,
                'nbNotes' => count($notes),
            ];
        }
    }

    /**
     * ✅ OUI → REMPLACER
     */
    public function confirmerRemplacement()
    {
        Note::where('matiere_id', $this->matiere_id)
            ->where('trimestre_id', $this->trimestre_id)
            ->whereHas('inscription', function ($q) {
                $q->where('classe_id', $this->classe_id)
                  ->where('annee_id', $this->annee_id);
            })
            ->delete();

        $this->confirmationImport = false;

        $this->previewFile();
    }

    /**
     * ❌ NON → ANNULER
     */
    public function annulerRemplacement()
    {
        $this->confirmationImport = false;
        $this->messageConfirmation = '';
        $this->preview = [];

        session()->flash('info', "Import annulé");
    }

    /**
     * 💾 IMPORT FINAL
     */
    public function importer()
    {
        $imported = 0;
        $skipped = 0;

        foreach ($this->preview as $row) {

            $data = $row['data'];
            $matricule = $data['Matricule'] ?? null;

            if (!$matricule) {
                $skipped++;
                continue;
            }

            $eleve = Eleve::where('matricule', $matricule)->first();

            if (!$eleve) {
                $skipped++;
                continue;
            }

            $inscription = Inscription::where([
                'eleve_id' => $eleve->id,
                'classe_id' => $this->classe_id,
                'annee_id' => $this->annee_id
            ])->first();

            if (!$inscription) {
                $skipped++;
                continue;
            }

            $notes = $row['notes'];

            $interro = $notes['Moyenne Interrogation'] ?? null;
            $d1 = $notes['Devoir1'] ?? null;
            $d2 = $notes['Devoir2'] ?? null;

            $moyenne = $this->calculerMoyenne($interro, $d1, $d2);

            Note::create([
                'inscription_id' => $inscription->id,
                'classe_id' => $this->classe_id,
                'matiere_id' => $this->matiere_id,
                'trimestre_id' => $this->trimestre_id,
                'annee_id' => $this->annee_id,
                'moyenne_interro' => $interro,
                'devoir1' => $d1,
                'devoir2' => $d2,
                'moyenne_matiere' => $moyenne,
            ]);

            $imported++;
        }

        $this->reset(['preview', 'fichier', 'fichierPath', 'fichierName']);

        session()->flash('success', "✅ {$imported} importées, {$skipped} ignorées");
    }

    private function calculerMoyenne($a, $b, $c)
    {
        $vals = array_filter([$a, $b, $c], fn($v) => $v !== null);
        return count($vals) ? round(array_sum($vals) / count($vals), 2) : null;
    }

    public function render()
    {
        return view('livewire.notes-import');
    }
}