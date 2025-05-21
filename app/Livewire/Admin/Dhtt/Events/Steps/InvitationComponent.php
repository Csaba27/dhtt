<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt\Events\Steps;

use App\FeaturedStepComponent;
use Illuminate\View\View;
use Livewire\Attributes\Locked;

class InvitationComponent extends FeaturedStepComponent
{
    public ?string $invitation = null;

    #[Locked]
    public ?int $editId = null;

    #[Locked]
    public bool $editing = false;

    public function stepInfo(): array
    {
        return [
            'label' => 'Meghívó',
        ];
    }

    protected function rules(): array
    {
        return ['invitation' => 'required|string'];
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
        return view('livewire.admin.dhtt.events.steps.invitation', [
            'isEdit' => $this->editing,
        ]);
    }
}
