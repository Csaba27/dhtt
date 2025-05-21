<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt\Hikes\Steps;

use App\FeaturedStepComponent;
use App\Models\Hike;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class HikeStepThree extends FeaturedStepComponent
{
    use WithFileUploads;

    #[Locked]
    public ?Hike $hike = null;

    #[Locked]
    public string $trackLink = '';

    public string $route = '';

    public $track = null;

    #[Locked]
    public bool $editing = false;

    protected array $rules = [
        'route' => 'required|string',
        'trackLink' => 'nullable|url',
        'track' => 'required|file|mimetypes:application/gpx+xml,text/xml,application/xml|max:26214400',
    ];

    public function stepInfo(): array
    {
        return [
            'label' => 'Útvonal',
        ];
    }

    public function hydrate(): void
    {
        if (is_array($this->track)) {
            $this->resetTrack();
        }
    }

    public function mount(): void
    {
        $this->fill($this->state()->all());
        $editId = $this->state()->forStep($this->allStepNames[0])['editId'];
        $this->editing = (bool) $editId;

        if ($this->editing) {
            $hike = Hike::find($editId);
            $this->hike = $hike;
            $url = $hike->getFirstMediaUrl('tracks');
            $this->trackLink = $url;
        }
    }

    protected function rules(): array
    {
        if ($this->editing && $this->trackLink) {
            $this->rules['track'] = str_replace('required|', 'nullable|', $this->rules['track']);
        }

        return $this->rules;
    }

    public function submit(): void
    {
        $this->validate();

        $data = [];
        $stepNames = $this->allStepNames;
        $stateAll = $this->state()->all();

        // Step 1
        $state = $stateAll[$stepNames[0]];
        $hikeId = $state['editId'] ?? null;

        $data['hike_type_id'] = $state['hikeType'];
        $data['name'] = $state['name'];
        $data['year'] = $state['year'];
        $data['distance'] = $state['distance'];
        $data['time_limit'] = $state['timeLimit'];
        $data['elevation'] = $state['elevation'];

        // Step 2
        $state = $stateAll[$stepNames[1]];
        $data['number_start'] = $state['numberStart'];
        $data['number_end'] = $state['numberEnd'];
        $data['number_start_extra'] = $state['numberStartExtra'];
        $data['number_end_extra'] = $state['numberEndExtra'];

        // Step 3
        $data['route'] = $this->route;
        $data['track_link'] = $this->trackLink;

        DB::beginTransaction();

        $hike = $hikeId > 0 ? $this->hike : new Hike;
        $hike->fill($data);

        if ($this->track instanceof TemporaryUploadedFile && $this->track->exists()) {
            try {
                $path = $this->track->getRealPath();
                $hike->clearMediaCollection('tracks');
                $hike->addMedia($path)->toMediaCollection('tracks', 'dhtttracks');
                $hike->track_link = $hike->getFirstMediaUrl('tracks');
            } catch (Exception $e) {
                DB::rollBack();

                $this->addError('track', 'Feltöltési hiba '.$e->getMessage());

                return;
            }
        }
        $hike->save();

        DB::commit();

        $route = route('admin.dhtt.hikes.'.($this->editing ? 'edit' : 'create'), $this->editing ? $hike : null);
        add_user_log([
            'title' => 'Túra #'.$hike->id,
            'link' => $route,
            'reference_id' => auth()->id(),
            'section' => 'Túra',
            'type' => ! is_null($hikeId) ? 'Szerkesztés' : 'Létrehozás',
        ]);

        flash('Túra sikeresen '.($this->editing ? 'szerkesztve' : 'létrehozva'))->success();
        $this->redirectRoute('admin.dhtt.index', ['tab' => 'hikes']);
    }

    public function resetTrack(): void
    {
        if ($this->track instanceof TemporaryUploadedFile && $this->track->exists()) {
            $this->track->delete();
        }
        $this->reset('track');
    }

    public function render(): View
    {
        return view('livewire.admin.dhtt.hikes.steps.hike-step-three', [
            'isEdit' => $this->editing,
        ]);
    }
}
