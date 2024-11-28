<?php

namespace App\Contracts\Services;

interface PlaylistProvider
{
    /**
     * Get all user playlists
     */
    public function getPlaylists(int $userId): array;

    /**
     * Get a specific user playlist
     */
    public function getPlaylist(int $userId, string $playlistId): array;
}

