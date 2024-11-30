<?php

namespace App\Services\Spotify;

use App\Contracts\Services\AppUserService;
use Illuminate\Support\Facades\Auth;

class SpotifyUserService implements AppUserService
{
    public function __construct(
        protected SpotifyResolutionService $resolutionService,
    ) {}

    public function storeUserProfile(array $spotifyUser): void
    {
        $spotifyUser = [
            'id'            => $spotifyUser['id'],
            'display_name'  => $spotifyUser['display_name'],
            'thumbnail_url' => $spotifyUser['images'][0]['url'],
        ];

        Auth::user()->update(['spotify_user' => $spotifyUser]);
    }

    public function deleteUserProfile(): void
    {
        Auth::user()->update(['spotify_user' => NULL]);
    }

    public function setConnectionStatus(bool $isConnected): void
    {
        Auth::user()->update(['is_spotify_connected' => $isConnected]);
    }

    public function syncUserPlaylists(array $playlists): void
    {
        $filteredPlaylists = array_filter($playlists['items'], function ($playlist)
        {
            return !is_null($playlist);
        });

        $this->resolutionService->resolvePlaylists($filteredPlaylists);
    }
}
