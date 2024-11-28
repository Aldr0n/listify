<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SpotifyAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('spotify')
            ->scopes(['playlist-read-private', 'playlist-read-collaborative'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        $spotifyUser = Socialite::driver('spotify')->user();

        dd($spotifyUser);

        Auth::user()->spotifyToken()->updateOrCreate(
            ['user_id' => Auth::user()->id],
            [
                'access_token'  => $spotifyUser->token,
                'refresh_token' => $spotifyUser->refreshToken,
                'expires_at'    => Carbon::now()->addSeconds($spotifyUser->expiresIn),
            ]
        );

        return redirect()->route('dashboard')->with('status', 'Spotify connected successfully!');
    }
}