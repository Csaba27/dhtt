<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class NotArrivedList extends Component
{
    #[Locked]
    public ?Event $event = null;

    public int $selectedHikeId = 0;

    #[On('filterHike')]
    public function filterHike(int|string $hikeId): void
    {
        // Ugyanazon túra kiválasztásánál vissza megyünk az összesre
        if ($hikeId == $this->selectedHikeId) {
            $this->reset('selectedHikeId');
        } else {
            $this->selectedHikeId = (int) $hikeId;
        }
    }

    public function getNotArrivedParticipants(): Collection
    {
        if (! $this->event) {
            return collect();
        }

        $participants = Participant::where('number', '<>', 0)
            ->where('status', 'started')
            ->where('event_id', $this->event->id);

        // Kiválasztott túra alapján
        if ($this->selectedHikeId) {
            $participants->where('hike_id', $this->selectedHikeId);
        }

        return $participants->get();
    }

    public function markArrived(int $participantId): void
    {
        $participant = $this->getParticipant($participantId);
        if ($participant) {
            $participant->update([
                'status' => 'completed',
                'finish_time' => now()->format('H:i:s'),
            ]);
            $this->dispatch('refreshStatistics')->to(Statistics::class);
        }
    }

    public function markAbandoned(int $participantId): void
    {
        $participant = $this->getParticipant($participantId);
        if ($participant) {
            $participant->update([
                'status' => 'abandoned',
                'finish_time' => now()->format('H:i:s'),
            ]);
            $this->dispatch('refreshStatistics')->to(Statistics::class);
        }
    }

    protected function getParticipant(int $participantId): ?Participant
    {
        return Participant::where('id', $participantId)->where('status', 'started')->first();
    }

    protected function getHike()
    {
        return $this->selectedHikeId > 0 ? $this->event->hikes()->where('id', $this->selectedHikeId)->first() : null;
    }

    public function render(): View
    {
        $hike = $this->getHike();

        return view('livewire.admin.dhtt.not-arrived-list', compact('hike'));
    }
}
