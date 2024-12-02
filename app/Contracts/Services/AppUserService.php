<?php

namespace App\Contracts\Services;

interface AppUserService
{
    /**
     * Store Spotify user profile
     * @param array $spotifyUser
     * @return void
     */
    public function storeUserProfile(array $spotifyUser);

    /**
     * Delete current user profile
     * @return void
     */
    public function deleteUserProfile();
}
