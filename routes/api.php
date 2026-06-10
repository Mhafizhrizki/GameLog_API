<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GameTrackerController;

// Endpoint Autentikasi (Public)
Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Endpoint yang Membutuhkan Autentikasi (Terkunci Sanctum)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // GameLogs CRUD
    Route::get('/gamelogs', [GameTrackerController::class, 'index']);
    Route::post('/gamelogs', [GameTrackerController::class, 'store']);
    Route::put('/gamelogs/{id}', [GameTrackerController::class, 'update']);
    Route::delete('/gamelogs/{id}', [GameTrackerController::class, 'destroy']);
});