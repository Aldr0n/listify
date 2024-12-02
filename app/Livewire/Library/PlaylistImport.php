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

    public function mount()
    {

        $this->isImporting = TRUE;

        // Restore queue from cache
        $this->importQueue = Cache::get('running_import_batches_' . Auth::id(), []);

        \Log::info('Import queue: ' . print_r($this->importQueue, TRUE));

        if ($this->importQueue === NULL || empty($this->importQueue) || !is_array($this->importQueue)) {
            $this->importQueue = [];
            return;
        }
        $this->updateImportStatus();
    }

    #[On('playlist-import-requested')]
    public function handlePlaylistImportRequest(array $playlist)
    {
        $importQueue = Cache::get('running_import_batches_' . Auth::id());

        if (empty($importQueue) || !is_array($importQueue) || $importQueue === NULL) {
            $this->importQueue = [];
        }

        if (isset($importQueue[$playlist['id']])) {
            return;
        }

        $importBatch = $this->spotifyResolutionService->resolvePlaylists([$playlist]);

        $importQueue[$playlist['id']] = $importBatch->id;

        Cache::put(
            'running_import_batches_' . Auth::id(),
            $importQueue,
            now()->addHours(1)
        );

        $this->isImporting = TRUE;
        $this->updateImportStatus();
    }

    public function updateImportStatus()
    {
        $localImportQueue = Cache::get('running_import_batches_' . Auth::id(), []);

        if (empty($localImportQueue)) {
            $this->isImporting  = FALSE;
            $this->importStatus = '';
            return;
        }

        $totalBatches     = count($localImportQueue);
        $completedBatches = 0;

        foreach ($this->importQueue as $playlistId => $batchId) {
            $status = $this->spotifyResolutionService->checkSyncStatus($batchId);

            if ($status['finished'] === TRUE || $status['cancelled'] === TRUE || $status['failed_jobs'] === $status['total_jobs']) {
                unset($this->importQueue[$playlistId]);
                $completedBatches++;
                \Log::info('Unsetting batch ' . $batchId);

                Cache::put(
                    'running_import_batches_' . Auth::id(),
                    $this->importQueue,
                    now()->addHours(1)
                );
            }
        }

        $this->importStatus = "Importing playlists: {$completedBatches}/{$totalBatches} completed";
    }

    public function render()
    {
        return view('livewire.library.playlist-import');
    }
}
