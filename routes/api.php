<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\EleveApiController;
use App\Http\Controllers\Api\ClasseApiController;
use App\Http\Controllers\Api\CycleApiController;
use App\Http\Controllers\Api\AnneeApiController;
use App\Http\Controllers\Api\TrimestreApiController;
use App\Http\Controllers\Api\NoteApiController;
use App\Http\Controllers\Api\BulletinApiController;
use App\Http\Controllers\Api\InscriptionApiController;
use App\Http\Controllers\Api\ParenApiController;
use App\Http\Controllers\Api\PaiementApiController;
use App\Http\Controllers\Api\EnseignantApiController;
use App\Http\Controllers\Api\ExamenBlancApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\ParenDashboardApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\UserApiController;




/*
|--------------------------------------------------------------------------
| AUTH API
|--------------------------------------------------------------------------
*/




// Dans le groupe auth:sanctum existant
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserApiController::class);
    Route::post('/users/{user}/reset-password', [UserApiController::class, 'resetPassword']);
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthApiController::class, 'login']);

    Route::post('/register', [AuthApiController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', [AuthApiController::class, 'user']);

        Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AuthApiController::class, 'me']);

    });
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [AuthApiController::class, 'user']);

    Route::post('/auth/logout', [AuthApiController::class, 'logout']);

});

/*
Route::post('/auth/login', [AuthApiController::class, 'login']);
Route::post('/auth/logout', [AuthApiController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthApiController::class, 'user']) ->middleware('auth:sanctum');
*/

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/dashboard', [DashboardApiController::class, 'index']);
});





/*
|--------------------------------------------------------------------------
| RESSOURCES CRUD
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('annees', AnneeApiController::class);

    Route::apiResource('trimestres', TrimestreApiController::class);

    Route::apiResource('cycles', CycleApiController::class);

    Route::apiResource('classes', ClasseApiController::class);

    Route::apiResource('eleves', EleveApiController::class);

    Route::apiResource('inscriptions', InscriptionApiController::class);

    Route::apiResource('notes', NoteApiController::class);

    Route::apiResource('bulletins', BulletinApiController::class);

    Route::apiResource('parens', ParenApiController::class);
    Route::apiResource('paiements', PaiementApiController::class);
    Route::apiResource('enseignants', EnseignantApiController::class);

    Route::apiResource('examens-blancs', ExamenBlancApiController::class);
});





/*
|--------------------------------------------------------------------------
| ROUTES DYNAMIQUES
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    | Classes par année
    */
    Route::get(
        '/annees/{annee}/classes',
        [ClasseApiController::class, 'classesByAnnee']
    );

    /*
    | Classes par cycle
    */
    Route::get(
        '/cycles/{cycle}/classes',
        [ClasseApiController::class, 'classesByCycle']
    );

    /*
    | Matières par classe
    */
    Route::get(
        '/classes/{classe}/matieres',
        [ClasseApiController::class, 'matieres']
    );

    /*
    | Élèves par classe
    */
    Route::get(
        '/classes/{classe}/eleves',
        [EleveApiController::class, 'parClasse']
    );

    /*
    | Notes par élève
    */
    Route::get(
        '/eleves/{eleve}/notes',
        [NoteApiController::class, 'notesEleve']
    );

    /*
    | Paiements d’un élève
    */
    Route::get(
        '/eleves/{eleve}/paiements',
        [PaiementApiController::class, 'paiementsEleve']
    );

    /*
    | Bulletin d’un élève
    */
    Route::get(
        '/eleves/{eleve}/bulletin',
        [BulletinApiController::class, 'bulletinEleve']
    );
});





/*
|--------------------------------------------------------------------------
| IMPORTATIONS
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
Route::get('/paren/dashboard', [ParenDashboardApiController::class, 'index']);
    /*
    | Import élèves
    */
    Route::post('/eleves/import', [EleveApiController::class, 'import']);

    /*
    | Import notes
    */
    Route::post('/notes/import', [NoteApiController::class, 'import']);

    /*
    | Import matières
    */
    Route::post('/matieres/import', [ClasseApiController::class, 'importMatieres']);

    /*
    | Import enseignants
    */
    Route::post('/enseignants/import', [EnseignantApiController::class, 'import']);
});





/*
|--------------------------------------------------------------------------
| EXAMENS BLANCS
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post(
        '/examens/{id}/generer-participants',
        [ExamenBlancApiController::class, 'genererParticipants']
    );

    Route::post(
        '/examens/{id}/generer-epreuves',
        [ExamenBlancApiController::class, 'genererEpreuves']
    );

    Route::get(
        '/examens/{id}/classement',
        [ExamenBlancApiController::class, 'classement']
    );
});