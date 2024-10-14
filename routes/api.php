<?php

use App\Http\Controllers\ImportOffersController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/import-offers', [ImportOffersController::class, 'import']);
});
