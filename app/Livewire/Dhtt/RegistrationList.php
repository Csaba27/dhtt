<?php

declare(strict_types=1);

namespace App\Livewire\Dhtt;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Isolate;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Isolate]
class RegistrationList extends Component
{
    use WithPagination;

    #[Locked]
    public Event $event;

    #[Locked]
    public string $sortField = 'id';

    #[Locked]
    public bool $sortAsc = false;

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        }

        $this->sortField = $field;
        $this->resetPage();
    }

    private function participants(): LengthAwarePaginator
    {
        $event = $this->event;
        $sortField = $this->sortField;
        if (! $sortField) {
            $this->reset('sortField');
        }

        if ($sortField == 'hike') {
            $sortField = 'hike_id';
        }

        return $event->participants()->with('hike')->orderBy($sortField, $this->sortAsc ? 'asc' : 'desc')->paginate();
    }

    #[On('refreshList')]
    public function refreshList(): void
    {
        $this->reset('sortField', 'sortAsc');
        $this->resetPage();
    }

    public function render(): View
    {
        $participants = $this->participants();

        return view('livewire.dhtt.registration.list', [
            'participants' => $participants,
        ]);
    }
}
