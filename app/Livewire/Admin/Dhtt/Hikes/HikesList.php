<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt\Hikes;

use App\Models\Event;
use App\Models\Hike;
use App\Models\HikeType;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class HikesList extends Component
{
    use WithPagination;

    #[Reactive]
    public int|string|null $eventId = null;

    #[Locked]
    public ?Event $event = null;

    #[Url(as: 'by')]
    public string $sortField = 'id';

    #[Url(as: 'asc')]
    public bool $sortAsc = false;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'type', except: '')]
    public string $hikeType = '';

    #[Url(except: '')]
    public string $distance = '';

    #[Locked]
    public int $paginate = 15;

    private function getEventId()
    {
        return $this->event?->getKey() ?? null;
    }

    public function mount(): void
    {
        if (is_numeric($this->eventId) && ! $this->event) {
            $this->event = Event::find($this->eventId);
        }
    }

    private function builder(): Builder
    {
        $hikes = Hike::with('hikeType');

        if ($this->event) {
            $hikes->whereHas('events', function ($query) {
                $query->where('id', $this->getEventId());
            });
        }

        if (! $this->sortField) {
            $this->reset('sortField');
        }

        return $hikes->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        }

        $this->sortField = $field;
        $this->resetPage();
    }

    private function hikes(): LengthAwarePaginator
    {
        $query = $this->builder();

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        if (is_numeric($this->hikeType)) {
            $query->where('hike_type_id', $this->hikeType);
        }

        if (is_numeric($this->distance)) {
            $query->where('distance', $this->distance);
        }

        return $query->paginate($this->paginate);
    }

    public function delete(int $id): void
    {
        $hike = Hike::find($id);

        if (! $hike) {
            $this->js('alert(\'Ez a túra nem létezik!\')');
        }
        $hike->delete();

        add_user_log([
            'title' => 'Túra #'.$id,
            'link' => route('admin.dhtt.index', ['tab' => 'hikes', 'event' => $this->getEventId()]),
            'reference_id' => auth()->id(),
            'section' => 'Túra',
            'type' => 'Törlés',
        ]);
        flash('Túra sikeresen törölve')->success();
        $this->dispatch('close-modal');
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
    protected function hikeTypes(): Collection
    {
        $event = $this->event;

        return $event
            ? HikeType::whereIn('id', $event->hikes->pluck('hike_type_id'))->get()
            : HikeType::all();
    }

    #[Computed]
    protected function distances(): Collection
    {
        $hikes = $this->event->hikes ?? Hike::all();

        if ($this->hikeType) {
            $hikes = $hikes->where('hike_type_id', $this->hikeType);
        }

        return $hikes->pluck('distance')->unique()->sort()->values();
    }

    public function render(): View
    {
        $hikes = $this->hikes();

        return view('livewire.admin.dhtt.hikes.list', compact('hikes'));
    }
}
