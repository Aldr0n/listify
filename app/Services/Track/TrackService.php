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