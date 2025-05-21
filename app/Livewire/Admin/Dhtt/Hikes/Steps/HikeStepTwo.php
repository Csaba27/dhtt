<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt\Hikes\Steps;

use App\FeaturedStepComponent;
use Illuminate\View\View;
use Livewire\Attributes\Locked;

class HikeStepTwo extends FeaturedStepComponent
{
    public int|string $numberStart = 0;

    public int|string $numberEnd = 0;

    public ?string $numberStartExtra;

    public ?string $numberEndExtra;

    #[Locked]
    public bool $editing = false;

    public function stepInfo(): array
    {
        return [
            'label' => 'Számok',
        ];
    }

    public function mount(): void
    {
        $this->fill($this->state()->all());
        $this->editing = (bool) $this->state()->forStep($this->allStepNames[0])['editId'];
    }

    protected function rules(): array
    {
        $rules = [
            'numberStart' => 'required_with:numberEnd|integer|min:0',
            'numberEnd' => 'required_with:numberStart|integer|gt:numberStart',
            'numberStartExtra' => 'nullable|integer|min:0',
            'numberEndExtra' => 'nullable|integer|min:0',
        ];

        if ($this->numberEndExtra && $this->numberEndExtra > 0) {
            $rules['numberStartExtra'] .= '|required_with:numberEndExtra|gt:numberEnd';
        }

        if ($this->numberStartExtra && $this->numberStartExtra > 0) {
            $rules['numberEndExtra'] .= '|required_with:numberStartExtra|gt:numberStartExtra';
        }

        return $rules;
    }

    public function submit(): void
    {
        $this->validate();
        $this->nextStep();
    }

    public function render(): View
    {
        return view('livewire.admin.dhtt.hikes.steps.hike-step-two', [
            'isEdit' => $this->editing,
        ]);
    }
}
