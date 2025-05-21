<?php

namespace Tests\Feature\App\Livewire\Admin\Dhtt;

use App\Livewire\Admin\Dhtt\Supporters;
use App\Models\Supporter;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Livewire;
use Livewire\WithFileUploads;

uses(WithFileUploads::class);

// Megtudjuk nyitni a létrehozás modalt?
test('can open the create modal', function () {
    Livewire::test(Supporters::class)
        ->call('create')
        ->assertSet('modalOpen', true);
});

// View render teszt
test('can render supporter details with image', function () {
    $supporter = Supporter::factory()->create([
        'image_url' => 'https://example.com/image.jpg',
        'link' => 'https://example.com',
    ]);

    Livewire::test(Supporters::class)
        ->assertSee($supporter->name)
        ->assertSee($supporter->image_url)
        ->assertSee($supporter->link);
});

// Szerkesztés modal megnyitható és láthatóak az adatok benne
test('can open edit modal with supporter data', function () {
    $supporter = Supporter::factory()->create();

    Livewire::test(Supporters::class)
        ->call('edit', $supporter->id)
        ->assertSet('modalOpen', true)
        ->assertSet('supporterId', $supporter->id)
        ->assertSet('name', $supporter->name);
});

// Tudunk törölni
test('can delete a supporter', function () {
    $supporter = Supporter::factory()->create();

    Livewire::test(Supporters::class)
        ->call('delete', $supporter->id)
        ->assertDontSee($supporter->name)
        ->assertSee('Támogató sikeresen törölve!');
});

// Cancel methódus működik
test('can cancel and reset form fields', function () {
    Livewire::test(Supporters::class)
        ->set('name', 'Some Supporter')
        ->set('link', 'https://example.com')
        ->call('cancel')
        ->assertSet('name', '')
        ->assertSet('link', '')
        ->assertSet('modalOpen', false);
});

// Létretudunk hozni új rekordot
test('can create a new supporter with valid data', function () {
    Livewire::test(Supporters::class)
        ->set('name', 'New Supporter')
        ->set('link', 'https://example.com')
        ->set('image_url', 'https://example.com/image.png')
        ->call('save')
        ->assertHasNoErrors();

    expect(Supporter::count())->toBe(1)
        ->and(Supporter::first()->name)->toBe('New Supporter');
});

// Szerkesztés megfelelően működik
test('can edit an existing supporter', function () {
    $supporter = Supporter::factory()->create([
        'name' => 'Old Supporter',
        'link' => 'https://old.com',
    ]);

    Livewire::test(Supporters::class)
        ->call('edit', $supporter->id)
        ->set('name', 'Updated Supporter')
        ->set('link', 'https://updated.com')
        ->call('save');

    $supporter->refresh();
    expect($supporter->name)->toBe('Updated Supporter')
        ->and($supporter->link)->toBe('https://updated.com');
});

// Validáció megfelelően működik
test('cannot create a supporter with invalid data', function () {
    Livewire::test(Supporters::class)
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name']);
});

// Kép feltöltés tesztelése
test('can handle image upload when creating a supporter', function () {
    Storage::fake('public');

    $image = TemporaryUploadedFile::fake()->image('supporter.jpg');

    Livewire::test(Supporters::class)
        ->set('name', 'Supporter with Image')
        ->set('link', 'https://example.com')
        ->set('newImage', $image)
        ->call('save');

    Storage::disk('public')->assertExists('supporters/'.$image->hashName());
});

// Szerkesztésnél kép csere működike
test('can update the image when editing a supporter', function () {
    $supporter = Supporter::factory()->create();

    Storage::fake('public');

    $newImage = TemporaryUploadedFile::fake()->image('new_image.jpg');

    Livewire::test(Supporters::class)
        ->call('edit', $supporter->id)
        ->set('newImage', $newImage)
        ->call('save');

    Storage::disk('public')->assertExists('supporters/'.$newImage->hashName());
});
