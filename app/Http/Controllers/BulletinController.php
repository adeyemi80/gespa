<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bulletin;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Note;
use App\Models\Classe;
use App\Models\Trimestre;
use App\Models\Annee;
use App\Models\Moyenne;
use App\Models\Matiere;
use App\Models\Conduite;
use App\Services\MoyenneService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\GereMentions;

class BulletinController extends Controller
{
    public function bulletins()
    {

        return view('bulletins.bulletins');
    }

    
// Affichage du formulaire de génération
      // Affichage du formulaire de génération et des bulletins


/**
 * Méthode pour déterminer l'appréciation générale selon la moyenne trimestrielle
 */


/**
 * Méthode pour déterminer l'appréciation par matière
 */



/**
 * Méthode pour déterminer l'appréciation de la conduite
 */



    /**
     * Affiche la liste des bulletins avec moyennes et rangs
     */
    

    /**
     * Mise à jour d'une note
     */
   

    public function getClassesParAnnee($anneeId)
{
    $annee = Annee::findOrFail($anneeId);
    // Récupère uniquement les classes actives attachées à cette année
    $classes = $annee->classesActives()->get();
    return response()->json($classes);
}

// Dans un contrôleur


}
