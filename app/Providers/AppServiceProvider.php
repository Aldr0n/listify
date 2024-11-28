<?php

namespace App\Providers;

use App\Contracts\Services\SpotifyServiceContract;
use App\Services\SpotifyService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Setup Spotify service
        $this->app->bind(SpotifyServiceContract::class, SpotifyService::class);
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
