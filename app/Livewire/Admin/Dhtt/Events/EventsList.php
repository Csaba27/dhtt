<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt\Events;

use App\Models\Event;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class EventsList extends Component
{
    use WithPagination;

    #[Url(as: 'by')]
    public string $sortField = 'id';

    #[Url(as: 'asc')]
    public bool $sortAsc = false;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'perpage')]
    public int $paginate = 15;

    public function mount(): void
    {
        $this->setPaginate($this->paginate);
    }

    private function builder(): Builder
    {
        if (!$this->sortField) {
            $this->reset('sortField');
        }

        return Event::with('hikes')->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        }

        $this->sortField = $field;
        $this->resetPage();
    }

    private function events(): LengthAwarePaginator
    {
        $query = $this->builder();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
            $query->orWhere('short_name', 'like', '%' . $this->search . '%');
        }

        return $query->paginate($this->paginate);
    }

    public function delete($id): void
    {
        $event = Event::find($id);

        if (!$event) {
            $this->js('alert(\'Ez az esemény nem létezik!\')');

            return;
        }
        $event->delete();

        add_user_log([
            'title' => 'Esemény #' . $id,
            'link' => route('admin.dhtt.index'),
            'reference_id' => auth()->id(),
            'section' => 'Esemény lista',
            'type' => 'Törlés',
        ]);

        flash('Esemény sikeresen törölve')->success();
        $this->dispatch('close-modal');
    }

    public function close(int $id): void
    {
        $event = Event::find($id);

        if (!$event) {
            $this->js('alert(\'Ez az esemény nem létezik!\')');

            return;
        }

        if (!$event->active && $event->status != 3) {
            $this->js('alert(\'Ezt az esemény nem zárhatod le!\')');

            return;
        }

        DB::beginTransaction();

        $event->status = 4;
        $event->active = 0;
        $event->save();

        foreach (Participant::where('event_id', $event->id)->whereIn('status', ['started', 'completed'])->cursor() as $participant) {
            $start = new Carbon($participant->start_time);
            $end = new Carbon($participant->finish_time);

            if ($end->gt($start)) {
                $diffInSeconds = $end->diffInSeconds($start);
                $totalTime = Carbon::createFromTimestamp($diffInSeconds)->format('H:i:s');
                $participant->completion_time = $totalTime;
                $participant->save();
            }
        }

        add_user_log([
            'title' => 'Esemény #' . $id,
            'link' => route('admin.dhtt.index', ['tab' => 'events']),
            'reference_id' => auth()->id(),
            'section' => 'Esemény',
            'type' => 'Lezárás',
        ]);

        flash('Esemény sikeresen lezárva')->success();
        DB::commit();
    }

    #[Renderless]
    public function openModal(int $id): void
    {
        $this->dispatch('openModal', $id)->to(EventModal::class);
    }

    public function setEventActive(string $eventId, bool $active): void
    {
        $event = Event::find($eventId);
        if ($event) {
            $event->active = $active;
            $event->save();
        } else {
            $this->js('alert(\'Esemény nem található!\')');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPaginate($value): void
    {
        $value = intval($value);
        $this->setPaginate($value);
        $this->resetPage();
    }

    public function setPaginate(int $value): void
    {
        $this->paginate = min(100, max(5, $value));
    }

    public function resetFilters(): void
    {
        $this->reset();
    }

    public function render(): View
    {
        $events = $this->events();

        return view('livewire.admin.dhtt.events.list', compact('events'));
    }
}
