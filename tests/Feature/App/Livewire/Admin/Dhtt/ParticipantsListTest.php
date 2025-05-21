<?php

use App\Livewire\Admin\Dhtt\Participants\ParticipantsList;
use App\Models\Association;
use App\Models\Event;
use App\Models\Hike;
use App\Models\HikeType;
use App\Models\Participant;
use Livewire\Livewire;
use Maatwebsite\Excel\Facades\Excel;

// Teszt előkészítése
beforeEach(function () {
    HikeType::create([
        'name' => 'hike',
        'description' => 'description',
    ]);

    Association::insert([
        'name' => 'example',
    ]);

    $event = Event::factory()->create();
    $hike = Hike::factory()->create();
    $event->hikes()->attach($hike);

    Participant::factory()->count(5)->create(['hike_id' => $hike->id, 'event_id' => $event->id]);
});

// Jelentkező látható
test('can list participants for selected event', function () {
    $event = Event::first();
    $participant = $event->participants->first();

    Livewire::test(ParticipantsList::class, ['eventId' => $event->id])
        ->assertSee($participant->name);
});

// Excel adatok letöltése
test('can export participants as Excel file', function () {
    Excel::fake();
    $event = Event::first();

    Livewire::test(ParticipantsList::class, ['eventId' => $event->id])
        ->call('export')
        ->assertFileDownloaded();
});

// Jelentkező törlése
test('can delete a participant', function () {
    $event = Event::first();
    $participant = $event->participants->first();

    Livewire::test(ParticipantsList::class, ['eventId' => $event->id])
        ->call('delete', $participant->id)
        ->assertDispatched('close-modal');

    expect(Participant::find($participant->id))->toBeNull();
});

// Nem választottunk ki eseményt
test('cannot export without event', function () {
    Livewire::test(ParticipantsList::class)
        ->call('export')
        ->assertNoFileDownloaded();
});
