<?php

namespace App\Livewire\Library;

use App\Enums\MediaType;
use App\Models\Playlist;
use App\Services\ImageService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PlaylistList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    private ImageService $imageService;

    public function boot(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function render()
    {
        $playlists = Playlist::where('user_id', Auth::id())
            ->paginate(10);

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
        $this->dispatch('$refresh');
    }
}
