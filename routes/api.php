<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Ostavljamo onu probnu rutu ako ti zatreba
Route::get('/test-konekcije', function () {
    return response()->json(['poruka' => 'Zdravo iz Laravela!']);
});

// Nova, čista ruta koja gađa Kontroler
Route::post('/login', [AuthController::class, 'login']);
