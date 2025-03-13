<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CountryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes d'authentification (publiques)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées par l'authentification
Route::middleware('auth:sanctum')->group(function () {
    // Route de déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Route pour obtenir les informations de l'utilisateur connecté
    Route::get('/user', [AuthController::class, 'user']);
    
    // Routes pour la gestion des pays
    Route::apiResource('countries', CountryController::class);
    
    // Routes pour la gestion des drapeaux
    Route::post('/countries/{id}/flag', [CountryController::class, 'updateFlag']);
    Route::get('/countries/{id}/flag', [CountryController::class, 'getFlag']);
});