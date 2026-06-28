<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfilController;
//use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
//use App\Http\Controllers\MoyenneController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AnneeController;
use App\Http\Controllers\TrimestreController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\ParenController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\ClassementController;
use App\Http\Controllers\ConduiteController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\PmoniController;
use App\Http\Controllers\TestImportController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TableauController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\MatiereImportController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\NoteImportController;
use App\Http\Controllers\PassageController;
use App\Http\Controllers\FraisController;
use App\Http\Controllers\EcheanceController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ConduiteImportController;
use App\Exports\PaiementsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\InscriptionFraisController;
use App\Http\Controllers\ImportationNoteController;
use App\Livewire\Bulletins\BulletinManager;
use App\Livewire\Bulletins\BulletinCard;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\RecetteController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\EleveImportController;
use App\Http\Controllers\ParenImportController;
use App\Http\Controllers\ImportPreviewController;
use App\Http\Controllers\EnseignantImportController;
use App\Http\Livewire\TestImporter;
use App\Http\Controllers\ParenDashboardController;
use App\Http\Controllers\TdController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TypeController;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\FicheMatiereController;
use App\Http\Controllers\FicheController;
use App\Models\Classe;
use App\Models\Frais;
use App\Models\Inscription;
use App\Http\Controllers\DashboardStatistiqueController;
use App\Http\Livewire\ImportMatieres;
use App\Exports\MatieresTemplateExport;
use App\Livewire\InscriptionFraisTable;
use App\Http\Controllers\ExamenBlancController;
use App\Http\Controllers\NoteExamenController;
use App\Http\Controllers\AjaxController; 
use App\Http\Controllers\RoleController; 
use App\Http\Controllers\AnneeClasseFraisController;
use Illuminate\Http\Request;
use App\Models\Annee;
use App\Models\Note;
use App\Http\Controllers\GalerieController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\RecuPaiementController;
use App\Livewire\PaiementMultiple;
use App\Http\Livewire\AnnulationPassage;
use App\Http\Controllers\TdRecapPdfController;
use App\Http\Controllers\TdRecapController;
use App\Http\Controllers\TdSeanceController;
use App\Http\Controllers\TdTarifController;
use App\Http\Controllers\TdPresenceController;
use App\Http\Controllers\TdPaiementController;








/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ✅ Après
Route::get('/check-session', function () {
    return response()->json(['active' => auth()->check()]);
});
Route::get('/', function () {return view('dashboard');})->name('home');

// Page et traitement de login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::get('/registre', function () {
    return view('registre');
})->name('registre');

Route::get('/templatte', function () {
    return view('templatte');
});

Route::get('/product-detail', function () {
    return view('product-detail');
});

Route::get('/serviceReadm', function () {
    return view('serviceReadm');
});
Route::get('/serviceReadp', function () {
    return view('serviceReadp');
});
Route::get('/serviceReads', function () {
    return view('serviceReads');
});

