<?php

namespace App\Contracts\Services;

interface OauthTokenService
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
}