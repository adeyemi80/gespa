<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Conduite;
use App\Services\CalculMoyenneService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalculMoyenneServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function il_calcule_correctement_la_moyenne_generale_avec_conduite()
    {
        $eleve = Eleve::factory()->create();
        $annee_id = 1;
        $trimestre_id = 1;

        // Création des matières
        $math = Matiere::create(['nom' => 'Math', 'coefficient' => 4]);
        $physique = Matiere::create(['nom' => 'Physique', 'coefficient' => 3]);
        $francais = Matiere::create(['nom' => 'Français', 'coefficient' => 2]);

        // Création des notes
        Note::create(['eleve_id' => $eleve->id, 'matiere_id' => $math->id, 'annee_id' => $annee_id, 'trimestre_id' => $trimestre_id, 'moyenne' => 14]);
        Note::create(['eleve_id' => $eleve->id, 'matiere_id' => $physique->id, 'annee_id' => $annee_id, 'trimestre_id' => $trimestre_id, 'moyenne' => 12]);
        Note::create(['eleve_id' => $eleve->id, 'matiere_id' => $francais->id, 'annee_id' => $annee_id, 'trimestre_id' => $trimestre_id, 'moyenne' => 16]);

        // Ajout de la conduite
        Conduite::create([
            'eleve_id' => $eleve->id,
            'annee_id' => $annee_id,
            'trimestre_id' => $trimestre_id,
            'niveau' => 'Bonne' // = 8
        ]);

        $service = new CalculMoyenneService();

        $moyenne = $service->calculerMoyenneGenerale($eleve, $annee_id, $trimestre_id);

        /**
         * Calcul attendu :
         * Numérateur : 14×4 + 12×3 + 16×2 + 8 = 56 + 36 + 32 + 8 = 132
         * Dénominateur : 4 + 3 + 2 + 1 = 10
         * Résultat : 132 / 10 = 13.2
         */
        $this->assertEquals(13.2, $moyenne);
    }

    /** @test */
    public function retourne_null_si_aucune_note_et_conduite_absente()
    {
        $eleve = Eleve::factory()->create();
        $annee_id = 1;
        $trimestre_id = 1;

        $service = new CalculMoyenneService();

        $moyenne = $service->calculerMoyenneGenerale($eleve, $annee_id, $trimestre_id);

        $this->assertNull($moyenne);
    }
}

