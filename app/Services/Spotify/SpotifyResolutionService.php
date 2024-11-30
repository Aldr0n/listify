<?php

namespace App\Services\Spotify;

use App\Jobs\ResolvePlaylistJob;
use App\Models\SpotifyToken;
use App\Models\Track;
use App\Services\Playlist\PlaylistService;
use App\Services\Track\TrackService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class SpotifyResolutionService
{
    public function __construct(
        protected SpotifyClientService $spotifyClientService,
        protected PlaylistService $playlistService,
        protected TrackService $trackService,
    ) {}

    public function resolvePlaylists(array $playlists)
    {
        $token  = Auth::user()->getValidSpotifyToken();
        $userId = Auth::id();

        $chain = array_map(
            function (array $playlist) use ($token, $userId)
            {
                return $this->createResolvePlaylistJob($playlist, $token, $userId);
            },
            $playlists
        );

        Bus::chain($chain)
            ->onQueue('playlists')
            ->dispatch();

        return TRUE;
    }

    private function createResolvePlaylistJob(array $playlistData, SpotifyToken $token, int $userId): ResolvePlaylistJob
    {
        $playlistData['user_id'] = $userId;
        return new ResolvePlaylistJob($playlistData, $token);
    }

    public function resolvePlaylistTracks(string $playlistId, SpotifyToken $spotifyToken): Collection
    {
        $allTracks = [];
        $offset    = 0;

        do {
            $data = $this->spotifyClientService->fetchPlaylistTracks(
                $playlistId,
                $spotifyToken,
                [
                    'offset' => $offset,
                ]
            );

            // Map track items to Track models
            $tracks = collect($data['items'])->map(function (array $trackItem): Track
            {
                return $this->trackService->resolveTrack($trackItem);
            });

            $allTracks = array_merge($allTracks, $tracks->all());
            $offset += 100;

            Log::info('Resolved ' . count($tracks) . ' tracks for playlist ' . $playlistId);

        } while (
            isset($data['next']) &&
            $offset < $data['total']
        );

        return new Collection($allTracks);
    }
}