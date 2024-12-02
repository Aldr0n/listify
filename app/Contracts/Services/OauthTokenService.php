<?php

namespace App\Contracts\Services;

use App\Models\SpotifyToken;

interface OauthTokenService
{
    /**
     * Store OAuth credentials
     * @param int $userId
     * @param array $credentials
     * @return SpotifyToken
     */
    public function storeCredentials(int $userId, array $credentials): SpotifyToken;

    /**
     * Remove user's OAuth credentials
     * @param int $userId
     * @return void
     */
    public function removeCredentials(int $userId): void;

    /**
     * Check if token requires refresh
     * @param SpotifyToken $token
     * @return bool
     */
    public function needsTokenRefresh(SpotifyToken $token): bool;

    /**
     * Refresh user's access token
     * @param int $userId
     * @return SpotifyToken
     */
    public function refreshToken(int $userId): SpotifyToken;

    /**
     * Get valid token for user
     * @param int $userId
     * @return SpotifyToken
     */
    public function getValidToken(int $userId): SpotifyToken;
}