////////////////////////         ROUTES STATIQUES        /////////////////////////////////////////
Route::resource('galeries', GalerieController::class)->parameters(['galeries' => 'galerie']);
Route::get('/medias', [MediaController::class, 'index'])->name('medias.index');
//Route::get('/medias/create', [MediaController::class, 'create'])->name('medias.create');
Route::get('galeries/{galerie}/medias/create', [MediaController::class, 'create'])->name('medias.create');
Route::post('/galeries/{galerie}/medias', [MediaController::class, 'store'])->name('medias.store');
Route::get('/medias/{media}', [MediaController::class, 'show'])->name('medias.show');
Route::delete('/medias/{media}', [MediaController::class, 'destroy'])->name('medias.destroy');
Route::resource('users', UserController::class);
Route::resource('profil', App\Http\Controllers\ProfilController::class);
Route::resource('contacts', App\Http\Controllers\ContactController::class);
//Route::redirect('/dashboard', 'redirects')->name('redirects');
//Route::get('redirects', [HomeController::class, "index"])->middleware(['auth', 'verified']);
Route::resource('cycles', App\Http\Controllers\CycleController::class);
Route::resource('classes', App\Http\Controllers\ClasseController::class)->parameters([ 'classes' => 'classe']);
//Route::get('/get-classes/{annee_id}', [App\Http\Controllers\AjaxController::class, 'getClasses']);
//Route::get('/get-matieres/{classe_id}', [App\Http\Controllers\AjaxController::class, 'getMatieres']);
Route::get('/inscriptions/{inscription}/finances', [InscriptionController::class, 'finances']);
Route::resource('inscriptions', App\Http\Controllers\InscriptionController::class);
Route::get('/show/admin', [App\Http\Controllers\ShowController::class, 'create2'])->name('show.admin');
Route::get('/show/import', [App\Http\Controllers\ShowController::class, 'create3'])->name('show.import');
Route::get('/show/show2', [App\Http\Controllers\ShowController::class, 'show2'])->name('show.show2');
Route::get('/show/register', [App\Http\Controllers\ShowController::class, 'show3'])->name('show.register');
Route::resource('shows', App\Http\Controllers\ShowController::class);
Route::resource('bords', App\Http\Controllers\BordController::class);
Route::get('/tableau/show', [App\Http\Controllers\TableauController::class, 'create1'])->name('tableau.show');
Route::get('/tableau/bulletin', [App\Http\Controllers\TableauController::class, 'create2'])->name('tableau.bulletin');
Route::get('/tableau/pedagogie', [App\Http\Controllers\TableauController::class, 'create3'])->name('tableau.pedagogie');
Route::get('/tableau/conduite', [App\Http\Controllers\TableauController::class, 'create4'])->name('tableau.conduite');
Route::get('/tableau/paiement', [App\Http\Controllers\TableauController::class, 'create5'])->name('tableau.paiement');
Route::get('/tableau/parent', [App\Http\Controllers\TableauController::class, 'create6'])->name('tableau.parent');
Route::get('/tableau/examen', [App\Http\Controllers\TableauController::class, 'prendre'])->name('tableau.examen');
Route::get('/tableau/emplois', [App\Http\Controllers\TableauController::class, 'emplois'])->name('tableau.emplois');
Route::get('/tableau/planning', [App\Http\Controllers\TableauController::class, 'planning'])->name('tableau.planning');
Route::get('/tableau/annees', [App\Http\Controllers\TableauController::class, 'annees'])->name('tableau.annees');
Route::get('/tableau/finances', [App\Http\Controllers\TableauController::class, 'finances'])->name('tableau.finances');
Route::get('/tableau/utilisateur', [App\Http\Controllers\TableauController::class, 'utilisateur'])->name('tableau.utilisateur');
Route::middleware(['auth'])->group(function () {Route::get('/tableau/accueil', [TableauController::class, 'accueil'])->name('tableau.accueil');});
Route::get('/tableau/progtd', [App\Http\Controllers\TableauController::class, 'progtd'])->name('tableau.progtd');
Route::get('/tableau/inscription', [App\Http\Controllers\TableauController::class, 'inscription'])->name('tableau.inscription');
Route::resource('tableaus', App\Http\Controllers\TableauController::class)->parameters([ 'tableaus' => 'tableau']);
/////////                     IMPORTATION DES ELEVES           ///////////////////////////////////////////////
// Formulaire d'importation
Route::get('eleves/import', [EleveImportController::class, 'form'])->name('eleves.form');
//Route::get('/eleves/modele-import', [EleveImportController::class, 'telechargerModeleImport'])->name('eleves.modele.import');
Route::get('/eleves/modele/import', [EleveImportController::class, 'telechargerModeleImport'])->name('eleves.modele.import');
Route::get('/eleves/import', [EleveImportController::class, 'form'])->name('eleves.import');
// Soumission pour prévisualisation
Route::post('eleves/previsualiser', [EleveImportController::class, 'previsualiser'])->name('eleves.previsualiser');
// Validation finale après prévisualisation
Route::post('eleves/valider-import', [EleveImportController::class, 'validerImport'])->name('eleves.import.valider');
// Import direct sans prévisualisation
Route::post('eleves/importer', [EleveImportController::class, 'importer'])->name('eleves.importer');
// Affichage des erreurs d'importation
Route::get('eleves/erreurs', [EleveImportController::class, 'afficherErreurs'])->name('eleves.erreurs');
// Export des erreurs en CSV
Route::get('eleves/erreurs/export', [EleveImportController::class, 'exportErreurs'])->name('eleves.erreurs.export');
Route::get('/eleves/export', [EleveImportController::class, 'export'])->name('eleves.export');
Route::get('/eleves/import/erreurs', [EleveImportController::class, 'telechargerErreurs'])->name('eleves.import.erreurs');
Route::get('/eleves/photos', [EleveController::class, 'photosForm'])->name('eleves.photos');
/**Route::post('/eleves/import/photos/force', [EleveController::class, 'importPhotosForce']) ->name('eleves.import.photos.force');*/
Route::post('/eleves/import/photos', [EleveController::class, 'importPhotos'])->name('eleves.import.photos');
Route::resource('eleves', App\Http\Controllers\EleveController::class)->parameters([
    'eleves' => 'eleve'
]);
/////////                      FIN IMPORTATION DES ELEVES         ////////////////////////////////////////////
/////////                      IMPORTATION DES PARENTS       ////////////////////////////////////////////
    Route::resource('/parens', ParenController::class)->whereNumber('paren');
