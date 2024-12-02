<?php

namespace App\Livewire\Library;

use App\Models\Playlist;
use App\Services\Library\TrackService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PlaylistView extends Component
{
    public Playlist $playlist;

    #[Validate('min:3|string|regex:/^[a-zA-Z0-9\s\-\.\/\:\_\?\=\&]+$/')]
    #[Validate(message: ['regex' => 'Search may only contain letters, numbers, common URL characters and hyphens'])]
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
