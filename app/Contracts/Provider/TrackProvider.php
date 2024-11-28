<?php

namespace App\Contracts\Services;

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

