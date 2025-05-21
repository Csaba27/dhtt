<?php

namespace Tests\Feature\App\Livewire\Admin\Dhtt;

use App\Livewire\Admin\Dhtt\Statistics;
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

// Esemény beállítása sikeres
test('can mount correctly and load event data', function () {
    $event = Event::first();

    Livewire::test(Statistics::class, ['eventId' => $event->id])
        ->assertSet('eventId', $event->id)
        ->assertSee($event->name);
});

// Statisztika betöltése sikeres
test('can load statistics correctly', function () {
    $event = Event::first();

    Livewire::test(Statistics::class, ['eventId' => $event->id])
        ->call('loadStatistics')
        ->assertSee('onlineParticipants')
        ->assertSee('totalOnlineParticipants')
        ->assertSee('onSiteParticipants')
        ->assertSee('totalOnSiteParticipants')
        ->assertSee('startParticipants')
        ->assertSee('totalStartParticipants')
        ->assertSee('arrivals')
        ->assertSee('totalArrivals')
        ->assertSee('notArrived')
        ->assertSee('totalNotArrived');
});

// Statisztika dispatch event
test('can refresh statistics correctly', closure: function () {
    $event = Event::first();

    Livewire::test(Statistics::class, ['eventId' => $event->id])
        ->call('refreshStatistics')
        ->assertDispatched('refreshStatisticsComplete');
});

// Esemény kiválasztás hiánya
test('cannot load statistics without an event', function () {
    Livewire::test(Statistics::class, ['eventId' => null])
        ->call('loadStatistics')
        ->assertSee('Esemény nem létezik vagy nincs kiválasztva!');
});

// Helyesek a statisztika adatai
test('can calculate statistics based on participants data', function () {
    Participant::truncate();
    $event = Event::factory()->create([
        'date' => now()->subDays(10),
        'registration_start' => now()->subDays(30),
        'registration_end' => now()->subDays(20),
    ]);
    $hike = Hike::first();
    $event->hikes()->attach($hike);

    Participant::factory()->create([
        'hike_id' => $hike->id,
        'event_id' => $event->id,
        'status' => 'started',
        'start_time' => '12:00:00',
        'number' => 1,
        'created_at' => now()->subDays(10),
    ]);

    Participant::factory()->create([
        'hike_id' => $hike->id,
        'event_id' => $event->id,
        'status' => 'completed',
        'start_time' => '12:00:00',
        'finish_time' => '13:00:00',
        'number' => 2,
        'created_at' => now()->subDays(25),
    ]);

    Livewire::test(Statistics::class, ['eventId' => $event->id])
        ->call('loadStatistics')
        ->assertReturned(function ($returned) {
            expect($returned['totalOnlineParticipants'])->toBe(1)
                ->and($returned['totalOnSiteParticipants'])->toBe(1)
                ->and($returned['totalStartParticipants'])->toBe(2)
                ->and($returned['totalArrivals'])->toBe(1)
                ->and($returned['totalNotArrived'])->toBe(1);

            return true;
        });
});
