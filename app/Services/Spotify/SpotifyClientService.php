<?php

namespace App\Services\Spotify;

use App\Contracts\Services\ApiClientService;
use App\Models\SpotifyToken;
use App\Services\Spotify\SpotifyUserService;
use Exception;
use Illuminate\Support\Facades\Http;

class SpotifyClientService implements ApiClientService
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.spotify.api_base_url');
    }

    public function fetchUserPlaylists(string $spotifyUserId, SpotifyToken $token): array
    {
        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/users/{$spotifyUserId}/playlists");

        return $response->json();
    }

    public function fetchUserProfile(string $spotifyUserId, SpotifyToken $token): array
    {
        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/users/{$spotifyUserId}");

        if (!$response->successful()) {
            throw new \RuntimeException("Failed to fetch Spotify profile: {$response->status()} - {$response->body()}");
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new \RuntimeException('Spotify API returned invalid data format');
        }

        return $data;
    }

    public function fetchPlaylistTracks(string $playlistId): array
    {
        return [];
    }

    public function refreshToken(int $userId): array
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
                config('services.spotify.api_auth_url'),
                [
                    'grant_type'    => 'refresh_token',
                    'refresh_token' => $token->refresh_token,
                ]
            );

        if (!$response->successful()) {
            throw new Exception('Failed to refresh token: ' . $response->body());
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new \RuntimeException('Spotify API returned invalid data format');
        }

        return $data;
    }
}
