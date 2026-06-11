<?php

namespace App\Http\Controllers;

use App\Models\Paren;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use App\Services\MoyenneService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class ParenController extends Controller
{
    /**
     * Affiche la liste des parents.
     */
    public function index()
    {
        $parens = Paren::all();
        return view('parens.index', compact('parens'));
    }

    public function dashboard()
    {
        $paren = Auth::user(); // Parent connecté
        // On récupère les élèves avec notes, conduites et moyennes
        $eleves = $paren->eleves()->with([
            'notes.matiere',
            'conduites',
            'moyennes'
        ])->get();

        return view('parent.dashboard', compact('eleves'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        $trimestres = Trimestre::all();

return view('parens.create', compact('trimestres'));

    }

    /**
     * Enregistre un nouveau parent.
     */

private function passwordRules()
{
    return [
        'required',
        'string',
        'min:8',
        Password::defaults(), // Règles Laravel par défaut (majuscule, chiffre, etc.)
        'confirmed'
    ];
}

    public function store(Request $request)
{
    $validated = $request->validate([
        // ... autres règles
        'password' => $this->passwordRules(),
    ]);

    $hashedPassword = Hash::make($validated['password']);
    
    // Créer User d'abord
    $user = User::create([
        'nom' => $validated['nom'],
        'prenom' => $validated['prenom'],
        'email' => $validated['email'],
        'password' => $hashedPassword,
        'role' => 'parent',
    ]);

    // Créer Paren lié
    $validated['password'] = $hashedPassword;
    $validated['user_id'] = $user->id;
    
    Paren::create($validated);

    return redirect()->route('parens.index')->with('success', 'Parent ajouté avec succès.');
}


    /**
     * Affiche un parent spécifique.
     */
    public function show(Paren $paren)
    {
        return view('parens.show', compact('paren'));
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(Paren $paren)
    {
        return view('parens.edit', compact('paren'));
    }

    /**
     * Met à jour un parent.
     */
    public function update(Request $request, Paren $paren)
{
    $rules = [
        'nom_parent' => 'required|string|max:255',
        'prenom_parent' => 'required|string|max:255',
        'telephone_parent' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'adresse_parent' => 'nullable|string|max:255',
    ];

    // Password optionnel
    if ($request->filled('password')) {
        $rules['password'] = $this->passwordRules(); // 'confirmed|min:8'
    }

    $validated = $request->validate($rules);

    // Hacher le mot de passe s'il est fourni
    if (isset($validated['password'])) {
        $hashedPassword = Hash::make($validated['password']);
        
        // 1. Mettre à jour le User lié
        if ($paren->user_id && $paren->user) {
            $paren->user->update(['password' => $hashedPassword]);
        }
        
        // 2. Mettre à jour le Paren
        $validated['password'] = $hashedPassword;
    }

    // Supprimer password des règles si pas fourni (pour ne pas écraser)
    unset($validated['password']);

    $paren->update($validated);

    return redirect()->route('parens.index')->with('success', 'Parent mis à jour avec succès.');
}

    /**
     * Supprime un parent.
     */
    public function destroy(Paren $paren)
    {
        $paren->delete();
        return redirect()->route('parens.index')->with('success', 'Parent supprimé avec succès.');
    }
}
