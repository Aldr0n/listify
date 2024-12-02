<?php

namespace App\Livewire\Library;

use App\Enums\MediaType;
use App\Services\ImageService;
use App\Services\Library\PlaylistService;
use Exception;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PlaylistList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    protected $queryString = ['search'];
    public string $search = '';

    private ImageService $imageService;
    private PlaylistService $playlistService;
    private $previousPlaylistHash = NULL;

    public function boot(ImageService $imageService, PlaylistService $playlistService)
    {
        $this->imageService    = $imageService;
        $this->playlistService = $playlistService;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $playlists = $this->playlistService->search($this->search);

        try {
            $playlists->through(function ($playlist)
            {
                $playlist->thumbnail_url = $playlist->thumbnail_id
                    ? $this->imageService->getImageUrl($playlist->thumbnail_id, MediaType::ALBUM_THUMBNAIL)
                    : NULL;
                return $playlist;
            });
        }
        catch (Exception $e) {
            logger()->error('Playlist error: ' . $e->getMessage());
            throw $e;
        }

        return view('livewire.library.playlist-list', [
            'playlists' => $playlists,
        ]);
    }

    #[On('playlist-sync-update')]
    public function getPlaylists()
    {
        $currentPlaylists = $this->playlistService->search($this->search);
        $currentHash      = md5(json_encode($currentPlaylists->pluck('updated_at')));

        if ($this->previousPlaylistHash !== $currentHash) {
            $this->previousPlaylistHash = $currentHash;
            $this->dispatch('playlist-grid-update');
        }
    }
}
