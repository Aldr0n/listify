<?php

namespace App\Services\Track;

use App\Contracts\Provider\TrackProvider;
use App\Models\Track;
use Illuminate\Database\Eloquent\Collection;

class TrackService implements TrackProvider
{
    public function getTrack(string $trackId): Track
    {
        return Track::find($trackId);
    }

    public function getTracks(): Collection
    {
        return Track::all();
    }

    public function getTracksByPlaylistMap(array $playlistMap): Collection
    {
        $tracks   = Track::whereIn('spotify_id', $playlistMap)->get();
        $trackMap = $tracks->keyBy('spotify_id');

        return collect($playlistMap)
            ->map(function ($spotifyId, $index) use ($trackMap)
            {
                if ($track = $trackMap[$spotifyId] ?? NULL) {
                    // Clone the track to prevent sharing the same instance
                    // This took way too long to figure out
                    $track               = clone $track;
                    $track->track_number = $index + 1;
                }
                return $this->transformTrack($track);
            })
            ->filter()
            ->pipe(fn($tracks) => new Collection($tracks));
    }

    private function transformTrack(?Track $track): ?Track
    {
        if (!$track) {
            return NULL;
        }

        $track->album = $this->formatAlbums($track);

        $track->duration = $this->formatDuration($track->duration_ms);

        return $track;
    }

    private function formatDuration(int $milliseconds): string
    {
        $minutes = floor($milliseconds / 60000);
        $seconds = floor(($milliseconds % 60000) / 1000);

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function formatAlbums(Track $track)
    {
        $albumData = is_string($track->album) ? json_decode($track->album, TRUE) : $track->album;

        return collect($albumData)->only([
            'name',
            'images',
            'release_date',
            'id',
            'uri',
            'artists',
        ]);
    }

    public function resolveTrack(array $trackObject): Track
    {
        $trackData = $trackObject['track'];

        if (!$trackData['id']) {
            \Log::error('No Spotify ID found in track data', ['track' => $trackObject]);
            throw new \Exception('No Spotify ID found in track data');
        }

        return Track::firstOrCreate(
            ['spotify_id' => $trackData['id']],
            [
                'name'              => $trackData['name'],
                'duration_ms'       => $trackData['duration_ms'],
                'artists'           => json_encode($trackData['artists']),
                'album'             => json_encode($trackData['album']),
                'href'              => $trackData['href'],
                'popularity'        => $trackData['popularity'],
                // 'track_number'      => $trackData['track_number'],
                'explicit'          => $trackData['explicit'],
                'available_markets' => json_encode($trackData['available_markets']),
            ]
        );
    }
}