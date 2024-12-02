<?php

namespace App\Contracts\Provider;

use App\Models\Playlist;
use Illuminate\Database\Eloquent\Collection;

interface PlaylistProvider
{
    /**
     * Get all user playlists
     * @param int $userId
     * @return Collection
     */
    public function getPlaylists(int $userId): Collection;

    /**
     * Get single playlist by ID
     * @param int $userId
     * @param string $playlistId
     * @return Playlist
     */
    public function getPlaylist(int $userId, string $playlistId): Playlist;
}

