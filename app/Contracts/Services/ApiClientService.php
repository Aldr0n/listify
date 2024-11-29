<?php

namespace App\Contracts\Services;

use App\Models\SpotifyToken;

interface ApiClientService
{
    public function fetchUserPlaylists(string $spotifyUserId, SpotifyToken $token): array;

    public function fetchUserProfile(string $spotifyUserId, SpotifyToken $token): array;

    public function fetchPlaylistTracks(string $playlistId, SpotifyToken $token): array;

    public function refreshToken(int $userId): array;
}

