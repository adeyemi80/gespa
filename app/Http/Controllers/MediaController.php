<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Galerie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Liste des médias (optionnel global)
     */
    public function index()
    {
        $medias = Media::with('galerie')->latest()->paginate(20);
        $galeries = Galerie::with('medias')->orderBy('id')->get();

return view('medias.index', compact('galeries'));
    }

    /**
     * Formulaire ajout média dans une galerie
     */
    public function create(Galerie $galerie)
    {
        return view('medias.create', compact('galerie'));
    }

    /**
     * Enregistrer un média (image ou vidéo)
     */
    public function store(Request $request, Galerie $galerie)
    {
        $request->validate([
            'fichier' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:51200',
            'titre' => 'nullable|string|max:255',
        ]);

        $file = $request->file('fichier');

        // Stockage dans storage/app/public/medias
        $path = $file->store('medias', 'public');

        // Détection type (image ou vidéo)
        $type = str_contains($file->getMimeType(), 'video')
            ? 'video'
            : 'image';

        Media::create([
            'galerie_id' => $galerie->id,
            'fichier' => $path,
            'type' => $type,
            'titre' => $request->titre,
        ]);

        return redirect()
            ->route('galeries.show', $galerie)
            ->with('success', 'Média ajouté avec succès.');
    }

    /**
     * Afficher un média (optionnel)
     */
    public function show(Media $media)
    {
        return view('medias.show', compact('media'));
    }

    /**
     * Formulaire édition
     */
    public function edit(Media $media)
    {
        return view('medias.edit', compact('media'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, Media $media)
    {
        $request->validate([
            'titre' => 'nullable|string|max:255',
        ]);

        $media->update([
            'titre' => $request->titre,
        ]);

        return redirect()
            ->route('galeries.show', $media->galerie_id)
            ->with('success', 'Média mis à jour.');
    }

    /**
     * Suppression du média + fichier physique
     */
    public function destroy(Media $media)
    {
        // supprimer fichier du stockage
        if (Storage::disk('public')->exists($media->fichier)) {
            Storage::disk('public')->delete($media->fichier);
        }

        $galerieId = $media->galerie_id;

        $media->delete();

        return redirect()
            ->route('galeries.show', $galerieId)
            ->with('success', 'Média supprimé.');
    }
}