Route::get('/parent/dashboard', [ParenDashboardController::class, 'index'])->name('parens.dashboard');
        Route::get('/parens/import', [ParenImportController::class, 'form'])->name('parens.import.form');
        Route::get('/parens/import/modele', [ParenImportController::class, 'telechargerModele']) ->name('parens.import.modele');
        Route::post('/parens/import/preview', [ParenImportController::class, 'previsualiser'])->name('parens.import.preview');
         Route::post('/parens/import/validate', [ParenImportController::class, 'validerImport']) ->name('parens.import.validate');
Route::post('/parens/import/valider', [ParenImportController::class, 'validerImport'])->name('parens.import.valider');
//Route::middleware(['auth', 'role:parent'])->group(function () {
   // Route::get('/parens/dashboard', [ParenController::class, 'dashboard'])->name('parens.dashboard');
//});
/////////                      FIN IMPORTATION DES PARENTS       ////////////////////////////////////////////
/////////                      IMPORTATION DES ENSEIGNANTS      ////////////////////////////////////////////
Route::get('/enseignants/import', [EnseignantImportController::class, 'showForm'])->name('enseignants.import.form');
Route::post('/enseignants/import', [EnseignantImportController::class, 'import'])->name('enseignants.import');
Route::get('/enseignants/import/erreurs', [EnseignantImportController::class, 'showErreurs'])->name('enseignants.import.erreurs');
Route::resource('enseignants', App\Http\Controllers\EnseignantController::class)->parameters([
    'enseignants' => 'enseignant'
]);
/////////                      FIN IMPORTATION DES ENSEIGNANTS       ////////////////////////////////////////////

// Page du formulaire d'import
Route::get('/matieres/import', [MatiereImportController::class, 'index'])->name('matieres.import');
// Téléchargement du modèle Excel pour une année donnée
Route::get('/matieres/modele/download', [MatiereImportController::class, 'telechargerModele']) ->name('matieres.modele.download');
// Prévisualisation des matières avant import
Route::post('/matieres/preview', [MatiereImportController::class, 'preview']) ->name('matieres.preview');
// Import réel des matières depuis la prévisualisation
Route::post('/matieres/inserer', [MatiereImportController::class, 'inserer'])->name('matieres.inserer');
//Route::get('/template', [MatiereController::class, 'downloadTemplate']);
Route::resource('matieres', App\Http\Controllers\MatiereController::class)->where(['matiere' => '[0-9]+']);
Route::prefix('frais')->name('frais.')->group(function () {
    Route::get('/annee-classe', [AnneeClasseFraisController::class, 'index']) ->name('annee_classe.index');
    Route::put('/annee-classe/{id}', [AnneeClasseFraisController::class, 'update']) ->name('annee_classe.update');
    Route::delete('/annee-classe/{id}', [AnneeClasseFraisController::class, 'destroy']) ->name('annee_classe.destroy');
});
Route::resource('frais', FraisController::class)->parameters(['frais' => 'frais']); // ici on force le nom du paramètre
//Route::get('/frais', [FraisController::class, 'index'])->name('frais.index');
Route::get('/frais/export/pdf', [FraisController::class, 'exportPdf'])->name('frais.export.pdf');
Route::get('/frais/classe', [FraisController::class, 'listeParClasse'])->name('frais.classe');
// On ne génère pas GET /inscription-frais (index)
Route::get('/ajax/eleves-par-classe/{classe}', [InscriptionFraisController::class, 'elevesParClasse']);
Route::resource('inscription-frais', InscriptionFraisController::class);//->except(['index']);


