<?php

namespace App\Services\Playlist;

use App\Contracts\Provider\PlaylistProvider;
use App\Enums\MediaType;
use App\Models\Playlist;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class PlaylistService implements PlaylistProvider
{
    public function __construct(
        protected ImageService $imageService,
    ) {}

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
        $spotifyId     = $playlistData['id'];
        $thumbnail_id  = NULL;
        $thumbnail_url = $playlistData['images'][0]['url'] ?? NULL;

        if (!$spotifyId) {
            throw new \RuntimeException('Missing required spotify_id');
        }

        if ($thumbnail_url) {
            $thumbnail_id = $this->handleThumbnail($thumbnail_url);
        }

        $playlist = [
            'title'        => $playlistData['name'],
            'description'  => $playlistData['description'] ?? NULL,
            'thumbnail_id' => $thumbnail_id,
            'user_id'      => $playlistData['user_id'] ?? Auth::id(),
            'map'          => $playlistData['map'] ?? NULL,
        ];

        return Playlist::firstOrCreate(
            ['spotify_id' => $spotifyId],
            $playlist
        );
    }

    public function handleThumbnail(string $thumbnailUrl): string
    {
        return $this->imageService->downloadImage($thumbnailUrl, MediaType::ALBUM_THUMBNAIL);
    }
}