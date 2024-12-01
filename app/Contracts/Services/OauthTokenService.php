<?php

namespace App\Contracts\Services;

use App\Models\SpotifyToken;

interface OauthTokenService
{
    /**
     * Store Spotify credentials for a user
     */
    public function storeCredentials(int $userId, array $credentials): SpotifyToken;

    /**
     * Remove Spotify credentials for a user
     */
    public function removeCredentials(int $userId): void;

    /**
     * Check if the token needs refresh
     */
    public function needsTokenRefresh(SpotifyToken $token): bool;

    /**
     * Refresh the access token
     */
    public function refreshToken(int $userId): SpotifyToken;

    /**
     * Get the valid token
     */
    public function getValidToken(int $userId): SpotifyToken;
}