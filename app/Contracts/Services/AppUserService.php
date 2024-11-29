<?php

namespace App\Contracts\Services;

interface AppUserService
{
    public function storeUserProfile(int $userId, array $spotifyUser);

    public function deleteUserProfile(int $userId);
}
