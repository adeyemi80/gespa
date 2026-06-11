<?php


namespace App\Observers;

use App\Models\User;
use App\Models\Paren;
use Illuminate\Support\Facades\Hash;

class UserObserver
{
    public function creating(User $user)
    {
        
        // Auto-compléter valeurs par défaut pour role=parent
        if ($user->role === 'parent') {
            $user->prenom = $user->prenom ?? 'Parent';
            $user->telephone = $user->telephone ?? '0000000000';
            $user->email_verified_at = $user->email_verified_at ?? now();
            
            // Si pas de password → défaut
            if (empty($user->password)) {
                $user->password = Hash::make('parent123');
            }
        }
    }
    
    public function created(User $user)
    {
        // Auto-créer Paren SI role = 'parent'
        if ($user->role === 'parent') {
            // Vérifier si parent existe déjà
            $existingParen = Paren::where('user_id', $user->id)->first();
            
            if (!$existingParen) {
                Paren::create([
                    'user_id' => $user->id,
                    'nom' => $user->name,
                    'prenom' => $user->prenom,
                    'telephone' => $user->telephone,
                    'email' => $user->email,
                    'password' => $user->password, // Même hash
                    'adresse' => null,
                ]);
            }
        }
    }
    
    public function updating(User $user)
    {
        // Synchroniser changements vers Paren APRÈS sauvegarde
        if ($user->role === 'parent') {
            $paren = Paren::where('user_id', $user->id)->first();
            if ($paren) {
                $updates = [];
                
                // Vérifier changements réels
                $originalEmail = $user->getOriginal('email');
                $originalPassword = $user->getOriginal('password');
                $originalTelephone = $user->getOriginal('telephone');
                $originalPrenom = $user->getOriginal('prenom');
                
                if ($user->email !== $originalEmail) {
                    $updates['email'] = $user->email;
                }
                
                if ($user->password && $user->password !== $originalPassword) {
                    $updates['password'] = $user->password;
                }
                
                if ($user->telephone !== $originalTelephone) {
                    $updates['telephone'] = $user->telephone;
                }
                
                if ($user->prenom !== $originalPrenom) {
                    $updates['prenom'] = $user->prenom;
                }
                
                if (!empty($updates)) {
                    $paren->update($updates);
                }
            }
        }
    }
    
    public function updated(User $user)
    {
        // Synchronisation finale APRÈS mise à jour DB
        if ($user->role === 'parent') {
            $paren = Paren::where('user_id', $user->id)->first();
            if ($paren) {
                $paren->update([
                    'nom' => $user->name,
                    'prenom' => $user->prenom,
                    'telephone' => $user->telephone,
                    'email' => $user->email,
                ]);
            }
        }
    }
}
