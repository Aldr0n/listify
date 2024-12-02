<?php

namespace App\Models;

use App\Contracts\Services\OauthTokenService;
use App\Models\SpotifyToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    private ?OauthTokenService $oauthTokenService = NULL;

    protected function getOauthTokenService(): OauthTokenService
    {
        return $this->oauthTokenService ??= app(OauthTokenService::class);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_spotify_connected',
        'spotify_user',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'spotify_user'      => 'array',
        ];
    }

    public function spotifyToken(): HasOne
    {
        return $this->hasOne(SpotifyToken::class);
    }

    /**
     * Get valid Spotify token
     * @return SpotifyToken
     */
    public function getValidSpotifyToken(): SpotifyToken
    {
        return $this->getOauthTokenService()->getValidToken($this->id);
    }
}
