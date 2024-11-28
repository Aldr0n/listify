<?php

namespace App\Livewire\Spotify;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SpotifyConnect extends Component
{
    public function render(): Factory|View
    {
        return view('livewire.pages.auth.spotify-connect', [
            'isConnected' => Auth::user()->spotifyToken !== NULL
        ]);
    }

    public function disconnect(): void
    {
        Auth::user()->spotifyToken?->delete();
        $this->dispatch('spotify-disconnected');
    }
}