// Page de paiement multiple (Livewire)
Route::get('/paiements/fraisFilter', [PaiementController::class, 'fraisFilter'])->name('paiements.fraisFilter');
Route::get('/paiements/paiement', [PaiementController::class, 'paiement'])->name('paiements.paiement');
Route::get('/paiements/multiple', PaiementMultiple::class)->name('paiements.multiple');
// Reçu / ticket d'impression
Route::get('/paiements/ticket/{numeroLot}', [RecuPaiementController::class, 'ticket'])->name('paiements.ticket');
Route::get('/paiements', fn() => view('paiements.index'))->name('paiements.index');
Route::get('paiements/historique', [PaiementController::class, 'historique']) ->name('paiements.historique');//->middleware(['auth', 'verified', 'role:admin,directeur']);
Route::get('/paiements/export/pdf', [PaiementController::class, 'exportPdf'])->name('paiements.export.pdf');//->middleware(['auth', 'verified', 'role:admin,directeur']);
// routes/web.php
Route::get('/paiements/recu/{numeroLot}',[PaiementController::class, 'recu'])->name('paiements.recu');
//Route::get('/paiements/{paiement}/recu', [PaiementController::class, 'recu'])->name('paiements.recu');
Route::get('/paiements/create-up', [PaiementController::class, 'createUP'])->name('paiements.create-up');
Route::post('/paiements/store-up', [PaiementController::class, 'storeUP'])->name('paiements.storeUP');
Route::put('/paiements/update-up/{id}', [PaiementController::class, 'updateUP'])->name('paiements.updateUP');
Route::resource('paiements', PaiementController::class)->whereNumber('paiement');
//Route::get('/paiements', [PaiementController::class, 'index'])->name('paiements.index');
// 👇 IMPORATATION DE NOTES
// Formulaire d'importation 
//Route::get('/notes/import', [App\Http\Controllers\NotesController::class, 'importer']) ->name('dashboard');//->middleware(['auth', 'verified']);
// Téléchargement du fichier Excel multi-feuilles
Route::get('/notes/template', [App\Http\Controllers\NotesController::class, 'downloadTemplate']) ->name('notes.template');
// Prévisualisation des notes avant insertion
Route::post('/notes/previsualiser', [App\Http\Controllers\NotesController::class, 'previsualiser'])->name('notes.previsualiser');
// Insertion finale des données validées
Route::post('/notes/inserer', [App\Http\Controllers\NotesController::class, 'inserer'])->name('notes.inserer');
// Page d'importation
Route::get('/notes/saisie', \App\Livewire\SaisieNotes::class)
    ->middleware('auth')
    ->name('notes.saisie');
Route::get('/notes/saisie-import', function () {
    return view('notes.saisie-import'); // ← la vue qui contient @livewire(...)
})->middleware('auth')->name('notes.saisie-import');
Route::get('/notes/moyennes', [NotesController::class, 'moyennes'])->name('notes.moyennes');
Route::get('/notes/import2', [NoteImportController::class, 'index'])->name('notes.import.index');
// Téléchargement du template Excel
Route::post('/notes/import2/template', [NoteImportController::class, 'downloadTemplate']) ->name('notes.import.template');
// Prévisualisation avant import
Route::post('/notes/import2/preview', [NoteImportController::class, 'preview']) ->name('notes.import.preview');
// Enregistrement en base après prévisualisation
Route::post('/notes/import2/store', [NoteImportController::class, 'store']) ->name('notes.import.store');
// Routes CRUD normales
Route::resource('notes', App\Http\Controllers\NotesController::class)->where(['note' => '[0-9]+']);
// ✅ ROUTES CONDUITE (IMPORT + CRUD)
// Routes fixes AVANT les routes dynamiques
// 1. Afficher le formulaire d'import
Route::get('/conduites/import', [ConduiteController::class, 'import']) ->name('conduites.import');//->middleware(['auth', 'verified']);
// 2. Prévisualiser les données du fichier
Route::post('/conduites/previsualiser', [ConduiteController::class, 'previsualiser'])->name('conduites.previsualiser');
    /**Route::get('/conduites/previsualiser', function () {
    return redirect()->route('conduites.import');
});*/
// 3. Enregistrer les données validées
/**Route::post('/conduites/inserer', function() {
    return "Route POST /conduites/inserer accessible";
});*/
Route::post('/conduites/inserer', [ConduiteController::class, 'inserer'])->name('conduites.inserer');
/**Route::post('/conduites/importer', [ConduiteController::class, 'importer'])->name('conduites.importer');*/
// Routes RESTful (sans show)
Route::resource('conduites', ConduiteController::class)->except(['show']);
// Route dynamique show avec contrainte pour éviter les collisions (comme "import")
   // routes/web.php

