<?php

namespace App\Services\Playlist;

use App\Contracts\Provider\PlaylistProvider;
use App\Models\Playlist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class PlaylistService implements PlaylistProvider
{
    public function getPlaylist(int $userId, string $playlistId): Playlist
    {
        return Playlist::where('user_id', $userId)->find($playlistId);
    }

    public function getPlaylists(int $userId): Collection
    {
        return Playlist::where('user_id', $userId)->get();
    }

    public function resolvePlaylist(array $playlistData): Playlist
    {
        $spotifyId = $playlistData['id'];

        if (!$spotifyId) {
            throw new \RuntimeException('Missing required spotify_id');
        }

        return Playlist::firstOrCreate(
            ['spotify_id' => $spotifyId],
            [
                'title'         => $playlistData['name'],
                'description'   => $playlistData['description'] ?? NULL,
                'thumbnail_url' => $playlistData['images'][0]['url'] ?? NULL,
                'user_id'       => $playlistData['user_id'] ?? Auth::id(),
                'map'           => $playlistData['map'] ?? NULL,
            ]
        );
    }
}