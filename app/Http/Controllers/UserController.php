<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(100000);
        return view('users.index', compact('users'));
    }

    public function create(User $user)
    {
        return view('users.create', compact('user'));
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // =========================
    // CREATE USER
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'nom'      => 'required',
            'prenom'   => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'role'     => 'required|in:admin,parent,enseignant,surveillant,secretaire,comptable,directeur,censeur',
        ]);

        User::create([
            'nom'      => $request->nom,
            'prenom'   => $request->prenom,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()
            ->route('users.create')
            ->with('success', 'Utilisateur créé avec succès');
    }

    // =========================
    // UPDATE USER
    // =========================
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'nullable|string|max:255',
            'role'     => 'required|in:admin,parent,enseignant,surveillant,secretaire,comptable,directeur,censeur',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->nom    = $request->nom;
        $user->prenom = $request->prenom;
        $user->email  = $request->email;
        $user->role   = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès');
    }

    // =========================
    // DELETE USER
    // =========================
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }
}