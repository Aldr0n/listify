<?php

namespace App\Providers;

use App\Contracts\Services\ApiClientService;
use App\Contracts\Services\AppUserService;
use App\Contracts\Services\OauthTokenService;
use App\Services\Spotify\SpotifyAuthService;
use App\Services\Spotify\SpotifyClientService;
use App\Services\Spotify\SpotifyUserService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Setup Spotify services
        $this->app->bind(OauthTokenService::class, SpotifyAuthService::class);
        $this->app->bind(AppUserService::class, SpotifyUserService::class);
        $this->app->bind(ApiClientService::class, SpotifyClientService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event)
        {
            $event->extendSocialite('spotify', \SocialiteProviders\Spotify\Provider::class);
        });
    }
}
