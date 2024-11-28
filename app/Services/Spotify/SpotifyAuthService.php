<?php

namespace App\Services\Spotify;

use App\Contracts\Services\OauthTokenService;
use App\Models\SpotifyToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class SpotifyAuthService implements OauthTokenService
{
    protected string $baseUrl = 'https://api.spotify.com/v1';

    public function storeCredentials(int $userId, array $credentials): void
    {
        SpotifyToken::updateOrCreate(
            ['user_id' => $userId],
            [
                'access_token'  => $credentials['access_token'],
                'refresh_token' => $credentials['refresh_token'],
                'expires_at'    => Carbon::now()->addSeconds($credentials['expires_in']),
            ]
        );
    }

    public function removeCredentials(int $userId): void
    {
        SpotifyToken::where('user_id', $userId)->delete();
    }

    public function needsTokenRefresh(int $userId): bool
    {
        $token = SpotifyToken::where('user_id', $userId)->first();

        if (!$token) {
            return FALSE;
        }

        return $token->expires_at->subMinutes(5)->isPast();
    }

    public function refreshToken(int $userId): void
    {
        // TODO: Implementation for refreshing the token
    }

    public function fetchUserPlaylists(int $userId): array
    {
        $token = SpotifyToken::where('user_id', $userId)->first();

        if ($this->needsTokenRefresh($userId)) {
            $this->refreshToken($userId);
        }

        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/me/playlists");

        return $response->json();
    }

    public function fetchUserProfile(int $userId)
    {
        // TODO: Implement fetchUserProfile() method.
    }
}