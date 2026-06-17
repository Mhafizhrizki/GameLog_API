<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GameTrackerController;
use App\Http\Controllers\Api\UserStatisticsController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/gamelogs', [GameTrackerController::class, 'index']);
    Route::post('/gamelogs', [GameTrackerController::class, 'store']);
    Route::put('/gamelogs/{id}', [GameTrackerController::class, 'update']);
    Route::delete('/gamelogs/{id}', [GameTrackerController::class, 'destroy']);
    
    Route::get('/user/statistics', [UserStatisticsController::class, 'getStatistics']);
});
