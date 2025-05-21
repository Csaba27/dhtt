<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt\Hikes\Steps;

use App\FeaturedStepComponent;
use Illuminate\View\View;
use Livewire\Attributes\Locked;

class HikeStepOne extends FeaturedStepComponent
{
    public $hikeType;

    public string $name;

    public string $year;

    public string $distance = '';

    public string $timeLimit = '';

    public string $elevation = '';

    #[Locked]
    public ?int $editId = null;

    #[Locked]
    public bool $editing = false;

    protected function rules(): array
    {
        return [
            'hikeType' => 'required|exists:hike_types,id',
            'name' => 'required|string|max:255',
            'year' => 'required|digits:4|integer',
            'distance' => 'required|integer|min:0|max:32767',
            'timeLimit' => 'required|numeric|min:0|max:100000',
            'elevation' => 'required',
        ];
    }

    public function stepInfo(): array
    {
        return [
            'label' => 'Főbb adatok',
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
        $this->nextStep();
    }

    public function render(): View
    {
        return view('livewire.admin.dhtt.hikes.steps.hike-step-one', [
            'isEdit' => $this->editing,
        ]);
    }
}
