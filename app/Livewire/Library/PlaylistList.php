<?php

namespace App\Livewire\Library;

use App\Models\Playlist;
use Auth;
use Livewire\Component;

class PlaylistList extends Component
{
    public $playlists;

    public function mount()
    {
        $this->playlists = $this->getPlaylists();
    }

    public function render()
    {
        return view('livewire.library.playlist-list');
    }

    public function getPlaylists()
    {
        return Playlist::all();
    }
}
