<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class OperationController extends Controller
{
    public function index()
{
    //$operations = Operation::latest()->get();
    $operations = Operation::latest()->paginate(100); // ✅ paginate() retourne un LengthAwarePaginator
    $recettes = Operation::where('categorie', 'recette')->sum('montant');
    $depenses = Operation::where('categorie', 'dépense')->sum('montant');
    $solde = $recettes - $depenses;

    return view('operations.index', compact('operations', 'recettes', 'depenses', 'solde'));
}

    public function create()
    {
        return view('operations.create');
    }

    public function show(Operation $operation)
{
    return view('operations.show', compact('operation'));
}

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'categorie' => 'required|in:recette,dépense',
            'description' => 'nullable|string',
        ]);

        Operation::create($request->all());

        return redirect()->route('operations.create')
                         ->with('success', 'Opération enregistrée avec succès.');
    }

    public function edit(Operation $operation)
    {
        return view('operations.edit', compact('operation'));
    }

    public function update(Request $request, Operation $operation)
    {
        $request->validate([
            'date' => 'required|date',
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'categorie' => 'required|in:recette,dépense',
            'description' => 'nullable|string',
        ]);

        $operation->update($request->all());

        return redirect()->route('operations.index')
                         ->with('success', 'Opération mise à jour avec succès.');
    }

    public function destroy(Operation $operation)
    {
        $operation->delete();

        return redirect()->route('operations.index')
                         ->with('success', 'Opération supprimée avec succès.');
    }
    
    /**
     * Génère le rapport filtré par libellé et période.
     */
    
    /**
     * Export du rapport filtré en PDF.
     */
   
    
    // Formulaire de filtre
    public function rapportForm(Request $request)
{
    $libelles = Operation::select('libelle')->distinct()->pluck('libelle');
    $categories = Operation::select('categorie')->distinct()->pluck('categorie');
    $date_debut = $request->input('date_debut'); 
    $date_fin   = $request->input('date_fin');

    return view('operations.rapport', compact('libelles', 'categories', 'date_debut', 'date_fin'));
}

    // Générer le rapport filtré
    public function rapportGenerer(Request $request)
    {
        $query = Operation::query();

        // Filtres
        if ($request->filled('libelle')) {
            $query->where('libelle', $request->libelle);
        }
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('date', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date', '<=', $request->date_fin);
        }

        $operations = $query->orderBy('date', 'asc')->get();

        // Calculs
        $recettes = $operations->where('categorie', 'recette')->sum('montant');
        $depenses = $operations->where('categorie', 'dépense')->sum('montant');
        $solde = $recettes - $depenses;

        $libelles = Operation::select('libelle')->distinct()->pluck('libelle');
        $categories = Operation::select('categorie')->distinct()->pluck('categorie');

        return view('operations.rapport', compact('operations', 'recettes', 'depenses', 'solde', 'libelles', 'categories'));
    }

    // Export PDF
   
public function rapportPdf(Request $request)
{
    $query = Operation::query();

    if ($request->filled('libelle')) {
        $query->where('libelle', $request->libelle);
    }
    if ($request->filled('categorie')) {
        $query->where('categorie', $request->categorie);
    }
    if ($request->filled('date_debut')) {
        $query->whereDate('date', '>=', $request->date_debut);
    }
    if ($request->filled('date_fin')) {
        $query->whereDate('date', '<=', $request->date_fin);
    }

    $operations = $query->orderBy('date', 'asc')->get();

    $recettes = $operations->where('categorie', 'recette')->sum('montant');
    $depenses = $operations->where('categorie', 'dépense')->sum('montant');
    $solde = $recettes - $depenses;

    // ✅ Définir les variables avant compact()
    $date_debut = $request->input('date_debut');
    $date_fin   = $request->input('date_fin');

    $pdf = PDF::loadView('operations.rapport_pdf',
        compact('operations','recettes','depenses','solde','date_debut','date_fin')
    )->setPaper('a4', 'portrait');

    return $pdf->download('operations.rapport_pdf');
}



}
