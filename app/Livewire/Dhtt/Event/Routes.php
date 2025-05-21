<?php

declare(strict_types=1);

namespace App\Livewire\Dhtt\Event;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Routes extends Component
{
    #[Locked]
    public ?Event $event = null;

    public function render(): View
    {
        $event = $this->event;
        $hikes = $event?->hikes()->orderBy('name')->get();

        return view('livewire.dhtt.event.routes', compact('hikes'));
    }
}
