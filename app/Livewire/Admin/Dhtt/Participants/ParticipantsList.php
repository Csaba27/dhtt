<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt\Participants;

use App\Exports\ParticipantsExport;
use App\Models\Event;
use App\Models\Hike;
use App\Models\Participant;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ParticipantsList extends Component
{
    use WithPagination;

    #[Reactive]
    public int|string|null $eventId = null;

    #[Locked]
    public ?Event $event = null;

    #[url]
    public int|string $hike = '';

    #[Url(as: 'by')]
    public string $sortField = 'id';

    #[Url(as: 'asc')]
    public bool $sortAsc = false;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'perpage')]
    public int $paginate = 15;

    private function getEventId(): ?int
    {
        return $this->event?->getKey() ?? null;
    }

    public function mount(): void
    {
        $this->setPaginate($this->paginate);
        if (is_numeric($this->eventId) && ! $this->event) {
            $this->event = Event::find($this->eventId);
        }
    }

    public function builder(): Builder
    {
        if (! $this->sortField) {
            $this->reset('sortField');
        }

        $sortField = $this->sortField;
        if ($sortField == 'hike') {
            $sortField = 'hike_id';
        }

        $query = Participant::with(['association', 'hike']);
        if ($this->event) {
            $query = $query->where('event_id', $this->getEventId());
        }

        if (isset($this->hike) && is_numeric($this->hike)) {
            $query->where('hike_id', $this->hike);
        }

        return $query->orderBy($sortField, $this->sortAsc ? 'asc' : 'desc');
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        }

        $this->sortField = $field;
        $this->resetPage();
    }

    public function participants(): LengthAwarePaginator
    {
        $query = $this->builder();

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');

            if (is_numeric($this->search)) {
                $query->orWhere('number', $this->search);
            }
        }

        return $query->paginate($this->paginate);
    }

    #[Renderless]
    public function export(): false|BinaryFileResponse
    {
        $year = Carbon::now()->year;
        $name = 'Jelentkezok_'.$year.'.xlsx';

        try {
            $eventId = $this->getEventId();
            if (is_null($eventId)) {
                throw new \Exception('Válassz ki egy eseményt!');
            }

            $export = new ParticipantsExport($eventId);

            return Excel::download($export, $name);
        } catch (\Exception $e) {
            $this->js('alert(\''.e($e->getMessage()).'\')');
        }

        return false;
    }

    public function delete(string|int $id): void
    {
        $participant = Participant::find($id);
        if (is_null($participant)) {
            $this->js('alert(\'Ez a jelentkező nem létezik!\')');

            return;
        }
        $participant->delete();

        add_user_log([
            'title' => 'Jelentkező #'.$id,
            'link' => route('admin.dhtt.index', ['tab' => 'participats', 'event' => $this->getEventId()]),
            'reference_id' => auth()->id(),
            'section' => 'Jelentkező',
            'type' => 'Törlés',
        ]);

        flash('Jelentkező sikeresen törölve!')->success();
        $this->dispatch('close-modal');
    }

    public function updatedPaginate(mixed $value): void
    {
        $value = intval($value);
        $this->setPaginate($value);
        $this->resetPage();
    }

    public function setPaginate(int $value): void
    {
        $this->paginate = min(100, max(5, $value));
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset();
    }

    #[Computed]
    protected function hikes(): Collection
    {
        $event = $this->event;

        return $event
            ? $event->hikes->pluck('name', 'id')
            : Hike::pluck('name', 'id');
    }

    public function render(): View
    {
        $participants = $this->participants();

        return view('livewire.admin.dhtt.participants.list', [
            'participants' => $participants,
        ]);
    }
}
