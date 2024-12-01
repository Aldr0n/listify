<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Spotify\SpotifyAuthService;
use App\Services\Spotify\SpotifyUserService;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class SpotifyAuthController extends Controller
{
    public function __construct(
        private SpotifyUserService $spotifyUserService,
        private SpotifyAuthService $spotifyAuthService,
    ) {}

    public function redirect()
    {
        return Socialite::driver('spotify')
            ->scopes(['playlist-read-private', 'playlist-read-collaborative'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $this->spotifyAuthService->handleOauthCallback();
            return redirect()->route('playlists')->with('status', 'Spotify connected successfully!');
        }
        catch (\Exception $e) {
            return redirect()->route('playlists')->with('error', 'Failed to connect Spotify. Please try again.');
        }
    }
}