/*
|--------------------------------------------------------------------------
| BULLETINS - WEB (PROPRE ARCHITECTURE)
|--------------------------------------------------------------------------
*/
Route::prefix('bulletins')->name('bulletins.')->group(function () {
    /*
    |----------------------------
    | INDEX (LISTE PRINCIPALE)
    |----------------------------
    */
    Route::get('/', BulletinManager::class)->name('index');

    /*
    |----------------------------
    | LIVEWIRE DASHBOARD (optionnel si tu veux séparer)
    |----------------------------
    */
    Route::get('/live', BulletinManager::class)->name('live');
    /*
    |----------------------------
    | DÉTAIL INSCRIPTION (CARTE BULLETIN)
    |----------------------------
    */
    Route::get(
    '/card/{inscriptionId}/{trimestreId}',
    \App\Livewire\Bulletins\BulletinCard::class
)->name('card');
    /*
    |----------------------------
    | SHOW BULLETIN (CONTROLLER CLASSIQUE)
    |----------------------------
    */
    Route::get('/show/{bulletin}', [BulletinController::class, 'show'])
        ->name('show');

    /*
    |----------------------------
    | PDF PAR CLASSE / ANNEE / TRIMESTRE
    |----------------------------
    */
    Route::get('/pdf/{annee}/{classe}/{trimestre}', [BulletinController::class, 'pdfClasse'])
        ->name('pdf');

    /*
    |----------------------------
    | BULLETINS PAR CLASSE (VIEW SIMPLE)
    |----------------------------
    */
    Route::get('/classe/{classe}/{trimestre}/{annee}', [BulletinController::class, 'bulletinsParClasse'])
        ->name('classe');
    /*
    |----------------------------
    | EXPORT CLASSE (si besoin séparé)
    |----------------------------
    */
    Route::get('/export/{classe}/{trimestre}/{annee}', [BulletinController::class, 'exportClasse'])
        ->name('export');
});
Route::get('/bulletins', \App\Livewire\Bulletins\BulletinManager::class)->name('bulletins.manager');
Route::get('/bulletins/bulletins', [App\Http\Controllers\BulletinController::class, 'bulletins'])->name('bulletins.bulletins');
Route::resource('recettes', App\Http\Controllers\RecetteController::class);
Route::resource('depenses', App\Http\Controllers\DepenseController::class);
Route::get('/finances', [FinanceController::class, 'index'])->name('finances.index');//->middleware(['auth', 'verified', 'role:admin,directeur,comptable']);
Route::resource('categories', App\Http\Controllers\CategorieController::class)->parameters(['categories' => 'categorie']);
Route::resource('transactions', App\Http\Controllers\TransactionController::class);
Route::resource('comptes', App\Http\Controllers\CompteController::class);
Route::resource('budgets', App\Http\Controllers\BudgetController::class);
// Passage des élèves
// routes/web.php
// routes/web.php


Route::get('/annulation-passage', function () {return view('livewire.annulation-passage');})->middleware('auth')->name('annulation.passage');
Route::get('/passages', [PassageController::class, 'index'])->name('passages.index');
Route::get('/passages/annuler', [PassageController::class, 'annuler'])->name('passages.annuler');
Route::get('/rapports', [App\Http\Controllers\RapportController::class, 'index'])->name('rapports.index');
Route::post('/rapports/resultats', [App\Http\Controllers\RapportController::class, 'resultats'])->name('rapports.resultats');
Route::get('/rapports/pdf', [App\Http\Controllers\RapportController::class, 'exportPdf'])->name('rapports.pdf');
// Rapport multi-catégories
Route::get('/rapports/global', [RapportController::class, 'globalForm'])->name('rapports.global.form');
Route::post('/rapports/global/resultat', [RapportController::class, 'globalResultat'])->name('rapports.global.resultat');
Route::get('/rapports/global/pdf', [RapportController::class, 'globalPdf'])->name('rapports.global.pdf');
Route::get('operations/rapport', [OperationController::class, 'rapportForm'])->name('operations.rapport.form');
Route::post('operations/rapport', [OperationController::class, 'rapportGenerer'])->name('operations.rapport.generer');
Route::get('operations/rapport/pdf', [OperationController::class, 'rapportPdf'])->name('operations.rapport.pdf');
Route::resource('operations', OperationController::class)->where(['operation' => '[0-9]+']);
Route::get('/tests', [TestController::class, 'index'])->name('tests.index'); // si tu veux la liste
Route::get('/tests/multiple/create', [TestController::class, 'createMultiple'])->name('tests.multiple.create');
Route::post('/tests/multiple/store', [TestController::class, 'storeMultiple'])->name('tests.multiple.store');
// Import + Preview workflow
// 1️⃣ Formulaire d'importation
Route::middleware(['web'])->group(function () {
    Route::get('/tests/import', [TestImportController::class, 'showImportForm'])->name('tests.importForm');
    Route::post('/tests/import/preview', [TestImportController::class, 'preview'])->name('tests.preview');
    Route::post('/tests/import-final', [TestImportController::class, 'importFinal'])->name('tests.importFinal');
});

Route::get('/search/tests', [TestController::class, 'search'])->name('tests.search');
Route::get('/tests/live-import', function () {
    return view('tests.live-import');
})->name('tests.live.import');
//Route::get('/passage', [PassageController::class, 'index'])->name('passage.index');

