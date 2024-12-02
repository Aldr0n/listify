<?php

namespace App\Services\Spotify;

use App\Jobs\ResolvePlaylistJob;
use App\Models\SpotifyToken;
use App\Models\Track;
use App\Services\Library\PlaylistService;
use App\Services\Library\TrackService;
use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SpotifyResolutionService
{
    public function __construct(
        protected SpotifyClientService $spotifyClientService,
        protected PlaylistService $playlistService,
        protected TrackService $trackService,
    ) {}

    public function startPlaylistSync(): Batch
    {
        $user      = Auth::user();
        $token     = $user->getValidSpotifyToken();
        $playlists = $this->spotifyClientService->fetchUserPlaylists($user->spotify_user['id'], $token);

        // Cleanup, spotify returns null items sometimes
        $playlists = array_filter($playlists['items'], function (?array $playlist)
        {
            return !is_null($playlist);
        });

        $batch = $this->resolvePlaylists($playlists, $token, $user->id);

        Cache::put(
            "spotify_sync_batch_{$user->id}",
            $batch->id,
            now()->addHours(1)
        );

        return $batch;
    }

    public function resolvePlaylists(array $playlists, ?SpotifyToken $token = NULL, ?int $userId = NULL): Batch
    {
        $token ??= Auth::user()->getValidSpotifyToken();
        $userId ??= Auth::id();

        $jobs = array_map(
            function (array $playlist) use ($token, $userId)
            {
                $playlist['user_id'] = $userId;
                return new ResolvePlaylistJob($playlist, $token);
            },
            $playlists
        );

        return Bus::batch($jobs)
            ->allowFailures()
            ->onQueue('playlists')
            ->dispatch();
    }

    public function resolvePlaylistTracks(string $playlistId, SpotifyToken $spotifyToken): Collection
    {
        $allTracks = [];
        $offset    = 0;

        do {
            $data = $this->spotifyClientService->fetchPlaylistTracks(
                $playlistId,
                $spotifyToken,
                [
                    'offset' => $offset,
                ]
            );

            if (empty($data['items'])) break;

            // Map track items to Track models
            $tracks = collect($data['items'])
                ->map(function (array $trackItem): ?Track
                {
                    try {
                        return $this->trackService->resolveTrack($trackItem);
                    }
                    catch (\Exception $e) {
                        \Log::error('Failed to resolve track', ['track' => $trackItem]);
                        return NULL;
                    }
                })
                ->filter() // Removes null values
                ->values(); // Reindex array after filtering

            $allTracks = array_merge($allTracks, $tracks->all());
            $offset += 100;

            Log::info('Resolved ' . count($tracks) . ' tracks for playlist ' . $playlistId);

        } while (
            isset($data['next']) &&
            $offset < $data['total']
        );

        return new Collection($allTracks);
    }

    public function checkSyncStatus(string $batchId): array
    {
        $batch = Bus::findBatch($batchId);

        return [
            'id'             => $batch->id,
            'total_jobs'     => $batch->totalJobs,
            'pending_jobs'   => $batch->pendingJobs,
            'failed_jobs'    => $batch->failedJobs,
            'processed_jobs' => $batch->processedJobs(),
            'progress'       => $batch->progress(),
            'finished'       => $batch->finished(),
            'cancelled'      => $batch->cancelled(),
        ];
    }

    public function getLatestRunningSyncBatch()
    {
        $batchId = Cache::get('spotify_sync_batch_' . Auth::id());

        if (isset($batchId)) {
            return Bus::findBatch($batchId);
        }

        return NULL;
    }

    public function getLatestRunningImportBatch()
    {
        $batchId = Cache::get('playlist_import_batch_' . Auth::id());

        if (isset($batchId)) {
            return Bus::findBatch($batchId);
        }

        return NULL;
    }
}