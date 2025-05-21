<?php

declare(strict_types=1);

namespace App\Livewire\Dhtt\Event;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Results extends Component
{
    #[Locked]
    public ?Event $event = null;

    public function render(): View
    {
        $event = $this->event;
        $hikes = null;

        if ($event && $event->date <= now()->format('Y-m-d')) {
            $hikes = $event->hikes()->orderBy('name')->get();

            $participants = Participant::where('event_id', $event->id)
                ->whereIn('status', ['completed', 'abandoned'])
                ->orderBy('status', 'asc') // akik beérkeztek elől jelennek meg
                ->orderBy('completion_time', 'asc') // szintidő alapján növekvőbe
                ->get()
                ->groupBy('hike_id');

            $hikes = $hikes->map(function ($hike) use ($participants) {
                return [
                    'name' => $hike->name,
                    'hike_type' => $hike->hike_type_id,
                    'participants' => $participants->get($hike->id, collect()),
                ];
            })->toArray();
        }

        return view('livewire.dhtt.event.results', compact('event', 'hikes'));
    }
}
