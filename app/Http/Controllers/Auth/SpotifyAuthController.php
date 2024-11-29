<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Spotify\SpotifyAuthService;
use App\Services\Spotify\SpotifyUserService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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
        $this->spotifyAuthService->handleOauthCallback();

        return redirect()->route('dashboard')->with('status', 'Spotify connected successfully!');
    }
}