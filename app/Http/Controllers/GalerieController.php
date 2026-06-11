<?php

namespace App\Http\Controllers;

use App\Models\Galerie;
use Illuminate\Http\Request;

class GalerieController extends Controller
{
    /**
     * Liste des galeries
     */
    public function index()
    {
        $galeries = Galerie::latest()->paginate(12);

        return view('galeries.index', compact('galeries'));
    }

    /**
     * Formulaire création
     */
    public function create()
    {
        return view('galeries.create');
    }

    /**
     * Enregistrer une galerie
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Galerie::create([
            'titre' => $request->titre,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('galeries.index')
            ->with('success', 'Galerie créée avec succès.');
    }

    /**
     * Afficher une galerie
     */
    public function show(Galerie $galerie)
    {
        $medias = $galerie->medias()->latest()->get();

        return view('galeries.show', compact('galerie', 'medias'));
    }

    /**
     * Formulaire modification
     */
    public function edit(Galerie $galerie)
    {
        return view('galeries.edit', compact('galerie'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, Galerie $galerie)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $galerie->update([
            'titre' => $request->titre,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('galeries.index')
            ->with('success', 'Galerie modifiée.');
    }

    /**
     * Suppression
     */
    public function destroy(Galerie $galerie)
    {
        $galerie->delete();

        return redirect()
            ->route('galeries.index')
            ->with('success', 'Galerie supprimée.');
    }
}