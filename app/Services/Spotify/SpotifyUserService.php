<?php

namespace App\Services\Spotify;

use App\Contracts\Services\AppUserService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SpotifyUserService implements AppUserService
{
    public function storeUserProfile(int $userId, array $spotifyUser): void
    {
        $spotifyUser = [
            'id'            => $spotifyUser['id'],
            'display_name'  => $spotifyUser['display_name'],
            'thumbnail_url' => $spotifyUser['images'][0]['url'],
        ];

        Auth::user()->update(['spotify_user' => $spotifyUser]);
    }

    public function deleteUserProfile(int $userId): void
    {
        Auth::user()->update(['spotify_user' => NULL]);
    }

    public function setConnectionStatus(int $userId, bool $isConnected): void
    {
        Auth::user()->update(['is_spotify_connected' => $isConnected]);
    }
    }
}
