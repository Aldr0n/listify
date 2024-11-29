<?php

namespace App\Contracts\Provider;

use App\Models\Track;
use Illuminate\Database\Eloquent\Collection;

interface TrackProvider
{
    /**
     * Get multiple tracks
     */
    public function getTracks(): Collection;

    /**
     * Get a specific track
     */
    public function getTrack(string $trackId): Track;
}

