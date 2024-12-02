<?php

namespace App\Livewire\Library;

use App\Models\Playlist;
use App\Services\Library\TrackService;
use Livewire\Component;

class PlaylistView extends Component
{
    public Playlist $playlist;
    public string $search = '';

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
        $tracks = $this->trackService->getTracksByPlaylistMap($this->playlist->map);

        if ($this->search) {
            $search = strtolower($this->search);
            $tracks = collect($tracks)->filter(function ($track) use ($search)
            {
                return str_contains(strtolower($track['name']), $search);
            });
        }

        return $tracks;
    }


}
