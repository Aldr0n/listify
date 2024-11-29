<?php

namespace App\Services;

use App\Contracts\Services\AppUserService;
use App\Services\Spotify\SpotifyClientService;

class SpotifyUserService implements AppUserService
{
    public function __construct(
        protected SpotifyClientService $spotifyClientService,
    ) {}

    public function storeUserProfile(int $userId, array $spotifyUser)
    {
        $this->spotifyClientService->fetchUserProfile($userId);
    }

    public function deleteUserProfile(int $userId)
    {
        // TODO: Implement deleteUserProfile() method.
    }

    public function updateSpotifyConnection(int $userId, bool $isConnected)
    {
        User::where('id', $userId)->update(['is_spotify_connected' => $isConnected]);
    }
}
