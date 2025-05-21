<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt\Events\Steps;

use App\FeaturedStepComponent;
use Illuminate\View\View;
use Livewire\Attributes\Locked;

class BasicInfoComponent extends FeaturedStepComponent
{
    public ?string $short_name = null;

    public ?string $name = null;

    public ?int $year = null;

    public ?string $date = null;

    public ?string $location = null;

    public ?int $entry_fee = null;

    public ?int $discount1 = null;

    public ?int $discount2 = null;

    public ?string $registration_start = null;

    public ?string $registration_end = null;

    public ?string $registration_discount_until = null;

    public ?string $organizer_name = null;

    public ?string $organizer_email = null;

    public ?string $organizer_phone = null;

    public ?string $rules = null;

    public bool $active = false;

    public bool $show = false;

    #[Locked]
    public ?int $editId = null;

    #[Locked]
    public bool $editing = false;

    protected function rules(): array
    {
        return [
            'short_name' => 'required|string|max:50',
            'name' => 'required|string',
            'year' => 'required|numeric|min:2000|max:2100',
            'date' => 'required|date|after:registration_discount_until',
            'location' => 'required|string|max:100',
            'entry_fee' => 'required|numeric|min:0',
            'discount1' => 'required|integer',
            'discount2' => 'required|integer',
            'registration_start' => 'required|date',
            //            'registration_end' => 'required|date|after:registration_start',
            'registration_discount_until' => 'required|date|after:registration_start',
            'organizer_name' => 'required|string|max:100',
            'organizer_email' => 'required|email',
            'organizer_phone' => 'required|string|max:20',
            'rules' => 'required|string',
            'active' => 'nullable|boolean',
            'show' => 'nullable|boolean',
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
        $this->registration_end = $this->registration_discount_until;
        $this->nextStep();
    }

    public function render(): View
    {
        return view('livewire.admin.dhtt.events.steps.basic-info', [
            'isEdit' => $this->editing,
        ]);
    }
}
