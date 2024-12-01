<?php

namespace App\Livewire\Library;

use App\Models\Playlist;
use App\Services\Track\TrackService;
use Livewire\Component;

class PlaylistView extends Component
{
    public Playlist $playlist;

    private TrackService $trackService;

    public function boot(TrackService $trackService)
    {
        $this->trackService = $trackService;
    }

    public function render()
    {
        return view('livewire.library.playlist-view', [
            'tracks' => $this->getTracks(),
        ]);
    }

    public function getTracks()
    {
        return $this->trackService->getTracksByPlaylistMap($this->playlist->map);
    }


}
