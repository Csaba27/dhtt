<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt\Events\Steps;

use App\FeaturedStepComponent;
use App\Models\Event;
use App\Models\Hike;
use Illuminate\View\View;
use Livewire\Attributes\Locked;

class HikesComponent extends FeaturedStepComponent
{
    public ?string $search = null;

    public array $hikes = [];

    #[Locked]
    public ?int $editId = null;

    #[Locked]
    public bool $editing = false;

    public function stepInfo(): array
    {
        return [
            'label' => 'Túrák',
        ];
    }

    protected function rules(): array
    {
        return [
            'hikes' => 'array',
            'hikes.*' => 'exists:'.Hike::class.',id',
        ];
    }

    public function mount(): void
    {
        $this->editing = (bool) $this->editId;
        $this->fill($this->state()->all());
    }

    public function submit(): void
    {
        $this->validate();
        $data = [];
        $stepNames = $this->allStepNames;
        $stateAll = $this->state()->all();

        $keys = array_flip((new Event)->getFillable());

        foreach ($stepNames as $stepName) {
            $state = $stateAll[$stepName];

            $state = array_intersect_key($state, $keys);
            $data = array_merge($data, $state);
        }

        $event = $this->editing
            ? Event::findOrFail($this->editId)
            : new Event;

        $event->fill($data)->save();
        $event->hikes()->sync($this->hikes);
        $event->save();

        $route = route('admin.dhtt.events.'.($this->editing ? 'edit' : 'create'), $this->editing ? $event : null);
        add_user_log([
            'title' => 'Esemény #'.$event->id,
            'link' => $route,
            'reference_id' => auth()->id(),
            'section' => 'Esemény',
            'type' => $this->editing ? 'Szerkesztés' : 'Létrehozás',
        ]);

        flash('Esemény sikeresen '.($this->editing ? 'szerkesztve' : 'létrehozva'))->success();

        $this->redirectRoute('admin.dhtt.index', ['tab' => 'events']);
    }

    public function render(): View
    {
        $allHikes = Hike::where('name', 'like', '%'.$this->search.'%')->get();

        return view('livewire.admin.dhtt..events.steps.hikes', [
            'isEdit' => $this->editing,
            'allHikes' => $allHikes,
        ]);
    }
}
