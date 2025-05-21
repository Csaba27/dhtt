<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt\Events\Steps;

use App\FeaturedStepComponent;
use Illuminate\View\View;
use Livewire\Attributes\Locked;

class DescriptionComponent extends FeaturedStepComponent
{
    public ?string $description = null;

    #[Locked]
    public ?int $editId = null;

    #[Locked]
    public bool $editing = false;

    public function stepInfo(): array
    {
        return [
            'label' => 'Leírás',
        ];
    }

    protected function rules(): array
    {
        return ['description' => 'required|string'];
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
        return view('livewire.admin.dhtt.events.steps.description', [
            'isEdit' => $this->editing,
        ]);
    }
}
