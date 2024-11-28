<?php

namespace App\Services;

use App\Contracts\Services\AppUserService;
use App\Services\Spotify\SpotifyClientService;

class SpotifyUserService implements AppUserService
{
    public function __construct(
        protected SpotifyClientService $spotifyClientService,
    ) {}

    public function storeUserProfile(int $userId)
    {
        $this->spotifyClientService->fetchUserProfile($userId);
    }

    public function deleteUserProfile(int $userId)
    {
        // TODO: Implement deleteUserProfile() method.
    }
}
