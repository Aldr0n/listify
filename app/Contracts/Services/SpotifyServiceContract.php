<?php

namespace App\Contracts\Services;

interface SpotifyServiceContract
{
    /**
     * Store Spotify credentials for a user
     */
    public function storeCredentials(int $userId, array $credentials): void;

    /**
     * Remove Spotify credentials for a user
     */
    public function removeCredentials(int $userId): void;

    /**
     * Check if the token needs refresh
     */
    public function needsTokenRefresh(int $userId): bool;

    /**
     * Refresh the access token
     */
    public function refreshToken(int $userId): void;

    /**
     * Get user's playlists
     */
    public function getPlaylists(int $userId): array;

    /**
     * Get a specific playlist
     */
    public function getPlaylist(int $userId, string $playlistId): array;
}