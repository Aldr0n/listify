<?php

namespace App\Livewire;

use App\Enums\MediaType;
use App\Services\ImageService;
use Livewire\Component;

class Test extends Component
{
    private ImageService $imageService;

    public function boot(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function render()
    {
        return view('livewire.util.test');
    }

    public function test() {}
}
