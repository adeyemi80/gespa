<?php
namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Imports\EleveImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class EleveImportController extends Controller
{
    /**
     * Formulaire d'import avec années et classes
     */
    public function form()
    {
        //$annees = Annee::with('classesActives')->orderBy('id', 'desc')->get();
        $annees = Annee::orderBy('nom', 'desc')->get();
        $cycles = Cycle::all();
        $classes = Classe::orderByNiveau()->get();
       $anneeEnCours = Annee::where('en_cours', true)->first();
// ou
// $anneeEnCours = Annee::where('en_cours', 1)->first();

        return view('eleves.import', compact('annees','anneeEnCours', 'cycles'));
    }

    /**
     * Téléchargement du modèle Excel filtré par année
     */
    public function telechargerModeleImport(Request $request)
{
    $request->validate([
        'annee_id' => 'required|exists:annees,id',
        'cycle_id' => 'required|exists:cycles,id'
    ]);

    $annee_id = $request->annee_id;
    $cycle_id = $request->cycle_id;

    $annee = Annee::findOrFail($annee_id);
    $cycle = Cycle::findOrFail($cycle_id);

    $filename = 'modele_eleves_' 
        . str_replace(' ', '_', strtolower($cycle->nom)) . '_'
        . str_replace(' ', '_', strtolower($annee->nom)) 
        . '.xlsx';

    return Excel::download(
        new \App\Exports\ModeleEleveExport($annee_id, $cycle_id),
        $filename
    );
}
    /**
     * Prévisualisation avant import réel (AVEC infos parent)
     */
    public function previsualiser(Request $request)
    { 
        $request->validate([
            'fichier'   => 'required|file|mimes:csv,xlsx,xls|max:5120', // 5MB
            'classe_id' => 'required|exists:classes,id',
            'annee_id'  => 'required|exists:annees,id',
        ]);

        // 🔐 Sécurité : classe ∈ année
        if (!$this->classeAppartientAnnee($request->classe_id, $request->annee_id)) {
            return back()->withErrors([
                'classe_id' => 'Cette classe n\'est pas attachée à l\'année sélectionnée.'
            ]);
        }

        // 📦 Stockage temporaire
        $filePath = $request->file('fichier')->storeAs(
            'temp',
            'preview_' . uniqid() . '.' . $request->file('fichier')->getClientOriginalExtension()
        );

        // 💾 Contexte session
        session([
            'fichier_temp' => $filePath,
            'classe_id'    => $request->classe_id,
            'annee_id'     => $request->annee_id,
        ]);

        // 🧪 PREVIEW (pas d'insertion DB)
        $import = new EleveImport(
            $request->classe_id,
            $request->annee_id,
            false  // Mode preview
        );

        Excel::import($import, $filePath);

        return view('eleves.previsualisation', [
            'valides' => $import->valides ?? [],
            'erreurs' => $import->erreurs ?? [],
            'classe'  => Classe::find($request->classe_id),
            'annee'   => Annee::find($request->annee_id),
            'stats'   => [
                'valides' => count($import->valides ?? []),
                'erreurs' => count($import->erreurs ?? [])
            ]
        ]);
    }

    /**
     * Validation finale et insertion réelle (User/Paren AUTO)
     */
    public function validerImport(Request $request)
    {
        $filePath = session('fichier_temp');
        $classeId = session('classe_id');
        $anneeId  = session('annee_id');

        if (!$filePath || !$classeId || !$anneeId) {
            return redirect()->route('eleves.import')
                ->with('error', 'Session d\'importation expirée.');
        }

        if (!$this->classeAppartientAnnee($classeId, $anneeId)) {
            return redirect()->route('eleves.import')
                ->with('error', 'Classe/Année invalide.');
        }

        // 🟢 IMPORT RÉEL → Observer crée User/Paren AUTO !
        $import = new EleveImport($classeId, $anneeId, true); // Mode réel
        Excel::import($import, $filePath);

        // 📊 Statistiques
        $nbValides = count($import->valides ?? []);
        $nbErreurs = count($import->erreurs ?? []);

        // 🧹 Nettoyage
        Storage::delete($filePath);
        session()->forget(['fichier_temp', 'classe_id', 'annee_id']);

        return redirect()->route('eleves.import')
            ->with('success', "✅ Import réussi ! {$nbValides} élèves créés, {$nbErreurs} erreurs.");
    }

    /**
     * Télécharge les erreurs Excel
     */
    public function telechargerErreurs()
    {
        $erreurs = session('import_erreurs', []);
        if (empty($erreurs)) {
            return redirect()->route('eleves.import')->with('error', 'Aucune erreur à exporter.');
        }

        return Excel::download(
            new \App\Exports\ErreursEleveExport($erreurs),
            'erreurs_import_eleves.xlsx'
        );
    }

    /**
     * 🔐 Vérifie classe → année active
     */
    private function classeAppartientAnnee(int $classeId, int $anneeId): bool
    {
        return Classe::where('id', $classeId)
            ->whereHas('annees', function ($q) use ($anneeId) {
                $q->where('annee_id', $anneeId)
                  ->where('annee_classe.active', true);
            })
            ->exists();
    }
}
