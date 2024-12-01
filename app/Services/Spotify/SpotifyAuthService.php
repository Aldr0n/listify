<?php

namespace App\Services\Spotify;

use App\Contracts\Services\ApiClientService;
use App\Contracts\Services\AppUserService;
use App\Contracts\Services\OauthTokenService;
use App\Models\SpotifyToken;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Socialite;

class SpotifyAuthService implements OauthTokenService
{
    public function __construct(
        protected ApiClientService $spotifyClientService,
        protected AppUserService $spotifyUserService,
    ) {}

    public function storeCredentials(int $userId, array $credentials): SpotifyToken
    {
        $token = SpotifyToken::updateOrCreate(['user_id' => $userId], $credentials);
        Auth::user()->update(['is_spotify_connected' => TRUE]);

        \Log::info('Spotify credentials stored for user', ['user_id' => $userId]);

        return $token;
    }

    public function removeCredentials(int $userId): void
    {
        SpotifyToken::where('user_id', $userId)->delete();
        Auth::user()->update(['is_spotify_connected' => FALSE]);

        \Log::info('Spotify credentials removed for user', ['user_id' => $userId]);
    }

    public function getValidToken(int $userId): SpotifyToken
    {
        $token = SpotifyToken::where('user_id', $userId)->first();

        if (!$token) {
            throw new Exception('No token found for user');
        }

        if ($this->needsTokenRefresh($token)) {
            return $this->refreshToken($userId);
        }

        return $token;
    }

    public function refreshToken(int $userId): SpotifyToken
    {
        $credentials               = $this->spotifyClientService->refreshToken($userId);
        $credentials['expires_at'] = Carbon::now()->addSeconds($credentials['expires_in'])->subMinutes(5);
        $credentials['created_at'] = Carbon::now();

        \Log::info('Spotify token refreshed for user', ['user_id' => $userId]);

        return $this->storeCredentials($userId, $credentials);
    }

    public function needsTokenRefresh(SpotifyToken $token): bool
    {
        return $token->expires_at->isPast();
    }

    public function handleOauthCallback()
    {
        $credentialsResponse = Socialite::driver('spotify')->user();
        $credentials         = [
            'access_token'  => $credentialsResponse->token,
            'refresh_token' => $credentialsResponse->refreshToken,
            'expires_at'    => Carbon::now()->addSeconds($credentialsResponse->expiresIn)->subMinutes(5),
        ];
        $spotifyUser         = $credentialsResponse->user;

        $this->storeCredentials(Auth::id(), $credentials);
        $this->spotifyUserService->storeUserProfile($spotifyUser);
    }
}

