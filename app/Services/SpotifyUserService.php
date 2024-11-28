<?php

namespace App\Services;

use App\Contracts\Services\OauthTokenService;

class SpotifyUserService
{
    public function __construct(
        protected OauthTokenService $spotifyApiService,
    ) {}

    public function storeUserProfile(int $userId)
    {
        // TODO: Implement storeUserProfile() method.
    }
}
