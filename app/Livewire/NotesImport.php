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
use Illuminate\Support\Facades\Storage;
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

    public $annee_id = null;
    public $classe_id = null;
    public $cycle_id = 3; // 🔥 FORCÉ à 3
    public $trimestre_id = null;
    public $matiere_id = null;

    public $fichier;
    public $fichierPath;
    public $preview = [];
    public $errorsPreview = [];
    public $headers = [];
    public $feuilleTrouvee;
    public $fichierName;
    public $feuilleSelectionnee = '';
    public $confirmationImport = false;
    public $messageConfirmation = '';
    public $dataImportTemp = [];
    public $confirmOverwrite = false;
    public $importMessage = '';
    public $storedPreview = [];

    protected $rules = [
        'annee_id' => 'nullable|integer',
        'cycle_id' => 'nullable|integer',
        'classe_id' => 'nullable|integer',
    ];

    public function mount()
    {
        $this->cycles = Cycle::all();
        $this->annees = Annee::all();
        $this->feuilleTrouvee = false;

        // 🔥 Charger directement les classes du cycle 3
        $this->classes = Classe::where('cycle_id', 3)
            ->orderBy('ordre')
            ->get();
    }

    // ✅ CORRIGÉ : un seul store, utilisation de Storage::path()
    public function updatedFichier()
    {
        if ($this->fichier) {
            $this->fichierName = $this->fichier->getClientOriginalName();
            $this->fichierPath = $this->fichier->store('imports_notes');
        }
    }

    public function viderFichier()
    {
        if ($this->fichierPath && Storage::exists($this->fichierPath)) {
            Storage::delete($this->fichierPath);
        }

        $this->reset([
            'fichier',
            'fichierName',
            'fichierPath',
        ]);
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['annee_id', 'classe_id', 'matiere_id', 'trimestre_id'])) {
            $this->$propertyName = $this->$propertyName ?: null;
        }
    }

    /**
     * 🔥 Filtrer classes selon année MAIS toujours cycle 3
     */
    public function updatedAnneeId($value)
    {
        if (empty($value)) {
            $this->reset(['classes', 'trimestres', 'classe_id', 'trimestre_id', 'matieres', 'matiere_id']);

            // 🔥 Recharge cycle 3 même sans année
            $this->classes = Classe::where('cycle_id', 3)->get();
            return;
        }

        $this->classes = Classe::where('cycle_id', 3)
            ->whereHas('annees', function ($q) use ($value) {
                $q->where('annee_id', $value)
                  ->where('active', true);
            })
            ->orderBy('ordre')
            ->get();

        $this->trimestres = Trimestre::whereHas('annees', function ($q) use ($value) {
            $q->where('annee_id', $value)->where('active', true);
        })->get();

        $this->reset(['classe_id', 'trimestre_id', 'matieres', 'matiere_id']);
    }

    public function updatedClasseId($value)
    {
        if (empty($value)) {
            $this->reset(['matieres', 'matiere_id']);
            return;
        }

        $this->matieres = Matiere::whereHas('classes', function ($q) use ($value) {
            $q->where('classe_id', $value)->where('active', true);
        })->get();

        $this->matiere_id = null;
    }

    private function detecterTrimestre($texte)
    {
        $texte = $this->normaliserTexte($texte);

        if (str_contains($texte, 'premier')) return 1;
        if (str_contains($texte, 'deuxieme')) return 2;
        if (str_contains($texte, 'troisieme')) return 3;

        if (str_contains($texte, '1er')) return 1;
        if (str_contains($texte, '2eme')) return 2;
        if (str_contains($texte, '3eme')) return 3;

        return null;
    }

    private function getNumeroTrimestre($nom)
    {
        $nom = $this->normaliserTexte($nom);

        if (str_contains($nom, 'premier')) return 1;
        if (str_contains($nom, 'deuxieme')) return 2;
        if (str_contains($nom, 'troisieme')) return 3;

        return null;
    }

    private function normaliserTexte($texte)
    {
        $texte = strtolower($texte);
        $texte = iconv('UTF-8', 'ASCII//TRANSLIT', $texte);
        $texte = str_replace(['-', '_'], ' ', $texte);
        $texte = preg_replace('/[^a-z0-9 ]/', '', $texte);
        $texte = preg_replace('/\s+/', ' ', $texte);
        return trim($texte);
    }

    /**
     * 🔧 Normaliser nom feuille
     */
    private function normaliserNomFeuille($nomMatiere)
    {
        return trim(ucfirst(strtolower($nomMatiere)));
    }

    /**
     * 📊 Traitement des données Excel
     */
    private function processerDonnees($rows)
    {
        $this->headers = $rows[0] ?? [];
        unset($rows[0]);
        $this->preview = [];

        $matiere = Matiere::find($this->matiere_id);
        $classe = Classe::find($this->classe_id);
        $trimestre = Trimestre::find($this->trimestre_id);

        foreach ($rows as $i => $row) {

            if (empty($row) || count($row) < 3) continue;
            if (count($this->headers) !== count($row)) continue;

            $dataRow = array_combine($this->headers, $row);
            $matricule = trim($dataRow['Matricule'] ?? '');
            $errors = [];

            // =========================
            // 🔍 MATRICULE
            // =========================
            if (!$matricule) {
                $errors[] = "Matricule manquant";

                $this->preview[] = [
                    'data'    => $dataRow,
                    'notes'   => [],
                    'errors'  => $errors,
                    'nbNotes' => 0,
                    'statut'  => 'Invalide'
                ];

                continue;
            }

            $eleve = Eleve::where('matricule', $matricule)->first();

            if (!$eleve) {
                $errors[] = "❌ Élève introuvable";
            } else {

                $inscription = Inscription::where([
                    'eleve_id'  => $eleve->id,
                    'classe_id' => $this->classe_id,
                    'annee_id'  => $this->annee_id
                ])->first();

                if (!$inscription) {
                    $errors[] = "❌ Pas inscrit en classe";
                } else {

                    $exists = Note::where([
                        'inscription_id' => $inscription->id,
                        'matiere_id'     => $this->matiere_id,
                        'trimestre_id'   => $this->trimestre_id,
                    ])->exists();

                    if ($exists && !$this->confirmOverwrite) {

                        $this->storedPreview = [...$this->preview];

                        $this->importMessage =
                            "⚠️ Les notes de {$matiere->nom} {$classe->nom}, {$trimestre->nom} existent déjà. Voulez-vous écraser ?";

                        session()->flash('warning', $this->importMessage);

                        $errors[] = "⚠️ Doublon détecté";
                    }
                }
            }

            // =========================
            // 📊 NOTES
            // =========================
            $notesDisponibles = [];
            $champsNotes = ['Moyenne Interrogation', 'Devoir1', 'Devoir2'];

            foreach ($champsNotes as $champ) {

                $valeur = $dataRow[$champ] ?? null;

                if ($valeur !== null && $valeur !== '' && $valeur !== '0') {

                    $valeurFloat = (float) $valeur;

                    if ($valeurFloat >= 0 && $valeurFloat <= 20) {
                        $notesDisponibles[$champ] = $valeurFloat;
                    } else {
                        $errors[] = "$champ invalide ($valeur)";
                    }
                }
            }

            $nbNotesValides = count($notesDisponibles);

            $dataRow['_statut'] =
                $nbNotesValides === 0 ? 'Sans note' :
                ($nbNotesValides < 3 ? "{$nbNotesValides}/3" : 'Complet');

            $this->preview[] = [
                'data'    => $dataRow,
                'notes'   => $notesDisponibles,
                'errors'  => $errors,
                'nbNotes' => $nbNotesValides,
                'statut'  => $dataRow['_statut']
            ];
        }

        $this->storedPreview = [...$this->preview];
    }

    /**
     * 🔥 PREVIEW FICHIER
     */
    public function previewFile()
    {
        // 🔄 Nettoyage des champs vides
        foreach (['annee_id', 'classe_id', 'trimestre_id', 'matiere_id'] as $field) {
            if ($this->$field === "") {
                $this->$field = null;
            }
        }

        // ✅ Validation
        $this->validate([
            'annee_id'     => 'required|integer|exists:annees,id',
            'classe_id'    => 'required|integer|exists:classes,id',
            'trimestre_id' => 'required|integer|exists:trimestres,id',
            'matiere_id'   => 'required|integer|exists:matieres,id',
            'fichier'      => 'nullable|file|mimes:xlsx',
        ]);

        try {

            // ✅ CORRIGÉ : vérification via Storage::exists()
            if (!$this->fichierPath || !Storage::exists($this->fichierPath)) {
                session()->flash('error', "❌ Aucun fichier chargé ou fichier introuvable sur le serveur");
                return;
            }

            $matiere  = Matiere::findOrFail($this->matiere_id);
            $trimestre = Trimestre::findOrFail($this->trimestre_id);

            $nomFeuilleRecherche = strtolower(trim($matiere->nom));

            // ✅ CORRIGÉ : Storage::path() au lieu de storage_path('app/...')
            $cheminFichier = Storage::path($this->fichierPath);

            if (!file_exists($cheminFichier)) {
                session()->flash('error', "❌ Fichier introuvable sur le serveur");
                return;
            }

            // 📌 Chargement Excel
            $spreadsheet = IOFactory::load($cheminFichier);

            $feuilleTrouvee = null;

            // 🔍 Recherche feuille par matière
            foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
                $nomFeuille = strtolower(trim($sheet->getTitle()));

                if (str_contains($nomFeuille, $nomFeuilleRecherche)) {
                    $feuilleTrouvee = $sheet;
                    break;
                }
            }

            // ❌ Feuille non trouvée
            if (!$feuilleTrouvee) {
                session()->flash('error', "❌ Feuille '{$matiere->nom}' introuvable dans le fichier");
                return;
            }

            $nomFeuille = $feuilleTrouvee->getTitle();
            $nomFichier = $this->fichierName ?? 'fichier inconnu';

            // 🔢 Détection trimestre
            $numeroSelectionne = $this->getNumeroTrimestre($trimestre->nom);

            $trimestreFeuille  = $this->detecterTrimestre($nomFeuille);
            $trimestreFichier  = $this->detecterTrimestre($nomFichier);

            $trimestreDetecte = $trimestreFeuille ?? $trimestreFichier;

            if (!$trimestreDetecte) {
                session()->flash('error', "❌ Impossible de détecter le trimestre du fichier");
                return;
            }

            if ($trimestreDetecte !== $numeroSelectionne) {

                $labels = [
                    1 => 'Premier Trimestre',
                    2 => 'Deuxième Trimestre',
                    3 => 'Troisième Trimestre'
                ];

                session()->flash(
                    'error',
                    "❌ ERREUR : Ce fichier correspond au {$labels[$trimestreDetecte]} alors que vous avez sélectionné {$trimestre->nom}"
                );

                $this->preview = [];
                return;
            }

            // 📊 Lecture données
            $rows = $feuilleTrouvee->toArray();

            if (empty($rows)) {
                session()->flash('error', "❌ La feuille est vide");
                return;
            }

            $headers = $rows[0] ?? [];
            $requiredHeaders = ['Matricule', 'Moyenne Interrogation', 'Devoir1', 'Devoir2'];

            foreach ($requiredHeaders as $header) {
                if (!in_array($header, $headers)) {
                    session()->flash('error', "❌ Colonne manquante : $header");
                    return;
                }
            }

            // 🔄 Traitement
            $this->processerDonnees($rows);

            $this->feuilleSelectionnee = $nomFeuille;

            session()->flash('success', "✅ Fichier chargé avec succès : {$nomFichier}");

        } catch (\Exception $e) {

            Log::error('NotesImport::previewFile error', ['message' => $e->getMessage()]);
            session()->flash('error', "❌ Erreur : " . $e->getMessage());
        }
    }

    /**
     * 💾 IMPORT
     */
    public function importer()
    {
        if (empty($this->preview)) {
            session()->flash('error', '❌ Aucune donnée à importer');
            return;
        }

        $imported = 0;
        $skipped  = 0;

        foreach ($this->preview as $row) {

            if (!isset($row['data'])) {
                $skipped++;
                continue;
            }

            $data  = $row['data'];
            $notes = $row['notes'] ?? [];

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
                'eleve_id'  => $eleve->id,
                'classe_id' => $this->classe_id,
                'annee_id'  => $this->annee_id
            ])->first();

            if (!$inscription) {
                $skipped++;
                continue;
            }

            $interro = $notes['Moyenne Interrogation'] ?? null;
            $d1      = $notes['Devoir1'] ?? null;
            $d2      = $notes['Devoir2'] ?? null;

            $moyenne = $this->calculerMoyenne($interro, $d1, $d2);

            $note = Note::where([
                'inscription_id' => $inscription->id,
                'matiere_id'     => $this->matiere_id,
                'trimestre_id'   => $this->trimestre_id,
            ])->first();

            if ($note) {
                $note->update([
                    'moyenne_interro'  => $interro,
                    'devoir1'          => $d1,
                    'devoir2'          => $d2,
                    'moyenne_matiere'  => $moyenne,
                    'appreciation'     => $this->genererAppreciation($moyenne),
                ]);
            } else {
                Note::create([
                    'inscription_id'  => $inscription->id,
                    'classe_id'       => $this->classe_id,
                    'matiere_id'      => $this->matiere_id,
                    'trimestre_id'    => $this->trimestre_id,
                    'annee_id'        => $this->annee_id,
                    'moyenne_interro' => $interro,
                    'devoir1'         => $d1,
                    'devoir2'         => $d2,
                    'moyenne_matiere' => $moyenne,
                    'appreciation'    => $this->genererAppreciation($moyenne),
                ]);
            }

            $imported++;
        }

        // ✅ Nettoyage après import
        $this->preview = [];
        $this->reset(['confirmOverwrite']);

        session()->flash('success', "✅ {$imported} importées, {$skipped} ignorées");
    }

    private function genererAppreciation($moyenne)
    {
        if ($moyenne === null) return null;

        if ($moyenne >= 18) return "Excellent";
        if ($moyenne >= 16) return "Très bien";
        if ($moyenne >= 14) return "Bien";
        if ($moyenne >= 12) return "Assez bien";
        if ($moyenne >= 10) return "Passable";
        if ($moyenne >= 8)  return "Insuffisant";
        if ($moyenne >= 6)  return "Faible";
        if ($moyenne >= 4)  return "Très Faible";

        return "Médiocre";
    }

    private function calculerMoyenne($a, $b, $c)
    {
        $vals = array_filter([$a, $b, $c], fn($v) => $v !== null);
        return count($vals) ? round(array_sum($vals) / count($vals), 2) : null;
    }

    // 🔥 Bouton "Oui, écraser"
    public function confirmOverwrite()
    {
        $this->confirmOverwrite = true;
        $this->importer();
    }

    // 🔥 Bouton "Annuler"
    public function cancelImport()
    {
        $this->reset(['preview', 'confirmOverwrite', 'importMessage', 'storedPreview']);
        session()->flash('info', '❌ Import annulé');
    }

    public function confirmerRemplacement()
    {
        if (empty($this->storedPreview)) {
            session()->flash('error', '❌ Données perdues');
            return;
        }

        $this->preview = $this->storedPreview;
        $this->confirmOverwrite = true;

        $this->importer();
    }

    public function downloadTemplate()
    {
        $this->validate([
            'annee_id'     => 'required|integer',
            'classe_id'    => 'required|integer',
            'trimestre_id' => 'required|integer',
        ]);

        return redirect()->route('notes.template', [
            'annee_id'     => $this->annee_id,
            'classe_id'    => $this->classe_id,
            'trimestre_id' => $this->trimestre_id,
        ]);
    }

    public function render()
    {
        return view('livewire.notes-import', [
            'selectedClasse'  => Classe::find($this->classe_id),
            'classes'         => $this->classes,
            'selectedMatiere' => Matiere::find($this->matiere_id),
        ]);
    }
}