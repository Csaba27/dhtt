<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Statistics extends Component
{
    #[Reactive]
    public int|string|null $eventId = null;

    #[Locked]
    public ?Event $event = null;

    public function mount(): void
    {
        if (is_numeric($this->eventId) && ! $this->event) {
            $this->event = Event::with('hikes.participants')->find($this->eventId);
        }
    }

    public function loadStatistics(): array
    {
        if (! $this->event) {
            return [];
        }
        $event = $this->event;
        $onlineParticipants = $this->onlineParticipants($event);
        $onSiteParticipants = $this->onSiteParticipants($event);
        $startParticipants = $this->startParticipants($event);
        $arrivals = $this->arrivals($event);
        $notArrived = $this->notArrived($event);

        return [
            'onlineParticipants' => $onlineParticipants['data'],
            'totalOnlineParticipants' => $onlineParticipants['total'],
            'onSiteParticipants' => $onSiteParticipants['data'],
            'totalOnSiteParticipants' => $onSiteParticipants['total'],
            'startParticipants' => $startParticipants['data'],
            'totalStartParticipants' => $startParticipants['total'],
            'arrivals' => $arrivals['data'],
            'totalArrivals' => $arrivals['total'],
            'notArrived' => $notArrived['data'],
            'totalNotArrived' => $notArrived['total'],
        ];
    }

    private function onlineParticipants(Event $event): array
    {
        $hikes = $event->hikes;
        $data = [];
        $total = 0;
        foreach ($hikes as $hike) {
            $count = $hike->participants
                ->where('created_at', '<', $event->registration_end)
                ->where('event_id', $event->id)
                ->count();
            $data[] = ['name' => $hike->name, 'count' => $count];
            $total += $count;
        }

        return ['data' => $data, 'total' => $total];
    }

    private function onSiteParticipants(Event $event): array
    {
        $hikes = $event->hikes;
        $data = [];
        $total = 0;
        foreach ($hikes as $hike) {
            $count = $hike->participants
                ->where('created_at', '>', $event->registration_end)
                ->where('event_id', $event->id)
                ->count();
            $data[] = ['name' => $hike->name, 'count' => $count];
            $total += $count;
        }

        return ['data' => $data, 'total' => $total];
    }

    private function startParticipants(Event $event): array
    {
        $hikes = $event->hikes;
        $data = [];
        $total = 0;
        foreach ($hikes as $hike) {
            $count = $hike->participants
                ->where('number', '<>', 0)
                ->whereIn('status', ['started', 'completed'])
                ->where('start_time', '<>', '00:00:00')
                ->where('event_id', $event->id)
                ->count();
            $data[] = ['name' => $hike->name, 'count' => $count];
            $total += $count;
        }

        return ['data' => $data, 'total' => $total];
    }

    private function arrivals(Event $event): array
    {
        $hikes = $event->hikes;
        $data = [];
        $total = 0;
        foreach ($hikes as $hike) {
            $count = $hike->participants
                ->where('number', '<>', 0)
                ->where('status', 'completed')
                ->where('start_time', '<>', '00:00:00')
                ->where('finish_time', '<>', '00:00:00')
                ->where('event_id', $event->id)
                ->count();
            $data[] = ['name' => $hike->name, 'count' => $count];
            $total += $count;
        }

        return ['data' => $data, 'total' => $total];
    }

    private function notArrived(Event $event): array
    {
        $hikes = $event->hikes;
        $data = [];
        $total = 0;
        foreach ($hikes as $hike) {
            $count = $hike->participants
                ->where('number', '<>', 0)
                ->where('status', 'started')
                ->where('event_id', $event->id)
                ->count();
            $data[] = ['name' => $hike->name, 'count' => $count, 'id' => $hike->id];
            $total += $count;
        }

        return ['data' => $data, 'total' => $total];
    }

    #[On('refreshStatistics')]
    public function refreshStatistics(): void
    {
        $statistics = $this->loadStatistics();
        $this->dispatch('refreshStatisticsComplete', $statistics);
        $this->skipRender();
    }

    public function render(): View
    {
        $statistics = $this->loadStatistics();

        return view('livewire.admin.dhtt.statistics', compact('statistics'));
    }
}
