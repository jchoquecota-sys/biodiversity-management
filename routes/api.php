<?php

use App\Http\Controllers\Api\BiodiversityController;
use App\Http\Controllers\Api\PublicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Biodiversity API Routes
Route::apiResource('biodiversity', BiodiversityController::class);
Route::get('biodiversity/{biodiversity}/publications', [BiodiversityController::class, 'publications']);
Route::get('biodiversity/{biodiversity}/similar', [BiodiversityController::class, 'similar']);

// Publications API Routes
Route::apiResource('publications', PublicationController::class);
Route::get('publications/filter', [PublicationController::class, 'filter']);
Route::get('publications/{publication}/similar', [PublicationController::class, 'similar']);