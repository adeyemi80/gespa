<?php
// app/Observers/EleveObserver.php
namespace App\Observers;

use App\Models\Eleve;
use App\Models\Paren;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EleveObserver
{
    private $defaultEmail = 'parent@gmail.com';
    private $defaultPassword = 'parent0011'; // À changer en prod

    public function creating(Eleve $eleve)
    {
        $this->syncParentWithUser($eleve);
    }
    
    public function updating(Eleve $eleve)
    {
        if ($eleve->isDirty(['nom_pere', 'prenom_pere', 'email', 'telephone', 'adresse'])) {
            $this->syncParentWithUser($eleve);
        }
    }
    
   private function syncParentWithUser(Eleve $eleve)
{
    if (!$eleve->nom_pere || !$eleve->prenom_pere) {
        return;
    }
    
    $email = $eleve->email ?: $this->defaultEmail;
    $hashedPassword = Hash::make($this->defaultPassword);
    
    // ✅ 1. CORRECT : Cherche PAR email, crée SANS id
    $user = User::firstOrCreate(
        ['email' => $email], // SEULEMENT email (clé unique)
        [ // JAMAIS 'id' dans attributes
            'name' => $eleve->nom_pere,
            'prenom' => $eleve->prenom_pere,
            'password' => $hashedPassword,
            'role' => 'parent',
            'telephone' => $eleve->telephone ?? '0000000000',
            'email_verified_at' => now(),
        ]
    );
    
    // ✅ 2. Paren SYNCHRONISÉ
    $paren = Paren::updateOrCreate(
        ['user_id' => $user->id],
        [
            'nom' => $eleve->nom_pere,
            'prenom' => $eleve->prenom_pere,
            'email' => $email,
            'password' => $hashedPassword, // MÊME hash
            'telephone' => $eleve->telephone ?? '0000000000',
            'adresse' => $eleve->adresse ?? null,
        ]
    );
    
    if ($eleve->inscription) {
        $eleve->inscription->update(['paren_id' => $paren->id]);
    }
}

}
