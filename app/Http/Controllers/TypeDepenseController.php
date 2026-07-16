<?php

namespace App\Http\Controllers;

use App\Models\CategorieDepense;
use App\Models\TypeDepense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TypeDepenseController extends Controller
{
    public function index(Request $request): View
    {
        $types = TypeDepense::query()
            ->with('categorie')
            ->when($request->filled('recherche'), function ($query) use ($request) {
                $query->where('nom', 'ILIKE', '%' . $request->recherche . '%')
                    ->orWhere('code', 'ILIKE', '%' . $request->recherche . '%');
            })
            ->when($request->filled('categorie_id'), function ($query) use ($request) {
                $query->where('categorie_id', $request->categorie_id);
            })
            ->orderBy('nom')
            ->paginate(20000)
            ->withQueryString();

        $categories = CategorieDepense::actif()->orderBy('nom')->get();

        return view('depenses.types.index', compact('types', 'categories'));
    }

    public function create(): View
    {
        $categories = CategorieDepense::actif()->orderBy('nom')->get();

        return view('depenses.types.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $valide = $this->validerDonnees($request);

        TypeDepense::create($valide);

        return redirect()
            ->route('types-depenses.create')
            ->with('succes', 'Type de dépense créé avec succès.');
    }

    public function show(TypeDepense $typesDepense): View
    {
        $typesDepense->load('categorie', 'depenses');

        return view('depenses.types.show', ['type' => $typesDepense]);
    }

    public function edit(TypeDepense $typesDepense): View
    {
        $categories = CategorieDepense::actif()->orderBy('nom')->get();

        return view('depenses.types.edit', ['type' => $typesDepense, 'categories' => $categories]);
    }

    public function update(Request $request, TypeDepense $typesDepense): RedirectResponse
    {
        $valide = $this->validerDonnees($request, $typesDepense->id);

        $typesDepense->update($valide);

        return redirect()
            ->route('types-depenses.index')
            ->with('succes', 'Type de dépense mis à jour avec succès.');
    }

    public function destroy(TypeDepense $typesDepense): RedirectResponse
    {
        if ($typesDepense->depenses()->exists()) {
            return redirect()
                ->route('types-depenses.index')
                ->with('erreur', 'Impossible de supprimer : des dépenses sont rattachées à ce type.');
        }

        $typesDepense->delete();

        return redirect()
            ->route('types-depenses.index')
            ->with('succes', 'Type de dépense supprimé avec succès.');
    }

    protected function validerDonnees(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'categorie_id' => 'required|exists:categories_depenses,id',
            //'code' => 'required|string|max:15|unique:types_depenses,code' . ($id ? ",$id" : ''),
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'actif' => 'boolean',
        ]);
    }
}