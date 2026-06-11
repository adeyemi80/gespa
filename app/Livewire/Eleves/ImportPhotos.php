<?php

namespace App\Livewire\Eleves;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Eleve;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImportPhotos extends Component
{
    use WithFileUploads;

    public $cycle_id = '';
    public $classe_id = '';

    public $photos = [];
    public $cycles = [];
    public $classes = [];

    public $preview = [];

    public $rapport = [
        'traites'   => 0,
        'importes'  => 0,
        'remplaces' => 0,
        'rejetes'   => 0,
    ];

    public $erreurs = [];

    protected $rules = [
        'cycle_id'  => 'required|exists:cycles,id',
        'classe_id' => 'required|exists:classes,id',
        'photos'    => 'required|min:1',
        'photos.*'  => 'image|mimes:jpg,jpeg,png|max:5120',
    ];

    public function mount()
    {
        $this->cycles = Cycle::orderBy('nom')->get();
        $this->classes = [];
    }

    public function updatedCycleId()
    {
        $this->classe_id = '';

        $this->classes = Classe::where('cycle_id', $this->cycle_id)
            ->orderBy('ordre')
            ->get();
    }

    public function updatedPhotos()
{
    $this->preview = [];
    $this->erreurs = [];

    foreach ($this->photos as $photo) {
        $contenu = file_get_contents($photo->getRealPath());
        $base64  = 'data:' . $photo->getMimeType() . ';base64,' . base64_encode($contenu);

        $this->preview[] = [
            'nom' => $photo->getClientOriginalName(),
            'url' => $base64,
        ];
    }
}

    private function normaliser($texte)
    {
        $texte = iconv('UTF-8', 'ASCII//TRANSLIT', $texte);
        return strtoupper(preg_replace('/[^A-Z0-9]/', '', $texte));
    }

    public function importer()
    {
        $this->validate();

        $this->rapport = [
            'traites'   => 0,
            'importes'  => 0,
            'remplaces' => 0,
            'rejetes'   => 0,
        ];

        $this->erreurs = [];

        $matriculesTraites = [];

        foreach ($this->photos as $photo) {

            $this->rapport['traites']++;

            try {

                // =========================
                // NOM FICHIER
                // =========================
                $nomOriginal = $photo->getClientOriginalName();
                $nomSansExt = pathinfo($nomOriginal, PATHINFO_FILENAME);

                $parts = explode('_', $nomSansExt);

                if (count($parts) < 3) {
                    $this->rejet("Format invalide", $nomOriginal);
                    continue;
                }

                // =========================
                // MATRICULE
                // =========================
                $matricule = preg_replace('/\s+/', '', trim($parts[0]));

                if (!$matricule || in_array($matricule, $matriculesTraites)) {
                    $this->rejet("Matricule invalide ou doublon", $nomOriginal);
                    continue;
                }

                $matriculesTraites[] = $matricule;

                // =========================
                // NOM + PRENOMS
                // =========================
                $nomFichier = $this->normaliser($parts[1]);

                $prenomFichier = $this->normaliser(
                    implode('_', array_slice($parts, 2))
                );

                // =========================
                // ELEVE
                // =========================
                $eleve = Eleve::where('matricule', $matricule)->first();

                if (!$eleve) {
                    $this->rejet("Élève introuvable", $nomOriginal);
                    continue;
                }

                $inscription = $eleve->inscriptions()->latest()->first();

                if (
                    !$inscription ||
                    $inscription->classe_id != $this->classe_id
                ) {
                    $this->rejet("Classe incorrecte", $nomOriginal);
                    continue;
                }

                // =========================
                // VALIDATION NOM
                // =========================
                if ($this->normaliser($eleve->nom) !== $nomFichier) {
                    $this->rejet("Nom incorrect", $nomOriginal);
                    continue;
                }

                if (!str_contains($this->normaliser($eleve->prenom), $prenomFichier)) {
                    $this->rejet("Prénom incorrect", $nomOriginal);
                    continue;
                }

                // =========================
                // SUPPRESSION ANCIENNE PHOTO
                // =========================
                $remplace = false;

                if ($eleve->photo && Storage::disk('public')->exists($eleve->photo)) {
                    Storage::disk('public')->delete($eleve->photo);
                    $remplace = true;
                }

                 // =========================
// IMAGE PROCESSING (v4.1.2)
// =========================
$manager = new ImageManager(new Driver());

$image = $manager->decode($photo->getRealPath())
    ->cover(350, 450);

$nomPhoto = Str::random(40) . '.jpg';
$chemin   = 'photos_eleves/' . $nomPhoto;
$path     = storage_path('app/public/' . $chemin);

if (!is_dir(dirname($path))) {
    mkdir(dirname($path), 0775, true);
}

$image->save($path);
                // =========================
                // UPDATE DB
                // =========================
                $eleve->update([
                    'photo' => $chemin
                ]);

                if ($remplace) {
                    $this->rapport['remplaces']++;
                } else {
                    $this->rapport['importes']++;
                }

            } catch (\Exception $e) {
                $this->rejet($e->getMessage(), $photo->getClientOriginalName());
            }
        }

        session()->flash('success', 'Importation terminée.');

        $this->reset('photos');
        $this->preview = [];
    }

    private function rejet($message, $file)
    {
        $this->rapport['rejetes']++;
        $this->erreurs[] = $message . ' : ' . $file;
    }

    public function render()
    {
        return view('livewire.eleves.import-photos');
    }
}