<?php

namespace App\Providers;

use App\Contracts\Services\AppUserService;
use App\Contracts\Services\OauthTokenService;
use App\Services\Spotify\SpotifyClientService;
use App\Services\SpotifyUserService;
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
        $this->app->bind(OauthTokenService::class, SpotifyClientService::class);
        $this->app->bind(AppUserService::class, SpotifyUserService::class);
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
