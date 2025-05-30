<?php

use App\Http\Controllers\PreguntasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TriviaController;
use App\Http\Controllers\RespuestasController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('trivias')->group(function () {
    Route::post('/crear', [TriviaController::class, 'crear']);
    Route::get('/listar', [TriviaController::class, 'listar']);
});

Route::prefix('preguntas')->group(function () {
    Route::post('/crear', [PreguntasController::class, 'crear']);
    Route::get('/listar', [PreguntasController::class, 'listar']);
});

Route::prefix('respuestas')->group(function () {
    Route::post('/crear', [RespuestasController::class, 'crear']);
    Route::get('/listar', [RespuestasController::class, 'listar']);
});

