<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\LivroController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('autores', AutorController::class)->only(['index', 'show'])->parameters(['autores' => 'autor']);
Route::apiResource('categorias', CategoriaController::class)->only(['index', 'show']);
Route::apiResource('livros', LivroController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::apiResource('autores', AutorController::class)->except(['index', 'show'])->parameters(['autores' => 'autor']);
    Route::apiResource('categorias', CategoriaController::class)->except(['index', 'show']);
    Route::apiResource('livros', LivroController::class)->except(['index', 'show']);
});
