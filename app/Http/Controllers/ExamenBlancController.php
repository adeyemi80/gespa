<?php

namespace App\Http\Controllers;

use App\Models\ExamenBlanc;
use App\Models\Classe;
use App\Models\Annee;
use App\Models\Inscription;
use App\Models\ParticipantExamen;
use App\Models\Epreuve;
use App\Models\Matiere;
use App\Models\NoteExamen;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;


class ExamenBlancController extends Controller
{
    /**
     * 📋 Liste des examens
     */
    public function index()
    {
        $examens = ExamenBlanc::with('classes')->latest()->get();
        return view('examens.index', compact('examens'));
    }

    /**
     * ➕ Formulaire création
     */
    public function create()
    {
        $classes = Classe::all();
        $annees  = Annee::all();
        return view('examens.create', compact('classes', 'annees'));
    }

    /**
     * 💾 Enregistrement examen
     */
    public function store(Request $request)
    {
        // 1️⃣ Validation
        $request->validate([
            'type'       => 'required|in:BEPC,BAC-A,BAC-B,BAC-C,BAC-D',
            'annee_id'   => 'required|exists:annees,id',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after_or_equal:date_debut',
        ]);

        DB::beginTransaction();

        try {
            // 2️⃣ Création examen
            $examen = ExamenBlanc::create([
                'type'       => $request->type,
                'annee_id'   => $request->annee_id,
                'date_debut' => $request->date_debut,
                'date_fin'   => $request->date_fin,
            ]);

            // 3️⃣ Classes selon type
            $classes = match ($examen->type) {
                'BEPC'  => Classe::where('niveau', '3eme')->get(),
                'BAC-A' => Classe::where('niveau', 'TleA')->get(),
                'BAC-B' => Classe::where('niveau', 'TleB')->get(),
                'BAC-C' => Classe::where('niveau', 'TleC')->get(),
                'BAC-D' => Classe::where('niveau', 'TleD')->get(),
                default => collect(),
            };

            if ($classes->isEmpty()) {
                throw new \Exception("Aucune classe trouvée pour ce type d'examen.");
            }

            // 4️⃣ Sync pivot
            $examen->classes()->sync($classes->pluck('id')->toArray());

            // 5️⃣ Calcul du numéro de table (basé sur l'examen en cours uniquement)
            $now   = Carbon::now();
            $year  = $now->format('Y');
            $month = $now->format('m');
            $numero = 1;

            // 6️⃣ Génération participants — filtrés par annee_id ✅
            foreach ($classes as $classe) {

                $inscriptions = Inscription::where('classe_id', $classe->id)
                                           ->where('annee_id', $examen->annee_id) // ✅ filtre par année
                                           ->get();

                foreach ($inscriptions as $inscription) {

                    $num_table = sprintf("EB%s%s%03d", $year, $month, $numero);

                    ParticipantExamen::firstOrCreate(
                        [
                            'examen_blanc_id' => $examen->id,
                            'inscription_id'  => $inscription->id,
                        ],
                        [
                            'numero_table' => $num_table,
                        ]
                    );

                    $numero++; // ✅ incrément global
                }
            }

            DB::commit();

            return redirect()
                ->route('examens-blancs.create', $examen->id)
                ->with('success', 'Examen créé avec classes et participants !');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * 👁️ Voir un examen
     */
    public function show($id)
    {
        $examen = ExamenBlanc::with([
            'classes',
            'participants.inscription.eleve',
            'participants.inscription.classe',
            'epreuves.matiere',
        ])->findOrFail($id);

        return view('examens.show', compact('examen'));
    }

    /**
     * ✏️ Formulaire édition
     */
    public function edit($id)
    {
        $examen  = ExamenBlanc::findOrFail($id);
        $classes = Classe::all();

        return view('examens.edit', compact('examen', 'classes'));
    }

    /**
     * 🔄 Mise à jour
     */
    public function update(Request $request, $id)
    {
        $examen = ExamenBlanc::findOrFail($id);

        $request->validate([
            'type'       => 'required|in:BEPC,BAC-A,BAC-B,BAC-C,BAC-D',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after_or_equal:date_debut',
        ]);

        $examen->update($request->only(['type', 'date_debut', 'date_fin']));
        $examen->classes()->sync($request->classes);

        return redirect()->route('examens-blancs.create', $id)
            ->with('success', 'Examen mis à jour');
    }

    /**
     * ❌ Suppression
     */
    public function destroy($id)
    {
        $examen = ExamenBlanc::findOrFail($id);
        $examen->delete();

        return redirect()->route('examens-blancs.index')
            ->with('success', 'Examen supprimé');
    }

    /**
     * ⚙️ Générer participants automatiquement
     */
    public function genererParticipants($id)
    {
        $examen = ExamenBlanc::with('classes')->findOrFail($id);

        $now   = Carbon::now();
        $year  = $now->format('Y');
        $month = $now->format('m');

        // Récupérer le dernier numéro attribué pour cet examen
        $lastParticipant = ParticipantExamen::where('examen_blanc_id', $examen->id)
            ->orderByDesc('numero_table')
            ->first();

        $numero = $lastParticipant
            ? ((int) substr($lastParticipant->numero_table, -3)) + 1
            : 1;

        foreach ($examen->classes as $classe) {

            $inscriptions = Inscription::where('classe_id', $classe->id)
                                       ->where('annee_id', $examen->annee_id) // ✅ filtre par année
                                       ->get();

            foreach ($inscriptions as $inscription) {

                $num_table = sprintf("EB%s%s%03d", $year, $month, $numero);

                $created = ParticipantExamen::firstOrCreate(
                    [
                        'examen_blanc_id' => $examen->id,
                        'inscription_id'  => $inscription->id,
                    ],
                    [
                        'numero_table' => $num_table,
                    ]
                );

                // N'incrémenter que si un nouveau participant a été créé
                if ($created->wasRecentlyCreated) {
                    $numero++;
                }
            }
        }

        return back()->with('success', 'Participants générés avec succès');
    }

    /**
     * 🧪 Générer épreuves automatiquement
     */
    public function genererEpreuves($id)
    {
        $examen = ExamenBlanc::with('classes')->findOrFail($id);

        foreach ($examen->classes as $classe) {

            $matieres = Matiere::where('classe_id', $classe->id)->get();

            foreach ($matieres as $matiere) {

                Epreuve::firstOrCreate(
                    [
                        'examen_blanc_id' => $examen->id,
                        'matiere_id'      => $matiere->id,
                    ],
                    [
                        'date'        => now(),
                        'heure_debut' => '08:00',
                        'heure_fin'   => '10:00',
                    ]
                );
            }
        }

        return back()->with('success', 'Épreuves générées');
    }

    /**
     * 📝 Saisie des notes
     */
    public function saisirNotes($id)
    {
        $examen = ExamenBlanc::with([
            'participants.inscription.eleve',
            'epreuves.matiere',
        ])->findOrFail($id);

        return view('examens.notes', compact('examen'));
    }

    /**
     * 💾 Enregistrer notes
     */
    public function enregistrerNotes(Request $request)
    {
        foreach ($request->notes as $participant_id => $epreuves) {
            foreach ($epreuves as $epreuve_id => $note) {
                NoteExamen::updateOrCreate(
                    [
                        'participant_id' => $participant_id,
                        'epreuve_id'     => $epreuve_id,
                    ],
                    [
                        'note' => $note,
                    ]
                );
            }
        }

        return back()->with('success', 'Notes enregistrées');
    }

    /**
     * 📊 Moyenne d'un participant
     */
    public function moyenneParticipant($participant_id)
    {
        $notes = NoteExamen::where('participant_id', $participant_id)
            ->whereNotNull('note')
            ->pluck('note');

        if ($notes->isEmpty()) return null;

        return round($notes->avg(), 2);
    }

    /**
     * 📄 Export PDF participants
     */
    public function exportPdf($id)
    {
        $examen = ExamenBlanc::with([
            'participants.inscription.eleve',
            'participants.inscription.classe',
        ])->findOrFail($id);

        $pdf = Pdf::loadView('examens.pdf.participants', compact('examen'));

        return $pdf->download('participants.pdf');
    }

    /**
     * 🏆 Classement PDF
     */
    public function classement($id)
    {
        $examen = ExamenBlanc::with([
            'participants.inscription.eleve',
            'participants.inscription.classe',
        ])->findOrFail($id);

        // Tri par moyenne décroissante
        $participants = $examen->participants->sortByDesc('moyenne')->values();

        // Statistiques
        $notes = $participants->pluck('moyenne')->filter();
        $stats = [
            'min'              => $notes->min(),
            'max'              => $notes->max(),
            'moyenne_generale' => $notes->avg(),
        ];

        // Top 3
        $top3 = $participants->take(3);

        // Taux de réussite
        $total          = $participants->count();
        $admis          = $participants->filter(fn($p) => $p->moyenne >= 10)->count();
        $taux_reussite  = $total > 0 ? round(($admis / $total) * 100, 2) : 0;

        $pdf = Pdf::loadView('examens.classement', compact('examen', 'participants', 'stats', 'top3', 'taux_reussite'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('classement_' . $examen->type . '.pdf');
    }

    /**
     * 📋 Notes PDF
     */
    public function notesPdf($id)
    {
        $examen = ExamenBlanc::with([
            'participants.inscription.eleve',
            'participants.inscription.classe.matieres',
        ])->findOrFail($id);

        $matieres = $examen->classes->flatMap(function ($classe) {
            return $classe->matieres;
        })->unique('id');

        $pdf = Pdf::loadView('examens.pdf.notes', compact('examen', 'matieres'))
                  ->setPaper('A4', 'landscape');

        return $pdf->download('notes_' . $examen->type . '.pdf');
    }
}