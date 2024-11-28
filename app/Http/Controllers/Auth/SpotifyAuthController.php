<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Services\SpotifyServiceContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SpotifyAuthController extends Controller
{
    public function __construct(
        private SpotifyServiceContract $spotifyService
    ) {}

    public function redirect()
    {
        return Socialite::driver('spotify')
            ->scopes(['playlist-read-private', 'playlist-read-collaborative'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        $spotifyUser = Socialite::driver('spotify')->user();

        $this->spotifyService->storeCredentials(Auth::id(), [
            'access_token'  => $spotifyUser->token,
            'refresh_token' => $spotifyUser->refreshToken,
            'expires_in'    => $spotifyUser->expiresIn,
        ]);

        return redirect()->route('dashboard')->with('status', 'Spotify connected successfully!');
    }
}