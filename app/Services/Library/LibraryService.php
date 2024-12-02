<?php

namespace App\Services;

use App\Contracts\Provider\PlaylistProvider;
use App\Contracts\Provider\TrackProvider;
use App\Models\Playlist;
use App\Models\SpotifyToken;
use App\Models\Track;
use App\Services\Library\PlaylistService;
use App\Services\Library\TrackService;
use App\Services\Spotify\SpotifyResolutionService;
use Illuminate\Database\Eloquent\Collection;

class LibraryService implements PlaylistProvider, TrackProvider
{
    public function __construct(
        protected PlaylistService $playlistService,
        protected TrackService $trackService,
        protected SpotifyResolutionService $resolutionService,
    ) {}

    public function getPlaylist(int $userId, string $playlistId): Playlist
    {
        return $this->playlistService->getPlaylist($userId, $playlistId);
    }

    public function getPlaylists(int $userId): Collection
    {
        return $this->playlistService->getPlaylists($userId);
    }

    public function getTrack(string $trackId): Track
    {
        return $this->trackService->getTrack($trackId);
    }

    public function getTracks(): Collection
    {
        return $this->trackService->getTracks();
    }

    public function resolvePlaylists(array $playlists)
    {
        return $this->resolutionService->resolvePlaylists($playlists);
    }

    public function resolvePlaylist(array $playlistData): Playlist
    {
        return $this->playlistService->resolvePlaylist($playlistData);
    }

    public function resolvePlaylistTracks(string $playlistId, SpotifyToken $spotifyToken): Collection
    {
        return $this->resolutionService->resolvePlaylistTracks($playlistId, $spotifyToken);
    }

    public function resolveTrack(array $trackObject): Track
    {
        return $this->trackService->resolveTrack($trackObject);
    }
}
