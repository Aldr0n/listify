<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_spotify_connected',
        'spotify_user',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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

    public function getValidSpotifyToken(): SpotifyToken
    {
        return $this->getOauthTokenService()->getValidToken($this->id);
    }
}