Route::get('/debug-php', function() {
    return response()->json([
        'max_file_uploads' => ini_get('max_file_uploads'),
        'post_max_size' => ini_get('post_max_size'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'files_received' => request()->file('tests_files')?->count() ?? 0
    ]);
});
Route::resource('frais.echeances', EcheanceController::class);
Route::resource('articles', App\Http\Controllers\StockController::class);
Route::resource('types', App\Http\Controllers\TypeController::class);
Route::get('/fiches/une-matiere/pdf', [FicheMatiereController::class, 'telecharger'])->name('fiches.une-matiere.pdf');
Route::middleware(['auth'])->group(function () {
    // 1️⃣ Formulaire pour générer toutes les fiches
    Route::get('/fiches/generer', [FicheController::class, 'formulaire'])->name('fiches.formulaire');

    // 2️⃣ Générer toutes les fiches de toutes les matières d’une classe
    Route::post('/fiches/generer/toutes', [FicheController::class, 'genererToutesMatieres'])->name('fiches.generer.toutes.matieres');
});
// Formulaire et génération PDF
Route::get('/fiches/export/pdf', [FicheController::class, 'exportPDF'])->name('fiches.export.pdf');
Route::get('/notes/import-livewire', function () {return view('notes.import-livewire');})->name('notes.import-livewire');
//Route::get('/moyennes/moyennes/pdf', [MoyenneController::class, 'exportPdf']) ->name('moyennes.export.pdf');
Route::get('/dashboard-statistiques', [DashboardStatistiqueController::class, 'index']) ->name('dashboard.statistiques');
Route::get('/dashboard-statistique/export-pdf', [DashboardStatistiqueController::class, 'exportPdf'])->name('dashboard.statistique.pdf');
Route::get('/classement-par-classe',  [DashboardStatistiqueController::class, 'classementParClasse'])->name('classement.par.classe');
Route::get('/classement-par-classe/pdf', [DashboardStatistiqueController::class, 'exportClassementParClassePdf'])->name('classement.par.classe.pdf');
Route::get('/dashboard/classement-annuel',  [DashboardStatistiqueController::class, 'classementAnnuel'])->name('dashboard.statistique.classement.annuel');
Route::get( '/statistiques/classement-annuel-classe', [DashboardStatistiqueController::class, 'classementAnnuelParClasse'])->name('dashboard.statistique.classement.annuel.par.classe');
Route::get('/classement-annuel-par-classe/pdf',  [DashboardStatistiqueController::class, 'exportClassementAnnuelParClassePdf'])->name('classement.annuel.par.classe.pdf');
Route::get('/classement-annuel/pdf', [DashboardStatistiqueController::class, 'classementAnnuelPdf'])->name('classement.annuel.pdf');
//Route::get('/matieres/import', function () { return view('matieres.import');})->name('matieres.import');

Route::resource('examens-blancs', ExamenBlancController::class);
Route::post('/examens/{id}/generer-participants', [ExamenBlancController::class, 'genererParticipants']);
Route::post('/examens/{id}/generer-epreuves', [ExamenBlancController::class, 'genererEpreuves']);
// Saisie des notes (routes nommées)
Route::get('/examens/{id}/notes', [ExamenBlancController::class, 'saisirNotes'])->name('examens.notes');
Route::post('/examens/notes/save', [ExamenBlancController::class, 'enregistrerNotes'])->name('examens.notes.save');
Route::prefix('examens-notes')->group(function () {
    Route::get('/', [NoteExamenController::class, 'index'])->name('examens.notes.index');
    Route::get('/template/{examen}', [NoteExamenController::class, 'downloadTemplate'])->name('examens.notes.template');
    Route::get('/import/{examen}', [NoteExamenController::class, 'importForm'])->name('examens.notes.import.form');
    Route::post('/preview', [NoteExamenController::class, 'preview'])->name('examens.notes.preview');
    Route::post('/import', [NoteExamenController::class, 'import'])->name('examens.notes.import');
});
Route::get('/examens/{id}/export-pdf', [ExamenBlancController::class, 'exportPdf']) ->name('examens.export.pdf');
Route::get('/examens/{id}/classement', [ExamenBlancController::class, 'classement'])->name('examens.classement');
Route::get('/examens/{id}/notes/pdf', [ExamenBlancController::class, 'notesPdf'])->name('examens.notes.pdf');
Route::get('/roles/{role}/pdf', [RoleController::class, 'exportPdf'])->name('roles.pdf');
// routes/web.php




Route::get('/td/recap/pdf', [TdRecapPdfController::class, 'export'])->name('td.recap.pdf');
Route::get('/td/recap/classe/pdf', [TdRecapPdfController::class, 'classe'])->name('td.recap.classe.pdf');
Route::get('/td/recap/toutes-classes/pdf', [TdRecapPdfController::class, 'toutesClasses'])->name('td.recap.toutes-classes.pdf');
Route::get('td-presences/{seance}', [TdPresenceController::class, 'show'])->name('td-presences.show');
Route::resource('td-tarifs', App\Http\Controllers\TdTarifController::class);
Route::resource('td-seances', App\Http\Controllers\TdSeanceController::class);
Route::resource('td-paiements', App\Http\Controllers\TdPaiementController::class);
Route::get('/td/recap/pdf', [TdRecapPdfController::class, 'export'])->name('td.recap.pdf');
Route::get('/td', [TdController::class, 'index'])->name('td.index');
Route::get('/td/dirige', [TdController::class, 'dirige'])->name('td.dirige');



////////////////////////////// FIN ROUTES STATIQUES/////////////////////////////////////////

///////////////////////////////// ROUTES DYNAMIQUES //////////////////////////////////////////
Route::get('/profil/{id}', [App\Http\Controllers\ProfilController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('profil.show');
// Récupérer les classes actives pour une année
//Route::get('/annees/{annee}/classes', [ClasseController::class, 'getClassesByAnnee']);
Route::get('/classes-by-annee/{annee}', [ClasseController::class, 'classesByAnnee'])
     ->name('classes.by.annee');
// Récupérer les matières pour une classe
//Route::get('/classes/{classe}/matieres', [MatiereController::class, 'getMatieresByClasse']);
Route::get('/cycles/{cycle}/classes', [ClasseController::class, 'getClassesByCycle']);
Route::get('/annees/{annee}/cycles/{cycle}/classes', [ClasseController::class, 'getAnneeClassesByCycle']);
Route::get('/annees/{annee}/classes', [ClasseController::class, 'getClassesByAnnee']);
Route::get('/annees/{id}/classes', [App\Http\Controllers\AnneeController::class, 'getClasses'])
    ->where('id', '[0-9]+');
// web.php
Route::get('/annees/{annee}/classes/actives', [AnneeController::class, 'getClassesActives']);
/**Route::get('/annees/{annee}/trimestres', function (Annee $annee) {
    return $annee->trimestresActifs()->get();

});*/
Route::get('/annees/{annee}/trimestres', [TrimestreController::class, 'getTrimestresByAnnee']);
Route::resource('annees', App\Http\Controllers\AnneeController::class);

// routes/web.php
//Route::get('/classes/{annee_id}', [App\Http\Controllers\ClasseController::class, 'getByAnnee']);
Route::resource('trimestres', App\Http\Controllers\TrimestreController::class);
//Route::get('/classes/by-annee/{annee_id}', [ClasseController::class, 'getByAnnee']);
//Route::get('/classes/{classe}/matieres', function(App\Models\Classe $classe) { return $classe->matieres()->select('id', 'nom')->get();});
// ✅ CORRECT
/**Route::get('/classes-by-annee/{annee}', function($anneeId) {
    $classes = \App\Models\Annee::findOrFail($anneeId)
                  ->classesActives()
                  ->get();
    
    return response()->json($classes);
});*/

Route::get('/classes/annee/{annee_id}', [ClasseController::class, 'getByAnnee'])
     ->where('annee_id', '[0-9]+');
    /// Route::patch('/classe-annee/{id}/toggle', [ClasseAnneeController::class, 'toggle'])
     ///->name('classe-annee.toggle');
 Route::get('annees/{annee}/classes/cycle3', [AnneeController::class, 'getClassesCycle3']) ->name('annees.classes.cycle3');
Route::patch('/classes/{classe}/toggle', [App\Http\Controllers\ClasseController::class, 'toggle'])->name('classes.toggle');
Route::patch('/classes/{classe}/annee/{annee}/toggle-active', [ClasseController::class, 'toggleActiveAnnees'])
     ->name('classes.toggleActiveAnnees');
     Route::get('/classes/annee/{annee}', [ClasseController::class, 'indexByAnnee'])
     ->name('classes.byAnnee');
 Route::get('/classes/{id}/inscriptions', [PaiementController::class, 'getInscriptionsParClasse']);
 Route::get('classes/{id}/frais', [PaiementController::class, 'getFraisParClasse']);
Route::get('/classes/{id}/matieres', [App\Http\Controllers\ClasseController::class, 'getMatieres'])->where('id', '[0-9]+');
    Route::get('/eleves-par-classe/{classe}', [App\Http\Controllers\EleveController::class, 'getParClasse']);
    Route::get('/eleves/{id}', [EleveController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('eleves.show');
    Route::get('/frais/eleve/{eleve}', [FraisController::class, 'detailsEleve'])->name('frais.eleve');
//Route::get('/classes/{classe}/matieres', [MatiereController::class, 'getByClasse']);
//Route::get('/api/classes/{annee}', function($annee) { return \App\Models\Classe::where('annee_id', $annee)->get();});
//Route::get('/api/matieres/{classe}', function($classe) { return \App\Models\Matiere::where('classe_id', $classe)->get(); });
Route::get('conduites/template/{classe}/{trimestre}/{annee}', [ConduiteController::class, 'template'])->name('conduites.template');
Route::get('/conduites/{id}', [ConduiteController::class, 'show']) ->where('id', '[0-9]+')->name('conduites.show');
    Route::get('/classe/{classe_id}/annee/{annee_id}/classement-annuel', [MoyenneController::class, 'recalculerRangsAnnuels']);
Route::resource('tests', App\Http\Controllers\TestController::class)->where(['test' => '[0-9]+']);//->middleware(['auth', 'verified', 'role:admin,parent,secretaire']);
  
   //Route::middleware(['auth', 'role:admin'])
    //->group(function () {
   // });
// Récupérer les frais pour une inscription donnée
Route::get('/inscriptions/{inscription}/frais', [PaiementController::class, 'getFraisParInscription']);



Route::get('/classes/{classe}/frais', [PaiementController::class, 'getFraisParClasse'])->name('classes.frais');


// ✅ Inscriptions OK, mais ajoute gestion erreur
Route::get('/classes/{classe}/inscriptions', function ($classe, Request $request) {
    $anneeId = $request->annee_id;
    if (!$anneeId) return response()->json([], 400);
    return Inscription::with('eleve')
        ->where('classe_id', $classe)
        ->where('annee_id', $anneeId)
        ->get(['id', 'eleve']);
})->name('classes.inscriptions');


// Routes AJAX "API" pour les selects
Route::get('/ajax/annees/{annee}/trimestres', [AjaxController::class, 'trimestres'])->name('ajax.trimestres');
Route::get('/ajax/annees/{annee}/classes', [AjaxController::class, 'classes'])->name('ajax.classes');
Route::get('/ajax/classes/{classe}/matieres', [AjaxController::class, 'matieres'])->name('ajax.matieres');
// routes/web.php
Route::get('/fiches', fn() => view('fiches.index'))->name('fiches.index');
Route::get('/fiches/pdf', function (Request $request) {

    $request->validate([
        'annee_id'     => 'required',
        'trimestre_id' => 'required',
        'classe_id'    => 'required',
        'matiere_id'   => 'required',
    ]);

    $annee     = Annee::findOrFail($request->annee_id);
    $trimestre = $annee->trimestres()->findOrFail($request->trimestre_id);
    $classe    = $annee->classes()->findOrFail($request->classe_id);
    $matiere   = $classe->matieres()->findOrFail($request->matiere_id);

    $coef = $matiere->pivot->coef;

    $eleves = Inscription::where('annee_id', $annee->id)
        ->where('classe_id', $classe->id)
        ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
        ->orderBy('eleves.nom')
        ->orderBy('eleves.prenom')
        ->select('eleves.*')
        ->get();

    $notes = Note::where([
        'annee_id'     => $annee->id,
        'trimestre_id' => $trimestre->id,
        'matiere_id'   => $matiere->id,
    ])->get()->keyBy('eleve_id');

    $resultats = [];

    foreach ($eleves as $eleve) {
        $n = $notes[$eleve->id] ?? null;

        $moy = $n
            ? collect([$n->devoir, $n->mcc, $n->composition])->filter()->avg()
            : null;

        $resultats[] = [
            'eleve'    => $eleve,
            'note'     => $n,
            'moyenne'  => $moy,
            'moy_coef' => $moy ? $moy * $coef : null,
        ];
    }

    $classement = collect($resultats)
        ->whereNotNull('moyenne')
        ->sortByDesc('moyenne')
        ->values();

    foreach ($classement as $i => $res) {
        $classement[$i]['rang'] = $i + 1;
    }

    $pdf = Pdf::loadView('fiches.pdf', compact(
        'annee', 'trimestre', 'classe', 'matiere',
        'resultats', 'classement', 'coef'
    ))->setPaper('A4', 'landscape');

    return $pdf->download(
        'Fiche_notes_'.$classe->nom.'_'.$matiere->nom.'.pdf'
    );

})->name('fiches.pdf');
Route::get('/annees/{id}/trimestres', function ($id) {
    return \App\Models\Trimestre::where('annee_id', $id)->get();
});

Route::get('/annees/{id}/classes', function ($id) {
    return \App\Models\Classe::whereHas('cycles', function ($q) {
        $q->where('cycle_id', 3);
    })->get();
});
///////////////////////////////// FIN ROUTES DYNAMIQUES //////////////////////////////////////////

//->middleware('auth')
//->middleware(['auth', 'verified'])
//->middleware(['auth', 'verified', 'role:admin,parent,censeur,diercteur,secretaire'])
///Route::resource('tests', App\Http\Controllers\TestController::class)->where(['test' => '[0-9]+'])->middleware(['auth', 'verified', 'role:admin,parent,secretaire']);
