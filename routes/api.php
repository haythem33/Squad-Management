<?php

use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::name('api.')->group(function () {
        Route::apiResource('teams', TeamController::class);
        Route::apiResource('players', PlayerController::class);
    });
});
