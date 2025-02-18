<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Routes for Viewer (can only read)
    Route::get('/buku', [BukuController::class, 'index']);
    Route::get('/buku/{buku}', [BukuController::class, 'show']);

    // Routes for Editor (can create and edit)
    Route::middleware('role:editor,admin')->group(function () {
        Route::post('/buku', [BukuController::class, 'store']);
        Route::put('/buku/{buku}', [BukuController::class, 'update']);
    });

    // Routes for Admin (can delete)
    Route::middleware('role:admin')->group(function () {
        Route::delete('/buku/{buku}', [BukuController::class, 'destroy']);
    });
});
