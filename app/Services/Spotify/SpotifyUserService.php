<?php

namespace App\Services\Spotify;

use App\Contracts\Services\AppUserService;
use App\Models\User;

class SpotifyUserService implements AppUserService
{
    public function storeUserProfile(int $userId, array $spotifyUser)
    {
        $spotifyUser = [
            'id'            => $spotifyUser['id'],
            'display_name'  => $spotifyUser['display_name'],
            'thumbnail_url' => $spotifyUser['images'][0]['url'],
        ];

        User::where('id', $userId)->update(['spotify_user' => $spotifyUser]);
    }

    public function deleteUserProfile(int $userId)
    {
        User::where('id', $userId)->update(['spotify_user' => NULL]);
    }

    public function updateSpotifyConnection(int $userId, bool $isConnected)
    {
        User::where('id', $userId)->update(['is_spotify_connected' => $isConnected]);
    }
}
