<?php

namespace App\Livewire\Admin\Dhtt\Events;

use App\Models\Event;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class EventModal extends Component
{
    public bool $open = false;

    #[Locked]
    public ?Event $event = null;

    #[On('openModal')]
    public function openModal(int $eventId): void
    {
        $this->open = true;
        $this->event = Event::find($eventId);
    }

    public function render(): View
    {
        return view('livewire.admin.dhtt.events.modal');
    }
}
