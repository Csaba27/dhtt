<?php

use App\Livewire\Admin\Dhtt\Events\EventsList;
use App\Livewire\Admin\Dhtt\Hikes\HikesList;
use App\Livewire\Admin\Dhtt\Panel;
use App\Models\Event;
use Livewire\Livewire;

// Alapértelmezett fül betöltése
test('can load default tab', function () {
    Livewire::test(Panel::class)
        ->assertSet('activeTab', 'events')
        ->assertSeeLivewire(EventsList::class);
});

// Fülváltás működik
test('can switch tabs correctly', function () {
    Livewire::test(Panel::class)
        ->set('activeTab', 'hikes')
        ->assertSet('activeTab', 'hikes')
        ->assertSeeLivewire(HikesList::class);
});

// Hibás fül visszaáll alapértelmezettre
test('can allow invalid tabs', function () {
    Livewire::test(Panel::class)
        ->set('activeTab', 'invalid')
        ->assertSet('activeTab', 'events');
});

// Ha nincs kiválasztott esemény, az aktív eseményt állítja be
test('can set eventId to active event if none selected', function () {
    $event = Event::factory()->create(['active' => 1]);

    Livewire::test(Panel::class)
        ->assertSet('eventId', $event->id);
});

// Ha nincs aktív esemény, akkor a legutolsó inaktív eseményt állítja be
test('can set eventId to latest inactive if no active event exists', function () {
    Event::factory()->count(3)->create(['active' => 0]);

    $latest = Event::orderByDesc('id')->first();

    Livewire::test(Panel::class)
        ->assertSet('eventId', $latest->id);
});

// Hibás eventId esetén reseteli
test('can reset eventId if invalid value is set', function () {
    Livewire::test(Panel::class)
        ->set('eventId', 'invalid')
        ->assertSet('eventId', null);
});

// Érvényes vagy 'all' érték megtartása
test('can keep eventId if valid or "all"', function () {
    $event = Event::factory()->create();

    Livewire::test(Panel::class)
        ->set('eventId', $event->id)
        ->assertSet('eventId', $event->id)
        ->set('eventId', 'all')
        ->assertSet('eventId', 'all');
});

// Navigáció másik tab-ra session és URL frissítéssel
test('can navigating to another tab updates URL and session', function () {
    $event = Event::factory()->create();

    Livewire::withQueryParams(['tab' => 'participants', 'event' => $event->id])
        ->test(Panel::class)
        ->assertSet('activeTab', 'participants')
        ->assertSet('eventId', $event->id);
});
