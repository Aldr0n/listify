<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Spotify\SpotifyClientService;
use App\Services\Spotify\SpotifyUserService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SpotifyAuthController extends Controller
{
    public function __construct(
        private SpotifyClientService $spotifyClientService,
        private SpotifyUserService $spotifyUserService,
    ) {}

    public function redirect()
    {
        return Socialite::driver('spotify')
            ->scopes(['playlist-read-private', 'playlist-read-collaborative'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        $callbackPayload = Socialite::driver('spotify')->user();
        $credentials     = [
            'access_token'  => $callbackPayload->token,
            'refresh_token' => $callbackPayload->refreshToken,
            'expires_at'    => Carbon::now()->addSeconds($callbackPayload->expiresIn),
        ];
        $spotifyUser     = $callbackPayload->user;

        $this->spotifyClientService->storeCredentials(Auth::id(), $credentials);
        $this->spotifyUserService->storeUserProfile(Auth::id(), $spotifyUser);

        return redirect()->route('dashboard')->with('status', 'Spotify connected successfully!');
    }
}