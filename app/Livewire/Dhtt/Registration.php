<?php

declare(strict_types=1);

namespace App\Livewire\Dhtt;

use App\Models\Association;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Isolate;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Isolate]
class Registration extends Component
{
    #[Locked]
    public Event $event;

    #[Locked]
    public int $minimumConfirmAge = 14;

    public string $name = '';

    public string $city = '';

    public string $phone = '';

    public int|string $member = 0;

    public string|bool $is_student = false;

    public string|int $age = '';

    public string|bool $tshirt = false;

    public int|string $hike = '';

    public string|bool $terms = false;

    protected array $rules = [
        'name' => 'required',
        'city' => 'required',
        'phone' => 'required',
        'age' => 'required|numeric|min:0|max:110',
        'member' => 'nullable|exists:associations,id',
        'is_student' => 'required|boolean',
        'hike' => 'required|exists:hikes,id',
        'terms' => 'accepted',
        //           'g-recaptcha-response'  => 'required|captcha',
    ];

    public function submit(): void
    {
        $this->validate();

        $registrationEnd = new Carbon($this->event->registration_end);

        if ($registrationEnd < now()) {
            flash('Regisztráció véget ért '.$this->event->registration_end.' ekkor')->error();

            return;
        }

        $status = $this->event->status;

        if ($status != 1 && $status != 2) {
            if ($status == 3) {
                flash('Az online regisztráció lezárva, már csak a helyszínen lehet regisztrálni')->warning();
            } else {
                flash('Pillanatnyilag nincs aktív esemény!')->info();
            }

            return;
        }

        $exists = $this->event->participants()->where([
            'name' => $this->name,
            'age' => $this->age,
            'city' => $this->city,
            'hike_id' => $this->hike,
        ])->exists();

        if ($exists) {
            flash('Már van regisztráció ezzel a névvel és túrával!')->error();

            return;
        }

        $event = $this->event;

        $status = $event->status;
        $entry_fee = $event->entry_fee;
        $discount1 = $event->discount1;
        $discount2 = $event->discount2;

        $is_member = $this->member >= 1;
        $is_student = $this->is_student;
        $age = $this->age;
        $tshirt = $this->tshirt ?? '';

        if ($status == 2 || $status == 3) {     // nincs korai reg. kedvezmeny
            // Diak vagy tag
            if ($is_member || $is_student) {
                $entry_fee -= $discount1;
            }
            // Kedvezmenyes regisztracio
        } elseif ($status == 1) {
            // Diak vagy tag
            if ($is_member || $is_student) {
                $entry_fee -= ($discount1 + $discount2);
            } else {
                $entry_fee -= $discount2;
            }
        }

        if ($age <= 10) {
            $entry_fee = 0;
        }

        if ($tshirt) {
            $entry_fee += 35;
        }

        $entry_fee = max(0, $entry_fee);

        DB::beginTransaction();

        $participant = Participant::create([
            'name' => mb_convert_case($this->name, MB_CASE_TITLE, 'UTF-8'),
            'city' => mb_convert_case($this->city, MB_CASE_TITLE, 'UTF-8'),
            'phone' => $this->phone,
            'is_student' => $is_student,
            'entry_fee' => $entry_fee,
            'age' => $this->age,
            'tshirt' => $this->tshirt ?? '',
            //           'transport' => $request->transport ?? '0',
            'hike_id' => $this->hike,
            'event_id' => $event->id,
        ]);

        if ($this->member > 0) {
            $participant->association()->associate($this->member);
            $participant->save();
        }

        DB::commit();

        flash('Sikeres regisztráció! Szeretettel várunk!')->success();
        $this->resetExcept('event');
        $this->dispatch('refreshList')->to(RegistrationList::class);
    }

    #[Computed]
    public function termsContent(): string
    {
        return Template::firstWhere(['type' => 'terms', 'is_active' => true])->content ?? '';
    }

    public function render(): View
    {
        $associations = Association::all();
        $this->event->load('hikes');
        $hikes = $this->event->hikes->sortBy('hike_type_id');

        return view('livewire.dhtt.registration.form', compact('associations', 'hikes'));
    }
}
