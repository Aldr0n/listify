<?php

namespace App\Services\Spotify;

use App\Contracts\Services\SearchServiceInterface;
use Auth;

class SpotifySearchService implements SearchServiceInterface
{
    public function __construct(
        private readonly SpotifyClientService $spotifyClientService
    ) {}

    public function search(string $term, string $token): array
    {
        $token         = Auth::user()->getValidSpotifyToken();
        $sanitizedTerm = $this->sanitizeSearchTerm($term);
        $results       = $this->spotifyClientService->search($sanitizedTerm, $token);
        return $this->formatResults($results);
    }

    public function sanitizeSearchTerm(string $term): string
    {
        return htmlspecialchars($term, ENT_QUOTES, 'UTF-8');
    }

    public function formatResults(array $results): array
    {
        return collect($results['playlists']['items'])
            ->filter()
            ->map(function ($playlist)
            {
                $playlist                = (object) $playlist;
                $playlist->thumbnail_url = $playlist->images[0]['url'];
                return $playlist;
            })
            ->all();
    }
}
