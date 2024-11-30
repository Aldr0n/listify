<?php

namespace App\Livewire\Spotify;

use App\Services\Spotify\SpotifyResolutionService;
use Livewire\Component;

class SyncPlaylists extends Component
{
    private SpotifyResolutionService $spotifyResolutionService;

    public string $batchId;
    public bool $isPolling = FALSE;
    public ?array $status = NULL;

    public function boot(SpotifyResolutionService $spotifyResolutionService)
    {
        $this->spotifyResolutionService = $spotifyResolutionService;
    }

    public function render()
    {
        return view('livewire.pages.auth.sync-playlists');
    }

    public function syncUserPlaylists(): void
    {
        $this->batchId   = $this->spotifyResolutionService->startPlaylistSync()->id;
        $this->isPolling = TRUE;
    }

    public function checkSyncStatus(): void
    {
        if (!isset($this->batchId)) return;

        $this->status = $this->spotifyResolutionService->checkSyncStatus($this->batchId);

        // Stop polling if the batch is finished or cancelled
        if ($this->status['finished'] || $this->status['cancelled']) {
            $this->isPolling = FALSE;
            $this->status    = NULL;
        }
    }
}
