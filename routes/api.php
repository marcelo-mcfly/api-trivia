<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TriviaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('trivias')->group(function () {
    Route::post('/', [TriviaController::class, 'store']);
    Route::get('/', [TriviaController::class, 'listar']);
});

Route::post('/test', function() {
    return response()->json(['message' => 'Ruta test funcionando']);
});
