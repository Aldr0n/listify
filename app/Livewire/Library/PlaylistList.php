<?php

namespace App\Livewire\Library;

use App\Models\Playlist;
use App\Services\ImageService;
use Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class PlaylistList extends Component
{
    public $playlists;

    public function boot(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function render()
    {
        return view('livewire.library.playlist-list');
    }

    #[On('playlist-sync-update')]
    public function getPlaylists()
    {
        $this->dispatch('$refresh');
    }
}
