<?php

namespace App\Services\Library;

use App\Contracts\Provider\PlaylistProvider;
use App\Enums\MediaType;
use App\Models\Playlist;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function search(?string $searchTerm = NULL): LengthAwarePaginator
    {
        return Playlist::where('user_id', Auth::id())
            ->when($searchTerm, function ($query) use ($searchTerm)
            {
                $query->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('tracks', function ($query) use ($searchTerm)
                    {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    });
            })
            ->paginate(10);
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
            \Log::info('Fetched Thumbnail ID: ' . $thumbnail_id);
        }

        $thumbnail_url = $thumbnail_id ? $this->imageService->getImageUrl($thumbnail_id, MediaType::ALBUM_THUMBNAIL) : NULL;

        $playlist = [
            'title'         => $playlistData['name'],
            'description'   => $playlistData['description'] ?? NULL,
            'thumbnail_id'  => $thumbnail_id,
            'thumbnail_url' => $thumbnail_url,
            'user_id'       => $playlistData['user_id'] ?? Auth::id(),
            'map'           => $playlistData['map'] ?? NULL,
        ];

        return Playlist::updateOrCreate(
            ['spotify_id' => $spotifyId],
            $playlist
        );
    }

    public function handleThumbnail(string $thumbnailUrl): string
    {
        return $this->imageService->downloadImage($thumbnailUrl, MediaType::ALBUM_THUMBNAIL);
    }
}