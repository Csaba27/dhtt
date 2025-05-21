<?php

use App\Livewire\Admin\Dhtt\Templates;
use App\Models\Template;
use Livewire\Livewire;

// Tundjuk őket listázni sorrendben
test('can list templates in alphabetical order', function () {
    Template::factory()->create(['title' => 'Z']);
    Template::factory()->create(['title' => 'A']);

    Livewire::test(Templates::class)
        ->assertCount('templates', 2)
        ->assertSeeInOrder(['A', 'Z']);
});

// Új sablon létrehozásához szükséges űrlapot meg tudjuk nyitni és a mezőket vissza tudjuk állítani
test('can open the create form and reset fields', function () {
    Livewire::test(Templates::class)
        ->call('create')
        ->assertSet('formOpen', true)
        ->assertSet('title', '')
        ->assertSet('type', '')
        ->assertSet('content', '')
        ->assertSet('isActive', true);
});

// Űrlap törlésével vissza tudjuk állítani a mezők alapértelmezett értékeit
test('can cancel and reset the form fields', function () {
    Livewire::test(Templates::class)
        ->set('title', 'Something')
        ->set('type', 'rules')
        ->set('content', 'Content here')
        ->call('cancel')
        ->assertSet('title', '')
        ->assertSet('type', '')
        ->assertSet('content', '')
        ->assertSet('formOpen', false);
});

// A kötelező mezőket validálni tudjuk mentéskor
test('can validate required fields when saving', function () {
    Livewire::test(Templates::class)
        ->call('save')
        ->assertHasErrors(['title', 'type', 'content']);
});

// Új sablont tudunk létrehozni érvényes adatokat megadva
test('can create a new template with valid data', function () {
    Livewire::test(Templates::class)
        ->set('title', 'New Template')
        ->set('type', 'rules')
        ->set('content', 'This is a rules template.')
        ->set('isActive', true)
        ->call('save')
        ->assertSet('formOpen', false);

    expect(Template::count())->toBe(1)
        ->and(Template::first()->title)->toBe('New Template');
});

// Meglévő sablont tudunk szerkeszteni
test('can edit an existing template', function () {
    $template = Template::factory()->create([
        'title' => 'Old Template',
        'type' => 'rules',
        'content' => 'Old content',
        'is_active' => false,
    ]);

    Livewire::test(Templates::class)
        ->call('edit', $template->id)
        ->set('title', 'Updated Template')
        ->set('content', 'New content')
        ->set('isActive', true)
        ->call('save')
        ->assertSet('editing', $template->id);

    $template->refresh();
    expect($template->title)->toBe('Updated Template')
        ->and($template->content)->toBe('New content')
        ->and($template->is_active)->toBeTrue();
});
