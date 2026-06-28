<?php
// app/Livewire/Bulletins/BulletinManager.php

namespace App\Livewire\Bulletins;

use Livewire\Component;
use App\Models\Annee;
use App\Models\Moyenne;
use App\Models\Inscription;
use App\Models\Classe;
use App\Services\BulletinService;
use App\Actions\CalculerBulletinAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class BulletinManager extends Component
{
    public $annee_id = null;
    public $classe_id = null;
    public $trimestre_id = null;
    public array $annees = [];
    public array $classes = [];
    public array $trimestres = [];
    public array $bulletins = [];
    public bool $generatingPdf = false;
    public $bulletinSelectionne = null;
    public $statistiques = [];


    public function mount(): void
    {
        $this->annees = Annee::orderBy('nom')->get()->map(fn ($a) => [
            'id'  => $a->id,
            'nom' => $a->nom,
        ])->toArray();

        $this->classes   = [];
        $this->trimestres = [];
    }


    public function updateFilters($data): void
    {
        $this->annee_id    = $data['anneeId'];
        $this->classe_id   = $data['classeId'];
        $this->trimestre_id = $data['trimestreId'];

        $this->chargerBulletins(app(BulletinService::class));
    }

    public function updatedAnneeId(): void
    {
        if (!$this->annee_id) {
            $this->classes     = [];
            $this->trimestres  = [];
            $this->bulletins   = [];
            $this->statistiques = [];
            $this->classe_id   = null;
            $this->trimestre_id = null;
            return;
        }

        $annee = Annee::with('trimestres')->find($this->annee_id);

        $this->classes = Classe::where('cycle_id', 3)
            ->orderBy('rang')
            ->get()
            ->map(fn ($c) => ['id' => $c->id, 'nom' => $c->nom])
            ->toArray();

        $this->trimestres = $annee?->trimestres?->map(fn ($t) => [
            'id'  => $t->id,
            'nom' => $t->nom,
        ])->toArray() ?? [];

        $this->bulletins    = [];
        $this->statistiques = [];
        $this->classe_id    = null;
        $this->trimestre_id = null;
    }

    public function updatedClasseId(): void
    {
        $this->bulletins    = [];
        $this->statistiques = [];
        if ($this->annee_id && $this->classe_id && $this->trimestre_id) {
            $this->chargerBulletins(app(BulletinService::class));
        }
    }

    public function updatedTrimestreId(): void
    {
        $this->statistiques = [];
        if ($this->annee_id && $this->classe_id && $this->trimestre_id) {
            $this->chargerBulletins(app(BulletinService::class));
        }
    }

    public function chargerBulletins(BulletinService $service): void
{
    if (!$this->annee_id || !$this->classe_id || !$this->trimestre_id) {
        $this->bulletins = [];
        return;
    }

    $action = new CalculerBulletinAction();

    // 1. Recalculer les moyennes (avec bons coefficients + conduite)
    $this->bulletins = $action->getBulletinsClasse(
        $this->classe_id,
        $this->annee_id,
        $this->trimestre_id
    );

    // 2. Recalculer les rangs sur les nouvelles moyennes
    app(\App\Services\MoyenneService::class)
        ->calculerClassementAnnuel($this->annee_id, $this->classe_id);

    // 3. Recharger avec les rangs à jour
    $this->bulletins = $action->getBulletinsClasse(
        $this->classe_id,
        $this->annee_id,
        $this->trimestre_id
    );

    $this->calculerStatistiques();
}

    public function calculerStatistiques(): void
    {
        if (empty($this->bulletins)) {
            $this->statistiques = [];
            return;
        }

        $bulletins = collect($this->bulletins);
        $moyennes  = $bulletins->pluck('moyenne_trimestre')->filter()->map(fn($m) => (float)$m);

        $this->statistiques = [
            'total_eleves'        => $bulletins->count(),
            'moyenne_classe'      => $moyennes->count() > 0 ? round($moyennes->avg(), 2) : 0,
            'meilleure_moyenne'   => $moyennes->count() > 0 ? $moyennes->max() : 0,
            'plus_faible_moyenne' => $moyennes->count() > 0 ? $moyennes->min() : 0,
            'moyenne_mediane'     => $moyennes->count() > 0 ? round($moyennes->median(), 2) : 0,
            'eleves_a_checkpoint' => $bulletins->where('moyenne_trimestre', '<', 10)->count(),
            'eleves_reussis'      => $bulletins->where('moyenne_trimestre', '>=', 10)->count(),
            'taux_reussite'       => $bulletins->count() > 0
                ? round(($bulletins->where('moyenne_trimestre', '>=', 10)->count() / $bulletins->count()) * 100, 2)
                : 0,
        ];

        $this->statistiques['mentions'] = [
            'felicitations'  => $bulletins->where('mention', 'FÉLICITATION')->count(),
            'tableau_honneur'=> $bulletins->where('mention', 'TABLEAU D\'HONNEUR')->count(),
            'encouragement'  => $bulletins->where('mention', 'ENCOURAGEMENT')->count(),
            'avertissement'  => $bulletins->where('mention', 'AVERTISSEMENT')->count(),
            'blame'          => $bulletins->where('mention', 'BLAME')->count(),
        ];
    }

    public function voirBulletin($inscriptionId): void
    {
        $action = new CalculerBulletinAction();
        $this->bulletinSelectionne = $action->execute($inscriptionId, $this->trimestre_id);
    }

    public function fermerBulletin(): void
    {
        $this->bulletinSelectionne = null;
    }

   public function generatePdfClasse()
{
    if (!$this->annee_id || !$this->classe_id || !$this->trimestre_id) {
        session()->flash('error', 'Veuillez sélectionner année, classe et trimestre.');
        return;
    }

    $this->generatingPdf = true;

    $action    = new CalculerBulletinAction();
    $bulletins = $action->getBulletinsClasse(
        $this->classe_id,
        $this->annee_id,
        $this->trimestre_id
    );

    if (empty($bulletins)) {
        session()->flash('error', 'Aucun bulletin trouvé.');
        $this->generatingPdf = false;
        return;
    }

    $classe    = \App\Models\Classe::find($this->classe_id);
    $trimestre = \App\Models\Trimestre::find($this->trimestre_id);

    $nomClasse    = $classe    ? Str::slug($classe->nom,    '_') : $this->classe_id;
    $nomTrimestre = $trimestre ? Str::slug($trimestre->nom, '_') : $this->trimestre_id;

    $afficherTiret = fn($value) => $value !== null && $value !== '' && $value != 0
        ? number_format($value, 2) : '-';

    $pdf = Pdf::loadView('bulletins.pdf_classe', [
        'bulletins'     => collect($bulletins),
        'afficherTiret' => $afficherTiret,
    ])->setPaper('A4', 'portrait');

    $this->generatingPdf = false;

    $nomFichier = "bulletins_{$nomTrimestre}_{$nomClasse}.pdf";

    return response()->streamDownload(
        fn () => print($pdf->output()),
        $nomFichier
    );
}

    public function generatePdfIndividuel($inscriptionId)
    {
        $inscription = Inscription::find($inscriptionId);

        if (!$inscription) {
            session()->flash('error', 'Élève non trouvé.');
            return;
        }

        $inscriptions = Inscription::where('classe_id', $inscription->classe_id)
            ->where('annee_id', $inscription->annee_id)
            ->get();

        $moyennesClasse = Moyenne::whereIn('inscription_id', $inscriptions->pluck('id'))
            ->where('trimestre_id', $this->trimestre_id)
            ->pluck('moyenne_trimestrielle')
            ->filter()
            ->map(fn($m) => (float)$m);

        $statsClasse = [
            'total_eleves'      => $inscriptions->count(),
            'plusFaibleMoyenne' => $moyennesClasse->isNotEmpty() ? $moyennesClasse->min() : null,
            'plusForteMoyenne'  => $moyennesClasse->isNotEmpty() ? $moyennesClasse->max() : null,
        ];

        $action   = new CalculerBulletinAction();
        $bulletin = $action->execute($inscriptionId, $this->trimestre_id, $statsClasse);

        $afficherTiret = fn($value) => $value !== null && $value !== '' && $value != 0
            ? number_format($value, 2) : '-';

        $pdf = Pdf::loadView('bulletins.pdf_classe', [
            'bulletins'     => collect([$bulletin]),
            'afficherTiret' => $afficherTiret,
        ])->setPaper('A4', 'portrait');

        $nom    = $inscription->eleve->nom    ?? 'eleve';
        $prenom = $inscription->eleve->prenom ?? '';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'bulletin_' . $nom . '_' . $prenom . '.pdf'
        );
    }

    public function render()
    {
        return view('livewire.bulletins.bulletin-manager');
    }
}