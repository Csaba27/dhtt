<?php

use App\Livewire\Admin\Dhtt\Gallery;
use App\Models\Association;
use App\Models\Event;
use App\Models\HikeType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

// Eseményt létrehozzuk, fake dhtt_gallery storage
beforeEach(function () {
    HikeType::create([
        'name' => 'name',
        'description' => 'description',
    ]);

    Association::insert([
        'name' => 'name',
    ]);

    Event::factory()->create();

    Storage::fake('dhtt_gallery');
});

// Mount és kép betöltés teszt
test('can mount the gallery component and load images', function () {
    $event = Event::first();

    Livewire::test(Gallery::class, ['eventId' => $event->id])
        ->assertSet('eventId', $event->id)
        ->assertSet('images', $event->getMedia('gallery')->values());
});

// Feltöltés működik
test('can upload images to the gallery', function () {
    $event = Event::first();
    $image = UploadedFile::fake()->image('photo1.jpg');

    Livewire::test(Gallery::class, ['eventId' => $event->id])
        ->set('newImages', [$image])
        ->call('addImages')
        ->assertSee('Sikeres feltöltés!')
        ->assertSet('newImages', []);

    $this->assertFileExists($event->getFirstMediaPath('gallery'));
    $this->assertCount(1, $event->getMedia('gallery'));
});

// Törlés teszt
test('can delete an image from the gallery', function () {
    $event = Event::first();
    $media = $event->addMedia(UploadedFile::fake()->image('photo2.jpg'))->toMediaCollection('gallery', 'dhtt_gallery');

    Livewire::test(Gallery::class, ['eventId' => $event->id])
        ->call('deleteImage', $media->id)
        ->assertSee('A kép sikeresen törölve!');

    $this->assertDatabaseMissing('media', ['id' => $media->id]);
});

// Mozgatás felülre
test('can move image up in the gallery order', function () {
    $event = Event::first();
    $image1 = $event->addMedia(UploadedFile::fake()->image('photo1.jpg'))->toMediaCollection('gallery', 'dhtt_gallery');
    $image2 = $event->addMedia(UploadedFile::fake()->image('photo2.jpg'))->toMediaCollection('gallery', 'dhtt_gallery');

    Livewire::test(Gallery::class, ['eventId' => $event->id])
        ->call('moveUp', $image2->id)
        ->assertSee('Sorrend sikeresen frissítve!');

    $this->assertEquals($image2->id, $event->getMedia('gallery')->first()->id);
});

// Mozgatás lefele
test('can move image down in the gallery order', function () {
    $event = Event::first();
    $image1 = $event->addMedia(UploadedFile::fake()->image('photo1.jpg'))->toMediaCollection('gallery', 'dhtt_gallery');
    $image2 = $event->addMedia(UploadedFile::fake()->image('photo2.jpg'))->toMediaCollection('gallery', 'dhtt_gallery');

    Livewire::test(Gallery::class, ['eventId' => $event->id])
        ->call('moveDown', $image1->id)
        ->assertSee('Sorrend sikeresen frissítve!');

    $this->assertEquals($image1->id, $event->getMedia('gallery')->last()->id);
});
