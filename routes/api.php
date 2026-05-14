<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RadnikController;
use Illuminate\Support\Facades\Route;

Route::get('/test-konekcije', function () {
    return response()->json(['poruka' => 'Zdravo iz Laravela!']);
});

Route::post('/login', [AuthController::class, 'login']);

// Sve rute u ovoj grupi automatski traže Token i koriste RadnikController
Route::middleware('auth:sanctum')->controller(RadnikController::class)->group(function () {

    // --- RADNICI ---
    Route::get('/moji-radnici', 'getMojiRadnici');
    Route::post('/dodaj-radnika', 'dodajRadnika');
    Route::delete('/obrisi-radnika/{id}', 'obrisiRadnika');
    Route::put('/promeni-ulogu/{id}', 'promeniUlogu');

    // --- PROJEKTI ---
    Route::get('/moji-projekti', 'getMojiProjekti');
    Route::post('/dodaj-projekat', 'dodajProjekat');
    Route::get('/projekti/{id}', 'getProjekat');           // Za povlačenje 1 projekta za olovku
    Route::put('/izmeni-projekat/{id}', 'izmeniProjekat'); // Za čuvanje izmene olovke
    Route::delete('/obrisi-projekat/{id}', 'obrisiProjekat');

    // --- RELACIJE RADNIK <-> PROJEKAT ---
    Route::get('/projekti-radnika/{id}', 'getProjektiRadnika');
    Route::get('/projekti/{id}/radnici', 'getRadniciZaProjekat');
    Route::post('/projekti/{id}/radnici', 'sacuvajRadnikeProjekta');

});
