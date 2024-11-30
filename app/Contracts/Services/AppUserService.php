<?php

namespace App\Contracts\Services;

interface AppUserService
{
    public function storeUserProfile(array $spotifyUser);

    public function deleteUserProfile();
}
