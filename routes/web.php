<?php

use App\Http\Controllers\Auth\SpotifyAuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function ()
{
    Route::get('/auth/spotify', [SpotifyAuthController::class, 'redirect'])
        ->name('spotify.auth');

    Route::get('/auth/spotify/callback', [SpotifyAuthController::class, 'callback'])
        ->name('spotify.callback');
});

require __DIR__ . '/auth.php';
