<?php

namespace App\Services;

use App\Contracts\Services\PlaylistProvider;
use App\Contracts\Services\TrackProvider;
use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User;

class LibraryService implements PlaylistProvider, TrackProvider
{
    public function getPlaylist(int $userId, string $playlistId): Playlist
    {
        return Playlist::where('user_id', $userId)->find($playlistId);
    }

    public function getPlaylists(int $userId): Collection
    {
        return Playlist::where('user_id', $userId)->get();
    }

    public function getTrack(string $trackId): Track
    {
        return Track::find($trackId);
    }

    public function getTracks(): Collection
    {
        return Track::all();
    }

    /**
     * Search for a track or playlist by title, id, artist, etc.
     */
    public function search(string $query)
    {
        // TODO: Implement search() method.
    }

    public function syncPlaylists(User $user)
    {
        // TODO: Implement syncPlaylists() method.
    }
}
