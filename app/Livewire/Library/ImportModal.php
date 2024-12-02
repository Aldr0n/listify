<?php

namespace App\Livewire\Library;

use Livewire\Component;

class ImportModal extends Component
{
    protected $queryString = ['checkImportUrl'];
    protected $listeners = ['openModal'];
    public $isOpen = FALSE;
    public string $checkImportUrl = '';

    // public function mount()
    // {
    //     // Listen for the openModal event to open the modal.
    // }

    public function openModal()
    {
        $this->isOpen = TRUE;
    }

    public function closeModal()
    {
        $this->isOpen = FALSE;
    }

    public function updated($property)
    {
        if ($property === 'checkImportUrl') {
            $this->checkImportUrl();
        }
    }

    public function checkImportUrl()
    {
        if (!empty($this->checkImportUrl)) {
            // For debugging, you could log the value
            \Log::info('URL being checked: ' . $this->checkImportUrl);
        }
    }

    public function render()
    {
        return view('livewire.library.import-modal');
    }
}
