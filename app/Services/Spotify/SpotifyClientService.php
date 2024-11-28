<?php

namespace App\Services\Spotify;

use App\Contracts\Services\OauthTokenService;
use App\Models\SpotifyToken;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SpotifyClientService implements OauthTokenService
{
    protected string $baseUrl = 'https://api.spotify.com/v1';
    protected string $authUrl = 'https://accounts.spotify.com/api/token';

    public function storeCredentials(int $userId, array $credentials): void
    {
        SpotifyToken::updateOrCreate(['user_id' => $userId], $credentials);

        Auth::user()->update(['is_spotify_connected' => TRUE]);
    }

    public function removeCredentials(int $userId): void
    {
        SpotifyToken::where('user_id', $userId)->delete();

        Auth::user()->update(['is_spotify_connected' => FALSE]);
    }

    public function needsTokenRefresh(int $userId): bool
    {
        $token = $this->getToken($userId);

        if (!$token) {
            return FALSE;
        }

        return $token->expires_at->subMinutes(5)->isPast();
    }

    public function refreshToken(int $userId): void
    {
        $token           = SpotifyToken::where('user_id', $userId)->first();
        $basicAuthUser   = config('services.spotify.client_id');
        $basicAuthSecret = config('services.spotify.client_secret');

        if (!$token) {
            throw new Exception('No token found for user');
        }

        $response = Http::withBasicAuth($basicAuthUser, $basicAuthSecret)
            ->asForm()
            ->post(
                $this->authUrl,
                [
                    'grant_type'    => 'refresh_token',
                    'refresh_token' => $token->refresh_token,
                ]
            );

        if (!$response->successful()) {
            throw new Exception('Failed to refresh token: ' . $response->body());
        }

        $credentials = $response->json();

        $this->storeCredentials($userId, [
            'access_token'  => $credentials['access_token'],
            'refresh_token' => $credentials['refresh_token'] ?? $token->refresh_token,
            'expires_in'    => $credentials['expires_in'],
        ]);
    }

    public function fetchUserPlaylists(int $userId): array
    {
        $token = $this->getToken($userId);

        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/me/playlists");

        return $response->json();
    }

    public function fetchUserProfile(int $userId)
    {
        $token = $this->getToken($userId);

        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/users/smedjan");

        return $response->json();
    }

    protected function getToken(int $userId): SpotifyToken
    {
        $token = SpotifyToken::where('user_id', $userId)->first();

        if (!$token) {
            throw new Exception('No token found for user');
        }

        if ($this->needsTokenRefresh($userId)) {
            $this->refreshToken($userId);
        }

        return $token;
    }
}
