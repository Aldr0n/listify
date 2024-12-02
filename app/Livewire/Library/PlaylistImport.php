<?php

namespace App\Livewire\Library;

use App\Services\Spotify\SpotifyResolutionService;
use Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Component;

class PlaylistImport extends Component
{
    private SpotifyResolutionService $spotifyResolutionService;

    public bool $isImporting = FALSE;
    public string $importStatus = '';
    public array $importQueue = [];
    public array $status = [];

    public function boot(SpotifyResolutionService $spotifyResolutionService)
    {
        $this->spotifyResolutionService = $spotifyResolutionService;
    }

    private function loadImportQueue(): void
    {
        $this->importQueue = Cache::get('running_import_batches_' . Auth::id(), []) ?? [];
    }

    private function saveImportQueue(): void
    {
        Cache::put(
            'running_import_batches_' . Auth::id(),
            $this->importQueue,
            now()->addHours(1)
        );
    }

    private function updateImportQueueStatus(): void
    {
        $totalBatches         = count($this->importQueue);
        $completedBatches     = 0;
        $completedPlaylistIds = [];

        foreach ($this->importQueue as $playlistId => $batchId) {
            $status = $this->spotifyResolutionService->checkSyncStatus($batchId);

            if ($this->isBatchComplete($status)) {
                $completedBatches++;
                $completedPlaylistIds[] = $playlistId;
            }
        }

        if ($completedBatches === $totalBatches && $totalBatches > 0) {
            foreach ($completedPlaylistIds as $playlistId) {
                unset($this->importQueue[$playlistId]);
            }
            $this->saveImportQueue();
            \Log::info('All batches completed, clearing import queue');
        }

        \Log::info("Polling import status: {$completedBatches}/{$totalBatches} completed");
        $this->importStatus = "Importing playlists: {$completedBatches}/{$totalBatches} completed";
    }

    private function isBatchComplete(array $status): bool
    {
        return $status['finished'] === TRUE
            || $status['cancelled'] === TRUE
            || $status['failed_jobs'] === $status['total_jobs'];
    }

    public function updateImportStatus(): void
    {
        $this->loadImportQueue();

        if (empty($this->importQueue)) {
            $this->isImporting  = FALSE;
            $this->importStatus = '';
            return;
        }

        $this->isImporting = TRUE;
        $this->updateImportQueueStatus();
    }

    #[On('playlist-import-requested')]
    public function handlePlaylistImportRequest(array $playlist): void
    {
        $this->loadImportQueue();

        if (isset($this->importQueue[$playlist['id']])) {
            return;
        }

        $importBatch                        = $this->spotifyResolutionService->resolvePlaylists([$playlist]);
        $this->importQueue[$playlist['id']] = $importBatch->id;
        $this->saveImportQueue();

        $this->isImporting = TRUE;
        $this->updateImportStatus();
    }

    public function render()
    {
        return view('livewire.library.playlist-import');
    }
}
