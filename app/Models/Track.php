<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Track extends Model
{
    /** @use HasFactory<\Database\Factories\TrackFactory> */
    use HasFactory;

    protected $fillable = ['id', 'name', 'artist', 'album', 'thumbnail_url'];

    public function playlist(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class);
    }
}
