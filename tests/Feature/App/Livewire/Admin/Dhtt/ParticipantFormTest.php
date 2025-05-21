<?php

use App\Enums\ParticipantStatus;
use App\Livewire\Admin\Dhtt\Participants\ParticipantsForm;
use App\Models\Association;
use App\Models\Event;
use App\Models\Hike;
use App\Models\HikeType;
use App\Models\Participant;
use Livewire\Livewire;

// Mielőtt futna a teszt létrehozunk egy túra típust egy támogatót, egy eseményt és egy túrát amit hozzárendelek az eseményhez majd végezetűl 5 résztvevőt is létrehozok
beforeEach(function () {
    HikeType::create([
        'name' => 'name',
        'description' => 'description',
    ]);

    Association::insert([
        'name' => 'name',
    ]);

    $event = Event::factory()->create();
    $hike = Hike::factory()->create();
    $event->hikes()->attach($hike);

    Participant::factory()->count(5)->create(['hike_id' => $hike->id, 'event_id' => $event->id]);
});

// Új jelentkező sikeres létrehozása
test('can create a new participant successfully', function () {
    $event = Event::first();
    $hike = $event->hikes->first();

    Livewire::test(ParticipantsForm::class, ['event' => $event])
        ->set('name', 'Test')
        ->set('city', 'City')
        ->set('phone', '123456789')
        ->set('age', 25)
        ->set('entryFee', 1000)
        ->set('isStudent', 0)
        ->set('hikeId', $hike->id)
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('participants', [
        'name' => 'Test',
        'event_id' => $event->id,
        'hike_id' => $hike->id,
    ]);
});

// Rajtszám generálása elindulási idő alapján
test('can generate a number when requested and start time is set', function () {
    $event = Event::first();
    $hike = $event->hikes->first();
    $hike->number_start = 1;
    $hike->number_end = 100;
    $hike->save();

    Livewire::test(ParticipantsForm::class, ['event' => $event])
        ->set('name', 'Test')
        ->set('city', 'City')
        ->set('phone', '987654321')
        ->set('age', 30)
        ->set('entryFee', 1500)
        ->set('status', ParticipantStatus::Started->value)
        ->set('isStudent', 1)
        ->set('hikeId', $hike->id)
        ->set('generateNumber', true)
        ->set('startTime', now()->format('H:i:s'))
        ->call('submit')
        ->assertHasNoErrors();
});
