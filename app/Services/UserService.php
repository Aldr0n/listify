<?php

namespace App\Services;

use Illuminate\Foundation\Auth\User;

class UserService
{
    public function __construct(
        protected SpotifyService $spotifyService,
    ) {}

    public function storeUserProfile(int $userId)
    {
        // TODO: Implement storeUserProfile() method.
    }
}
