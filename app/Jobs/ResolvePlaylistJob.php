<?php

namespace App\Jobs;

use App\Models\SpotifyToken;
use App\Services\LibraryService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ResolvePlaylistJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;

    public function __construct(
        private readonly array $playlistData,
        private readonly SpotifyToken $spotifyToken
    ) {}

    public function handle(LibraryService $libraryService): void
    {
        if ($this->batch()?->cancelled()) {
            Log::info('Job cancelled for playlist', [
                'playlist_id' => $this->playlistData['id'],
                'job_id'      => $this->job->getJobId(),
            ]);

            return;
        }

        try {
            $tracks = $libraryService->resolvePlaylistTracks($this->playlistData['id'], $this->spotifyToken);

            $playlistData        = $this->playlistData;
            $playlistData['map'] = $tracks->pluck('spotify_id');

            $playlist = $libraryService->resolvePlaylist($playlistData);
            $playlist->tracks()->sync($tracks->pluck('id'));
        }
        catch (\Exception $e) {
            Log::error('Failed to resolve playlist', [
                'playlist_id' => $this->playlistData['id'],
                'error'       => $e->getMessage(),
                'job_id'      => $this->job->getJobId(),
                'attempt'     => $this->attempts(),
            ]);
            throw $e;
        }
    }
}
