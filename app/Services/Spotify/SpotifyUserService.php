<?php

namespace App\Services;

use App\Contracts\Services\AppUserService;
use App\Contracts\Services\OauthTokenService;

class SpotifyUserService implements AppUserService
{
    public function __construct(
        protected OauthTokenService $spotifyApiService,
    ) {}

    public function storeUserProfile(int $userId)
    {
        // TODO: Implement storeUserProfile() method.
    }

    public function deleteUserProfile(int $userId)
    {
        // TODO: Implement deleteUserProfile() method.
    }
}
