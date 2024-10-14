<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the Marketplace API',
    ]);
});

Route::get('/unauthorized', function () {
    return response()->json([
        'message' => 'Unauthorized',
    ], 401);
})->name('login');
