<?php

namespace App\Livewire\Spotify;

use App\Contracts\Services\OauthTokenService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SpotifyConnect extends Component
{
    private OauthTokenService $spotifyAuthService;

    public function boot(OauthTokenService $spotifyAuthService)
    {
        $this->spotifyAuthService = $spotifyAuthService;
    }

    public function render(): Factory|View
    {
        return view('livewire.pages.auth.spotify-connect', [
            'isConnected' => Auth::user()->spotifyToken !== NULL
        ]);
    }

    public function disconnect(): void
    {
        $this->spotifyAuthService->removeCredentials(Auth::id());
        $this->dispatch('spotify-disconnected');
    }
}