<?php

namespace App\Services\Spotify;

use App\Services\Spotify\SpotifyUserService;
use Illuminate\Support\Facades\Http;

class SpotifyClientService
{
    private $baseUrl;

    public function __construct(
        protected SpotifyAuthService $spotifyAuthService,
        protected SpotifyUserService $spotifyUserService,
    ) {
        $this->baseUrl = config('services.spotify.api_url');
    }

    public function storeCredentials(int $userId, array $credentials): void
    {
        $this->spotifyAuthService->storeCredentials($userId, $credentials);
        $this->spotifyUserService->updateSpotifyConnection($userId, TRUE);
    }

    public function removeCredentials(int $userId): void
    {
        $this->spotifyAuthService->removeCredentials($userId);
        $this->spotifyUserService->updateSpotifyConnection($userId, FALSE);
    }

    public function fetchUserPlaylists(int $userId): array
    {
        $token = $this->spotifyAuthService->getValidToken($userId);

        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/me/playlists");

        return $response->json();
    }

    public function fetchUserProfile(int $userId)
    {
        $token = $this->spotifyAuthService->getValidToken($userId);

        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/users/smedjan");

        return $response->json();
    }
}
