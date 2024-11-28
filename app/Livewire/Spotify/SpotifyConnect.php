<?php

namespace App\Livewire\Spotify;

use App\Contracts\Services\OauthTokenManager;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SpotifyConnect extends Component
{
    private OauthTokenManager $spotifyService;

    public function __construct()
    {
        $this->spotifyService = app(OauthTokenManager::class);
    }

    public function render(): Factory|View
    {
        return view('livewire.pages.auth.spotify-connect', [
            'isConnected' => Auth::user()->spotifyToken !== NULL
        ]);
    }

    public function disconnect(): void
    {
        $this->spotifyService->removeCredentials(Auth::id());
        $this->dispatch('spotify-disconnected');
    }
}