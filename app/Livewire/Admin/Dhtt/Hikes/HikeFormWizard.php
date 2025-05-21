<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt\Hikes;

use App\Livewire\Admin\Dhtt\Hikes\Steps\HikeStepOne;
use App\Livewire\Admin\Dhtt\Hikes\Steps\HikeStepThree;
use App\Livewire\Admin\Dhtt\Hikes\Steps\HikeStepTwo;
use App\Models\Hike;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Spatie\LivewireWizard\Components\WizardComponent;

class HikeFormWizard extends WizardComponent
{
    #[Locked]
    public ?Hike $hike = null;

    #[Locked]
    public bool $editing = false;

    public function mount(): void
    {
        $this->editing = ! is_null($this->hike);
    }

    public function initialState(): ?array
    {
        $stepsNames = $this->stepNames();

        if ($this->hike) {
            $hike = $this->hike;

            return [
                $stepsNames[0] => [
                    'editId' => $hike->getKey(),
                    'hikeType' => $hike->hike_type_id,
                    'name' => $hike->name,
                    'year' => $hike->year,
                    'distance' => $hike->distance,
                    'timeLimit' => $hike->time_limit,
                    'elevation' => $hike->elevation,
                ],

                $stepsNames[1] => [
                    'numberStart' => $hike->number_start,
                    'numberEnd' => $hike->number_end,
                    'numberStartExtra' => $hike->number_start_extra,
                    'numberEndExtra' => $hike->number_end_extra,
                ],

                $stepsNames[2] => [
                    'route' => $hike->route,
                    'trackLink' => (string) $hike->track_link,
                ],
            ];
        }

        return null;
    }

    public function steps(): array
    {
        return [
            HikeStepOne::class,
            HikeStepTwo::class,
            HikeStepThree::class,
        ];
    }

    public function render(): View
    {
        $title = 'Túra '.($this->editing ? 'szerkesztése' : 'létrehozása');

        return parent::render()->title($title);
    }
}
