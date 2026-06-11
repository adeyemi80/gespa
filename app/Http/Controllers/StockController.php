<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Article;
use App\Models\Type;
use App\Models\MouvementStock;

class StockController extends Controller
{
    /**
     * Liste tous les articles avec leurs types.
     */
    public function index()
    {
        $articles = Article::with('type')->get();
        return view('articles.index', compact('articles'));
    }

    /**
     * Formulaire pour créer un nouvel article.
     */
    public function create()
    {
        $types = Type::all();
        return view('articles.create', compact('types'));
    }

    /**
     * Enregistrement d'un nouvel article avec stock initial.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'reference' => 'required|unique:articles,reference',
            'type_id' => 'required|exists:types,id',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'stock_min' => 'nullable|integer|min:0',
            'quantite' => 'required|integer|min:0',
            'type_mouvement' => 'required|in:entree,sortie'
        ]);

        DB::transaction(function () use ($request) {

            // Création de l'article
            $article = Article::create($request->only(
                'nom','reference','type_id',
                'prix_achat','prix_vente','stock_min','description'
            ));

            // Stock initial
            MouvementStock::create([
                'article_id' => $article->id,
                'type' => $request->type_mouvement,
                'quantite' => $request->quantite,
                'prix_unitaire' => $request->prix_achat,
                'motif' => 'Stock initial'
            ]);
        });

        return redirect()->route('articles.index')
            ->with('success', '✅ Article ajouté avec stock initial');
    }

    /**
     * Affiche les détails d'un article avec ses mouvements.
     */
    public function show(string $id)
    {
        $article = Article::with(['type', 'mouvements'])->findOrFail($id);
        return view('articles.show', compact('article'));
    }

    /**
     * Formulaire d'édition d'un article.
     */
    public function edit(string $id)
    {
        $article = Article::findOrFail($id);
        $types = Type::all();
        return view('articles.edit', compact('article', 'types'));
    }

    /**
     * Mise à jour d'un article existant.
     */
    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'reference' => 'required|unique:articles,reference,' . $article->id,
            'type_id' => 'required|exists:types,id',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'stock_min' => 'nullable|integer|min:0',
        ]);

        $article->update($request->only(
            'nom','reference','type_id',
            'prix_achat','prix_vente','stock_min','description'
        ));

        return redirect()->route('articles.index')
            ->with('success', '✅ Article mis à jour avec succès');
    }

    /**
     * Supprime un article.
     */
    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);

        // Supprimer les mouvements liés
        $article->mouvements()->delete();

        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', '✅ Article supprimé avec succès');
    }
}
