<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\PieceJustificative;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PieceJustificativeController extends Controller
{
    /**
     * Liste des pièces justificatives rattachées à une dépense.
     */
    public function index(Depense $depense): View
    {
        $depense->load('piecesJustificatives');

        return view('depenses.pieces-justificatives.index', compact('depense'));
    }

    /**
     * Ajout d'une ou plusieurs pièces justificatives à une dépense existante.
     */
    public function store(Request $request, Depense $depense): RedirectResponse
    {
        $request->validate([
            'fichiers' => 'required|array',
            'fichiers.*' => 'file|max:5120|mimes:pdf,jpg,jpeg,png',
        ], [
            'fichiers.*.mimes' => 'Les pièces justificatives doivent être au format PDF, JPG ou PNG.',
            'fichiers.*.max' => 'Chaque fichier ne doit pas dépasser 5 Mo.',
        ]);

        foreach ($request->file('fichiers') as $fichier) {
            $chemin = $fichier->store('pieces-justificatives/' . $depense->id, 'public');

            PieceJustificative::create([
                'depense_id' => $depense->id,
                'nom_fichier' => $fichier->getClientOriginalName(),
                'chemin_fichier' => $chemin,
                'type_mime' => $fichier->getMimeType(),
                'taille' => $fichier->getSize(),
            ]);
        }

        return redirect()
            ->route('pieces-justificatives.index', $depense)
            ->with('succes', 'Pièce(s) justificative(s) ajoutée(s) avec succès.');
    }

    /**
     * Aperçu du fichier dans le navigateur (affichage inline pour PDF/images).
     */
    public function apercu(PieceJustificative $pieceJustificative): Response
    {
        if (! Storage::disk('public')->exists($pieceJustificative->chemin_fichier)) {
            abort(404, 'Fichier introuvable.');
        }

        $contenu = Storage::disk('public')->get($pieceJustificative->chemin_fichier);

        return response($contenu, 200, [
            'Content-Type' => $pieceJustificative->type_mime ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . $pieceJustificative->nom_fichier . '"',
        ]);
    }

    /**
     * Téléchargement forcé du fichier.
     */
    public function telecharger(PieceJustificative $pieceJustificative): StreamedResponse
    {
        if (! Storage::disk('public')->exists($pieceJustificative->chemin_fichier)) {
            abort(404, 'Fichier introuvable.');
        }

        return Storage::disk('public')->download(
            $pieceJustificative->chemin_fichier,
            $pieceJustificative->nom_fichier
        );
    }

    /**
     * Suppression d'une pièce justificative (fichier + enregistrement).
     */
    public function destroy(PieceJustificative $pieceJustificative): RedirectResponse
    {
        $depense = $pieceJustificative->depense;

        Storage::disk('public')->delete($pieceJustificative->chemin_fichier);
        $pieceJustificative->delete();

        return redirect()
            ->route('pieces-justificatives.index', $depense)
            ->with('succes', 'Pièce justificative supprimée avec succès.');
    }
}