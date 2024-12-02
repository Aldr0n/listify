<?php

namespace App\Livewire\Library;

use App\Contracts\Services\SearchServiceInterface;
use Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PlaylistSearch extends Component
{
    #[Validate('min:3|string|regex:/^[a-zA-Z0-9\s\-\.\/\:\_\?\=\&]+$/')]
    #[Validate(message: ['regex' => 'Search may only contain letters, numbers, common URL characters and hyphens'])]
    public string $search = '';

    public array $searchResults = [];
    private SearchServiceInterface $searchService;

    public function boot(SearchServiceInterface $searchService)
    {
        $this->searchService = $searchService;
    }

    public function render()
    {
        return view('livewire.library.playlist-search');
    }

    public function updated($property)
    {
        if ($property === 'search') {
            $this->search();
        }
    }

    protected function search(): void
    {
        if (strlen($this->search) < 3) {
            $this->searchResults = [];
            return;
        }

        $results = $this->searchService->search(
            $this->search,
            Auth::user()->getValidSpotifyToken()
        );

        $this->searchResults = collect($results)->keyBy('id')->all();
    }

    public function startImport(string $playlistId)
    {
        $this->dispatch('playlist-import-requested', playlist: $this->searchResults[$playlistId]);
    }

    #[Computed]
    public function results()
    {
        return $this->searchResults;
    }
}
