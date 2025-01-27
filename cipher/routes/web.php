<?php

use App\Http\Controllers\CipherController;

Route::get('/cipher', [CipherController::class, 'index']);
Route::post('/cipher/encrypt', [CipherController::class, 'encrypt']);
Route::post('/cipher/decrypt', [CipherController::class, 'decrypt']);