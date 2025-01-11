<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormulirController;
use App\Http\Controllers\BeritaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route untuk Formulir
Route::get('formulir', [FormulirController::class, 'index']);
Route::get('formulir/{id}', [FormulirController::class, 'show']);
Route::post('formulir', [FormulirController::class, 'store']);
Route::put('formulir/{id}', [FormulirController::class, 'update']);
Route::delete('formulir/{id}', [FormulirController::class, 'destroy']);

// Route untuk Berita
Route::get('berita', [BeritaController::class, 'index']);
Route::get('berita/{id}', [BeritaController::class, 'show']);
Route::post('berita', [BeritaController::class, 'store']);
Route::put('berita/{id}', [BeritaController::class, 'update']);
Route::delete('berita/{id}', [BeritaController::class, 'destroy']);