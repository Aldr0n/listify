<?php

namespace App\Services;

use App\Contracts\Provider\PlaylistProvider;
use App\Contracts\Provider\TrackProvider;
use App\Jobs\ResolvePlaylistJob;
use App\Models\Playlist;
use App\Models\SpotifyToken;
use App\Models\Track;
use App\Services\Spotify\SpotifyClientService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Log;

class LibraryService implements PlaylistProvider, TrackProvider
{

    public function __construct(
        protected SpotifyClientService $spotifyClientService,
    ) {}

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

    public function search(string $query)
    {
        // TODO: Implement search() method.
    }

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
                return $this->resolveTrack($trackItem);
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

    public function resolveTrack(array $trackObject): Track
    {
        $trackData = $trackObject['track'];

        return Track::firstOrCreate(
            ['spotify_id' => $trackData['id']],
            [
                'name'              => $trackData['name'],
                'duration_ms'       => $trackData['duration_ms'],
                'artists'           => json_encode($trackData['artists']),
                'album'             => json_encode($trackData['album']),
                'href'              => $trackData['href'],
                'popularity'        => $trackData['popularity'],
                'track_number'      => $trackData['track_number'],
                'explicit'          => $trackData['explicit'],
                'available_markets' => json_encode($trackData['available_markets']),
            ]
        );
    }
}
