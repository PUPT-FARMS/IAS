<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CipherController;

// Redirect the base URL to /cipher
Route::get('/', function () {
    return redirect('/cipher');
});

// Define the cipher routes
Route::get('/cipher', [CipherController::class, 'index']);
Route::post('/cipher/encrypt', [CipherController::class, 'encrypt']);
Route::post('/cipher/decrypt', [CipherController::class, 'decrypt']);
