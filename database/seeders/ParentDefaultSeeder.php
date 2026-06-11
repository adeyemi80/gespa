<?php
// database/seeders/ParentDefaultSeeder.php
namespace Database\Seeders;

use App\Models\Paren;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ParentDefaultSeeder extends Seeder
{
    public function run()
    {
        $defaultEmail = 'parent@defaut.com';
        $defaultPassword = Hash::make('parent123');

        Paren::whereDoesntHave('user')
            ->orWhereHas('user', function($q) {
                $q->whereNull('email')->orWhereNull('password');
            })
            ->chunk(100, function ($parens) use ($defaultEmail, $defaultPassword) {
                foreach ($parens as $paren) {
                    // ✅ 1. Créer/mettre à jour User PAR email (pas par id)
                    $user = User::firstOrCreate(
                        ['email' => $paren->email ?: $defaultEmail],
                        [
                            'name' => $paren->nom ?? 'Parent',
                            'prenom' => $paren->prenom ?? '',
                            'password' => $defaultPassword,
                            'role' => 'parent',
                            'telephone' => $paren->telephone ?? '0000000000',
                            'email_verified_at' => now(),
                        ]
                    );

                    // ✅ 2. Lier et synchroniser
                    $paren->update([
                        'user_id' => $user->id,
                        'password' => $defaultPassword,
                        'email' => $user->email,
                    ]);
                }
            });
    }
}
