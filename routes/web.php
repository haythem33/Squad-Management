<?php

use App\Http\Controllers\MatchController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\Userzone\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| These routes are accessible without authentication.
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
| All routes below require authentication via the 'auth' middleware.
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard - Logged-in landing page
    Route::get('/dashboard', function () {
        return view('userzone.dashboard');
    })->name('dashboard');

    // Profile Management Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Team Resource Routes
    // Generates: teams.index, teams.create, teams.store, teams.show, teams.edit, teams.update, teams.destroy
    Route::resource('teams', TeamController::class);

    // Player Resource Routes
    // Generates: players.index, players.create, players.store, players.show, players.edit, players.update, players.destroy
    Route::resource('players', PlayerController::class);

    // Match Resource Routes
    // Generates: matches.index, matches.create, matches.store, matches.show, matches.edit, matches.update, matches.destroy
    Route::resource('matches', MatchController::class);

    // Match Lineup Management Routes
    Route::get('/matches/{match}/lineup', [MatchController::class, 'editLineup'])->name('matches.lineup.edit');
    Route::put('/matches/{match}/lineup', [MatchController::class, 'updateLineup'])->name('matches.lineup.update');
});

// Authentication Routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';
