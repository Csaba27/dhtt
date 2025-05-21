<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dhtt\Participants;

use App\Enums\ParticipantStatus;
use App\Models\Association;
use App\Models\Event;
use App\Models\Hike;
use App\Models\Participant;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ParticipantsForm extends Component
{
    #[Locked]
    public ?Participant $participant = null;

    #[Locked]
    public ?Event $event = null;

    #[Locked]
    public int $number = 0;

    public bool $modalOpen = false;

    public bool $generateNumber = false;

    public string $name;

    public string $city;

    public string $phone;

    public int|string $age;

    public int|float|string $entryFee = 0.00;

    public string $status = '';

    public $member = null;

    public int|string $isStudent = 0;

    public string $startTime = '00:00:00';

    public string $finishTime = '00:00:00';

    public int|string $hikeId = '';

    public ?string $notes = '';

    #[Locked]
    public bool $isEdit = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'city' => 'required|string',
            'phone' => 'required|string',
            'age' => 'required|numeric|min:1|max:100',
            'entryFee' => 'required|numeric|max:10000.00',
            'member' => 'nullable|exists:'.Association::class.',id',
            'status' => ['required', Rule::enum(ParticipantStatus::class)],
            'isStudent' => 'required|boolean',
            'hikeId' => 'required|exists:'.Hike::class.',id',
            'startTime' => 'required|date_format:H:i:s',
            'finishTime' => 'required|date_format:H:i:s',
            'notes' => 'nullable|string',
        ];
    }

    public function boot(): void
    {
        // Extra validálások
        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {
                $status = ParticipantStatus::from($this->status);

                $hasStart = ! empty($this->startTime) && $this->startTime !== '00:00:00';
                $hasFinish = ! empty($this->finishTime) && $this->finishTime !== '00:00:00';

                // Van elindulási idő, de státusz nem megfelelő
                if ($hasStart && ! in_array($status, [ParticipantStatus::Started, ParticipantStatus::Completed, ParticipantStatus::Abandoned])) {
                    $validator->errors()->add('status', 'Ha van elindulási idő, a státusznak elindult, befejezte vagy feladottnak kell lennie.');
                }

                // Van érkezési idő, de státusz nem megfelelő
                if ($hasFinish && ! in_array($status, [ParticipantStatus::Completed, ParticipantStatus::Abandoned])) {
                    $validator->errors()->add('status', 'Ha van érkezési idő, a státusznak befejezettnek vagy feladottnak kell lennie.');
                }

                // Van bármilyen idő, de nincs szám vagy nincs engedélyezve a szám generálás
                if (($hasStart || $hasFinish) && ! $this->number && ! $this->generateNumber) {
                    $validator->errors()->add('startTime', 'Idő megadásához szükséges rajtszám vagy generálási engedély.');
                }

                // Be van jelölve a rajtszám generálás, de nincs idő megadva
                if ($this->generateNumber && ! $hasStart && ! $hasFinish) {
                    $validator->errors()->add('generateNumber', 'Rajtszám generálásához szükséges az elindulási idő vagy befejezési idő megadása.');
                }

                // Ha nincsenek idők beírva és a státusz nem hiányzó
                if (! $hasStart && ! $hasFinish && $status !== ParticipantStatus::Absent) {
                    $validator->errors()->add('status', 'Ha nincs idő megadva, a státusznak hiányzónak kell lennie.');
                }

                // Ha státusz befejezte akkor kell legyen idő beirva
                if ($status === ParticipantStatus::Completed && (! $hasFinish)) {
                    $validator->errors()->add('status', 'Ha a státusz befejezte akkor kell legyen befejezési idő.');
                }

                // Érkezési idő nem lehet korábbi mint az elindulási idő
                if ($hasStart && $hasFinish
                    && $this->finishTime < $this->startTime
                ) {
                    $validator->errors()->add('startTime', 'Elindulási idő nem lehet későbbi az érkezési időnél!');
                }
            });
        });
    }

    public function mount(): void
    {
        if (! is_null($this->participant)) {
            $participant = &$this->participant;
            $this->isEdit = true;
            $this->member = $participant->association_id ?? null;
            $this->event = $participant->event;

            $attributes = $participant->getAttributes();
            $data = array_combine(array_map(fn ($key) => Str::camel($key), array_keys($attributes)), $attributes);
            $this->fill($data);
        }

        if (is_null($this->event)) {
            abort(404, 'Nem választottál ki eseményt vagy az adatbázisban nem található!');
        }

        // Részvételi díj beírása automatikusan új jelentkező létrehozásakor
        if (! $this->isEdit) {
            $this->entryFee = $this->event->entry_fee;
        }

        // Alapértelmezett státusz
        if (! $this->status) {
            $this->status = ParticipantStatus::default()->value;
        }

        // Rajtolási információk megjelenítése
        $this->modalOpen = $this->modalOpen || session()->has('participant_start_info');
    }

    public function submit(): void
    {
        $this->validate();

        $participant = $this->participant ?? new Participant;
        $hike = Hike::find($this->hikeId);
        $hikeChanged = $this->hikeId != $participant->hike_id;
        $generateNumber = ($this->isEdit && $hikeChanged) || $this->generateNumber;

        $data = [
            'event_id' => $this->event->getKey(),
            'association_id' => $this->member,
            'name' => $this->name,
            'city' => $this->city,
            'phone' => $this->phone,
            'age' => $this->age,
            'entry_fee' => $this->entryFee,
            'status' => $this->status,
            'is_student' => $this->isStudent,
            'hike_id' => $this->hikeId,
            'start_time' => $this->startTime,
            'finish_time' => $this->finishTime,
            //            'notes' => $this->notes,
        ];

        DB::beginTransaction();

        // Túra váltásnál és hozzáadásnál új sorszmám generálása
        if ($generateNumber) {
            $number_start = $hike->number_start;
            $number_end = $hike->number_end;
            $number_start_extra = $hike->number_start_extra;
            $number_end_extra = $hike->number_end_extra;
            $current_number = $hike->current_number;

            if ($current_number == 0) {
                $current_number = max($number_start, 1);
            } elseif ($current_number + 1 <= $number_end) {
                $current_number++;
            } elseif ($number_start_extra != 0 && $current_number <= $number_start_extra) {
                $current_number = $number_start_extra;
            } elseif ($number_start_extra != 0 && $current_number + 1 <= $number_end_extra) {
                $current_number++;
            }

            if ($hike->current_number != $current_number) {

                // Előző túra sorszám aktuális visszaállítás túra típus váltásánál
                $lastHike = $participant->hike;
                if ($lastHike
                    && $lastHike->getKey() != $this->hikeId
                    && $lastHike->current_number <= $participant->number) {
                    $lastHike->current_number = max(0, $participant->number - 1);
                    $lastHike->save();
                }

                $this->number = $participant->number = $hike->current_number = $current_number;
                $hike->save();

                // Rajtolási információk megejelenítése
                $this->modalOpen = true;

                // Rajtszám generálás bejelölés visszaállítása
                $this->reset('generateNumber');

                session()->flash('participant_start_info');
                flash('Szám generálás sikeres volt.')->success();
            } else {
                $this->addError('generateNumber', 'Az új sorszám generálása nem sikerült, talán elfogytak a sorszámok.');
            }
        }

        // Új túra hozzárendelés
        if (! $this->isEdit || $hikeChanged) {
            $participant->hike()->associate($hike);
        }

        $data['number'] = $this->number;

        // Szint idő beírása
        $start = Carbon::parse($this->startTime);
        $finish = Carbon::parse($this->finishTime);

        if ($this->startTime && $this->startTime != '00:00:00' && $finish->gt($start)) {
            $data['completion_time'] = $finish->diff($start)->format('%H:%I:%S');
        }

        $participant->fill($data);
        $participant->save();

        $parameters = $this->isEdit ? $participant : [];

        add_user_log([
            'title' => 'Jelentkező #'.$this->participant?->getKey(),
            'link' => route('admin.dhtt.participants.'.($this->isEdit ? 'edit' : 'create'), $parameters),
            'reference_id' => auth()->id(),
            'section' => 'Jelentkező',
            'type' => ($this->isEdit ? 'Szerkesztés' : 'Létrehozás'),
        ]);

        DB::commit();

        flash('Jelentkező sikeresen '.($this->isEdit ? 'szerkesztve' : 'létrehozva'))->success();

        if (! $this->isEdit) {
            $this->redirectRoute('admin.dhtt.participants.edit', $participant);
        }
    }

    public function cancel(): void
    {
        $this->resetValidation();
        $this->redirectToIndex();
    }

    public function redirectToIndex(): void
    {
        $this->redirectRoute('admin.dhtt.index', ['tab' => 'participants']);
    }

    public function insertStartTime(): void
    {
        // Ha hiányzó volt a státusza akkor automatikusan berakjuk, hogy elkezdte
        if ($this->status == ParticipantStatus::Absent->value) {
            $this->status = ParticipantStatus::Started->value;
        }

        $this->startTime = now()->format('H:i:s');

        // Ha van kitöltve érkezési idő és az kisebb mint az elkezdési idő visszaállítjuk
        if ($this->finishTime && $this->finishTime != '00:00:00' && $this->startTime > $this->finishTime) {
            $this->reset('finishTime');
        }
    }

    public function insertFinishTime(): void
    {
        // Ha előszőr az érkezési idő kerülne kitöltésre figyelmeztetést adunk
        if (! $this->startTime || $this->startTime === '00:00:00') {
            $this->addError('finishTime', 'Előszőr az érkezési idő kell kitöltésre kerüljön!');

            return;
        }

        // Ha elkezdett volt a státusza akkor automatikusan berakjuk, hogy befejezte
        if ($this->status == ParticipantStatus::Started->value) {
            $this->status = ParticipantStatus::Completed->value;
        }

        $this->finishTime = now()->format('H:i:s');
    }

    public function render(): View
    {
        $title = 'Jelentkező '.($this->isEdit ? 'szerkesztés' : 'létrehozása');

        return view('livewire.admin.dhtt.participants.form')->title($title);
    }
}
