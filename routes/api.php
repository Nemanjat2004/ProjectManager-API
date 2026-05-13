<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RadnikController;
use Illuminate\Support\Facades\Route;

// Ostavljamo onu probnu rutu ako ti zatreba
Route::get('/test-konekcije', function () {
    return response()->json(['poruka' => 'Zdravo iz Laravela!']);
});

// Nova, čista ruta koja gađa Kontroler
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Sada je ruta potpuno čista i samo prosleđuje zadatak kontroleru
    Route::get('/moji-radnici', [RadnikController::class, 'getMojiRadnici']);

    Route::post('/dodaj-radnika', [RadnikController::class, 'dodajRadnika']);

    Route::get('/moji-projekti', [RadnikController::class, 'getMojiProjekti']);

});
