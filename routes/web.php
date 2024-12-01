<?php

use App\Http\Controllers\Auth\SpotifyAuthController;
use App\Http\Controllers\PlaylistController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function ()
{
    Route::get('/auth/spotify', [SpotifyAuthController::class, 'redirect'])
        ->name('spotify.auth');

    Route::get('/auth/spotify/callback', [SpotifyAuthController::class, 'callback'])
        ->name('spotify.callback');

    Route::controller(PlaylistController::class)->group(function ()
    {
        Route::get('playlists', 'index')->name('playlists');
        Route::get('playlists/{playlist}', 'show')->name('playlist.show');
    });
});

require __DIR__ . '/auth.php';
