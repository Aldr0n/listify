<?php

namespace App\Contracts\Services;

use App\Models\Playlist;
use Illuminate\Database\Eloquent\Collection;

interface PlaylistProvider
{
    /**
     * Get all user playlists
     */
    public function getPlaylists(int $userId): Collection;

    /**
     * Get a specific user playlist
     */
    public function getPlaylist(int $userId, string $playlistId): Playlist;
}

