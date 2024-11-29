<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Track extends Model
{
    /** @use HasFactory<\Database\Factories\TrackFactory> */
    use HasFactory;

    protected $fillable = [
        'spotify_id',
        'name',
        'duration_ms',
        'artists',
        'album',
        'thumbnail_url',
        'href',
        'popularity',
        'track_number',
        'explicit',
        'available_markets',
    ];

    protected $casts = [
        'artists'           => 'array',
        'album'             => 'array',
        'available_markets' => 'array',
    ];

    public function playlist(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class);
    }


}
