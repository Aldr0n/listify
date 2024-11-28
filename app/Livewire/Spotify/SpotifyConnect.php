<?php

namespace App\Livewire\Spotify;

use App\Contracts\Services\SpotifyServiceContract;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SpotifyConnect extends Component
{
    private SpotifyServiceContract $spotifyService;

    public function __construct()
    {
        $this->spotifyService = app(SpotifyServiceContract::class);
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