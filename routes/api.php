<?php

use Illuminate\Support\Facades\Route;

// Naša probna ruta
Route::get('/test-konekcije', function () {
    return response()->json([
        'status' => 'uspeh',
        'poruka' => 'Zdravo iz Laravela! C# i Laravel uspesno komuniciraju.',
        'vreme'  => now()->toDateTimeString(),
    ]);
});
