<?php

namespace App\Services;

use App\Contracts\Services\SpotifyServiceContract;
use App\Models\SpotifyToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class SpotifyService implements SpotifyServiceContract
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
        // Implementation for refreshing the token
        // Will add this in the next step
    }

    public function getPlaylists(int $userId): array
    {
        $token = SpotifyToken::where('user_id', $userId)->first();

        if ($this->needsTokenRefresh($userId)) {
            $this->refreshToken($userId);
        }

        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/me/playlists");

        return $response->json();
    }

    public function getPlaylist(int $userId, string $playlistId): array
    {
        $token = SpotifyToken::where('user_id', $userId)->first();

        if ($this->needsTokenRefresh($userId)) {
            $this->refreshToken($userId);
        }

        $response = Http::withToken($token->access_token)
            ->get("{$this->baseUrl}/playlists/{$playlistId}");

        return $response->json();
    }
}