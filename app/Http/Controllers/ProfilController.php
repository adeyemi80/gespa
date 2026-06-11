<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    /**
     * Liste des utilisateurs (réservé admin)
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        $users = User::latest()->paginate(1000000);
        return view('profil.index', compact('users'));
    }

    /**
     * Formulaire de création (admin)
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        return view('profil.create');
    }

    /**
     * Enregistrer un utilisateur (admin)
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'role' => 'required|in:admin,secretaire,comptable,enseignant,directeur,censeur,surveillant,parent',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = new User();
        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'] ?? null;
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->password = bcrypt($validated['password']);

        // Upload photo
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users', 'public');
            $user->photo = $path;
        }

        $user->save();

        return redirect()->route('profil.index')->with('success', 'Utilisateur créé avec succès ✅');
    }

    /**
     * Afficher SON profil
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        return view('profil.show', compact('user'));
    }

    /**
     * Formulaire édition profil
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        return view('profil.edit', compact('user'));
    }

    /**
     * Mise à jour profil
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
    'nom' => 'required|string|max:255',
    'prenom' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,

    'role' => 'nullable|in:admin,secretaire,comptable,enseignant,directeur,censeur,surveillant,parent',

    'password' => 'nullable|string|min:8|confirmed',
    'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
]);

        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->email = $validated['email'];

        // 🔐 Seul admin peut modifier le rôle
       if (Auth::user()->role === 'admin' && isset($validated['role'])) {
    $user->role = $validated['role'];
}

        // Mot de passe (optionnel)
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        // Gestion photo
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $path = $request->file('photo')->store('users', 'public');
            $user->photo = $path;
        }

        $user->save();

        return redirect()->route('tableau.accueil')->with('success', 'Votre Profil a été mis à jour avec succès ✅');
    }

    /**
     * Supprimer utilisateur (admin ou lui-même)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            abort(403, 'Suppression non autorisée.');
        }

        // Supprimer photo
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('profil.index')->with('success', 'Utilisateur supprimé avec succès 🗑️');
    }
}