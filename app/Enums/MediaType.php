<?php

namespace App\Enums;

enum MediaType: string
{
    case ALBUM_THUMBNAIL = 'albums';
    case USER_THUMBNAIL  = 'users';

    public function getLocation(): string
    {
        return match ($this) {
            self::ALBUM_THUMBNAIL => 'albums',
            self::USER_THUMBNAIL  => 'users',
        };
    }
}
