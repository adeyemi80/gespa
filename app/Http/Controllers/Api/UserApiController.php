<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserApiController extends Controller
{
    // Liste tous les utilisateurs
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'ilike', '%' . $request->search . '%')
                  ->orWhere('prenom', 'ilike', '%' . $request->search . '%')
                  ->orWhere('email', 'ilike', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('nom')
            ->paginate(20);

        return response()->json($users);
    }

    // Détail d'un utilisateur
    public function show(User $user)
    {
        return response()->json($user);
    }

    // Créer un utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'nom'      => 'required|string|max:100',
            'prenom'   => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,directeur,enseignant,parent,comptable,surveillant,secretaire,censeur',
            'telephone'=> 'nullable|string|max:20',
        ]);

        $user = User::create([
            'nom'       => strtoupper($request->nom),
            'prenom'    => $request->prenom,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'telephone' => $request->telephone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur créé avec succès',
            'user'    => $user,
        ], 201);
    }

    // Modifier un utilisateur
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nom'      => 'required|string|max:100',
            'prenom'   => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,directeur,enseignant,parent,comptable,surveillant,secretaire,censeur',
            'telephone'=> 'nullable|string|max:20',
        ]);

        $user->update([
            'nom'       => strtoupper($request->nom),
            'prenom'    => $request->prenom,
            'email'     => $request->email,
            'role'      => $request->role,
            'telephone' => $request->telephone,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur modifié avec succès',
            'user'    => $user,
        ]);
    }

    // Supprimer un utilisateur
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas supprimer votre propre compte',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès',
        ]);
    }

    // Réinitialiser le mot de passe
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:6',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe réinitialisé avec succès',
        ]);
    }
}