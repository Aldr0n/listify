<?php

namespace App\Services\Spotify;

use App\Contracts\Services\ApiClientService;
use App\Enums\SpotifySearchType;
use App\Models\SpotifyToken;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class SpotifyClientService implements ApiClientService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.spotify.api_base_url');
    }

    public function fetchUserPlaylists(string $spotifyUserId, SpotifyToken $token): array
    {
        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/users/{$spotifyUserId}/playlists");

        return $this->validateResponse($response);
    }

    public function fetchUserProfile(string $spotifyUserId, SpotifyToken $token): array
    {
        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/users/{$spotifyUserId}");

        return $this->validateResponse($response);
    }

    public function fetchPlaylistTracks(string $playlistId, SpotifyToken $token, array $params = []): array
    {
        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/playlists/{$playlistId}/tracks", $params);

        return $this->validateResponse($response);
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

        return $this->validateResponse($response);
    }

    public function search(string $query, SpotifyToken $token, ?array $params = [], SpotifySearchType $type = SpotifySearchType::PLAYLIST): array
    {
        $params = [...$params, 'q' => $query, 'limit' => 50, 'type' => $type->getSearchType()];

        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/search", $params);

        return $this->validateResponse($response);
    }

    private function validateResponse(Response $response): array
    {

        if (!$response->successful()) {
            throw new Exception('Unsuccessful response from Spotify API: ' . $response->body());
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new \RuntimeException('Invalid data format from Spotify API: ' . $response->body());
        }

        return $data;
    }
}
