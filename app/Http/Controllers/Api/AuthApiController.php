<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthApiController extends Controller
{
    /**
     * LOGIN
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        // créer token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
    }

    /**
     * USER CONNECTÉ
     */
    public function me(Request $request)
{
    return response()->json([
        'id' => $request->user()->id,
        'nom' => $request->user()->nom,
        'prenom' => $request->user()->prenom,
        'email' => $request->user()->email,
        'role' => $request->user()->role,
        'photo' => $request->user()->photo,
    ]);
}
    /*public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }*/

    public function user(Request $request)
{
    return response()->json($request->user());
}




}