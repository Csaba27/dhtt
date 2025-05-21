<?php

use App\Models\Setting;

use function Pest\Laravel\get;

test('settings are applied', function () {
    $this->authenticate();

    // App name
    Setting::create([
        'key' => 'app_name',
        'value' => 'Demo',
    ]);

    expect(Setting::first()->value)->toBe('Demo');

    get(route('dashboard'))->assertOk();
});

test('cannot register if registration is disabled', function () {
    // Turn off registration
    Setting::create([
        'key' => 'allow_registration',
        'value' => '0',
    ]);

    expect(Setting::first()->value)->toBe('0');
    get(route('register'))->assertForbidden();
});
