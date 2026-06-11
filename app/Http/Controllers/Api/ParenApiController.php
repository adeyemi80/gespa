<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paren;
use App\Models\Trimestre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class ParenApiController extends Controller
{
    /**
     * Liste des parents
     */
    public function index()
    {
        $parens = Paren::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $parens
        ]);
    }

    /**
     * Dashboard parent connecté
     */
    public function dashboard()
    {
        $paren = Auth::user();

        $eleves = $paren->eleves()->with([
            'notes.matiere',
            'conduites',
            'moyennes'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $eleves
        ]);
    }

    /**
     * Données nécessaires au formulaire
     */
    public function create()
    {
        $trimestres = Trimestre::all();

        return response()->json([
            'success' => true,
            'trimestres' => $trimestres
        ]);
    }

    /**
     * Règles mot de passe
     */
    private function passwordRules()
    {
        return [
            'required',
            'string',
            'min:8',
            Password::defaults(),
            'confirmed'
        ];
    }

    /**
     * Création parent
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'                => 'required|string|max:255',
            'prenom'             => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email',
            'telephone_parent'   => 'nullable|string|max:20',
            'adresse_parent'     => 'nullable|string|max:255',
            'password'           => $this->passwordRules(),
        ]);

        $hashedPassword = Hash::make($validated['password']);

        // Création user
        $user = User::create([
            'nom'      => $validated['nom'],
            'prenom'   => $validated['prenom'],
            'email'    => $validated['email'],
            'password' => $hashedPassword,
            'role'     => 'parent',
        ]);

        // Création parent
        $paren = Paren::create([
            'nom_parent'       => $validated['nom'],
            'prenom_parent'    => $validated['prenom'],
            'telephone_parent' => $validated['telephone_parent'] ?? null,
            'adresse_parent'   => $validated['adresse_parent'] ?? null,
            'email'            => $validated['email'],
            'password'         => $hashedPassword,
            'user_id'          => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Parent créé avec succès.',
            'data' => $paren
        ], 201);
    }

    /**
     * Afficher un parent
     */
    public function show(Paren $paren)
    {
        return response()->json([
            'success' => true,
            'data' => $paren
        ]);
    }

    /**
     * Formulaire édition
     */
    public function edit(Paren $paren)
    {
        return response()->json([
            'success' => true,
            'data' => $paren
        ]);
    }

    /**
     * Mise à jour parent
     */
    public function update(Request $request, Paren $paren)
    {
        $rules = [
            'nom_parent'       => 'required|string|max:255',
            'prenom_parent'    => 'required|string|max:255',
            'telephone_parent' => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:255',
            'adresse_parent'   => 'nullable|string|max:255',
        ];

        // Password optionnel
        if ($request->filled('password')) {
            $rules['password'] = $this->passwordRules();
        }

        $validated = $request->validate($rules);

        // Mise à jour password
        if ($request->filled('password')) {

            $hashedPassword = Hash::make($validated['password']);

            // User lié
            if ($paren->user_id && $paren->user) {
                $paren->user->update([
                    'password' => $hashedPassword
                ]);
            }

            $validated['password'] = $hashedPassword;
        }

        $paren->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Parent mis à jour avec succès.',
            'data' => $paren
        ]);
    }

    /**
     * Suppression
     */
    public function destroy(Paren $paren)
    {
        // Supprimer user lié
        if ($paren->user) {
            $paren->user->delete();
        }

        $paren->delete();

        return response()->json([
            'success' => true,
            'message' => 'Parent supprimé avec succès.'
        ]);
    }
}