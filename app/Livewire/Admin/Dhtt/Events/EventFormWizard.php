<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt\Events;

use App\Livewire\Admin\Dhtt\Events\Steps\BasicInfoComponent;
use App\Livewire\Admin\Dhtt\Events\Steps\DescriptionComponent;
use App\Livewire\Admin\Dhtt\Events\Steps\HikesComponent;
use App\Livewire\Admin\Dhtt\Events\Steps\InvitationComponent;
use App\Models\Event;
use App\Models\Template;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Spatie\LivewireWizard\Components\WizardComponent;

class EventFormWizard extends WizardComponent
{
    #[Locked]
    public ?Event $event = null;

    #[Locked]
    public bool $editing = false;

    public function mount(): void
    {
        $this->editing = ! is_null($this->event);
    }

    public function initialState(): ?array
    {
        $event = $this->event;
        $steps = $this->stepNames();

        if (! is_null($event) && $this->editing) {
            $editId = $event->id;
            $hikes = $event->hikes()->pluck('id')->toArray();
            $data = $event->only([
                'short_name', 'name', 'year', 'date', 'location', 'entry_fee',
                'discount1', 'discount2', 'registration_start', 'registration_end',
                'registration_discount_until', 'organizer_name', 'organizer_email',
                'organizer_phone', 'rules', 'active', 'show',
            ]);

            $data = array_merge($data, compact('editId'));

            return [
                $steps[0] => $data,
                $steps[1] => ['editId' => $editId, 'invitation' => $event->invitation],
                $steps[2] => ['editId' => $editId, 'description' => $event->description],
                $steps[3] => ['editId' => $editId, 'hikes' => $hikes],
            ];
        }

        // Új eseménynél: aktív sablonból betöltjük
        $templates = Template::whereIn('type', ['rules', 'invitation', 'description'])
            ->where('is_active', true)
            ->pluck('content', 'type');

        return [
            $steps[0] => ['rules' => $templates['rules'] ?? ''],
            $steps[1] => ['invitation' => $templates['invitation'] ?? ''],
            $steps[2] => ['description' => $templates['description'] ?? ''],
        ];
    }

    public function steps(): array
    {
        return [
            BasicInfoComponent::class,
            InvitationComponent::class,
            DescriptionComponent::class,
            HikesComponent::class,
        ];
    }

    public function render(): View
    {
        return parent::render()->title('Esemény '.($this->editing ? 'szerkesztése' : 'létrehozása'));
    }
}
