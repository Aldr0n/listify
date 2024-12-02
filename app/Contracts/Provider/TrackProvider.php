<?php

namespace App\Contracts\Provider;

use App\Models\Track;
use Illuminate\Database\Eloquent\Collection;

interface TrackProvider
{
    /**
     * Get multiple tracks
     * @return Collection
     */
    public function getTracks(): Collection;

    /**
     * Get track by ID
     * @param string $trackId
     * @return Track
     */
    public function getTrack(string $trackId): Track;
}

