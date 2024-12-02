<?php

namespace App\Enums;

enum SpotifySearchType: string
{
    case PLAYLIST  = 'playlist';
    case TRACK     = 'track';
    case ARTIST    = 'artist';
    case ALBUM     = 'album';
    case SHOW      = 'show';
    case EPISODE   = 'episode';
    case AUDIOBOOK = 'audiobook';

    public function getSearchType(): string
    {
        return $this->value;
    }
}
