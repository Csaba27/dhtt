<?php

use App\Http\Controllers\Auth\TwoFaController;
use App\Http\Controllers\DhttController;
use App\Http\Controllers\HomeController;
use App\Livewire\Admin\AuditTrails;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Dhtt\Events\EventFormWizard;
use App\Livewire\Admin\Dhtt\Hikes\HikeFormWizard;
use App\Livewire\Admin\Dhtt\Panel;
use App\Livewire\Admin\Dhtt\Participants\ParticipantsForm;
use App\Livewire\Admin\Roles\Edit;
use App\Livewire\Admin\Roles\Roles;
use App\Livewire\Admin\Settings\Settings;
use App\Livewire\Admin\Users\EditUser;
use App\Livewire\Admin\Users\ShowUser;
use App\Livewire\Admin\Users\Users;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('livewire/update', $handle);
});

Route::get('/', HomeController::class)->name('home');

Route::prefix('dhtt')->name('dhtt.')->controller(DhttController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('registration', 'registration')->name('registration');
    Route::get('event', 'event')->name('event');
    Route::get('archive/{event?}', 'archive')->name('archive');
});

Route::prefix(config('admintw.prefix'))->middleware(['auth', 'verified', 'activeUser', 'ipCheckMiddleware'])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::view('developer-reference', 'developer-reference')
        ->name('developer-reference');

    Route::get('2fa', [TwoFaController::class, 'index'])->name('admin.2fa');
    Route::post('2fa', [TwoFaController::class, 'update'])->name('admin.2fa.update');
    Route::get('2fa-setup', [TwoFaController::class, 'setup'])->name('admin.2fa-setup');
    Route::post('2fa-setup', [TwoFaController::class, 'setupUpdate'])->name('admin.2fa-setup.update');

    Route::prefix('settings')->group(function () {
        Route::get('audit-trails', AuditTrails::class)->name('admin.settings.audit-trails.index');
        Route::get('system-settings', Settings::class)->name('admin.settings');
        Route::get('roles', Roles::class)->name('admin.settings.roles.index');
        Route::get('roles/{role}/edit', Edit::class)->name('admin.settings.roles.edit');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', Users::class)->name('admin.users.index');
        Route::get('{user}/edit', EditUser::class)->name('admin.users.edit');
        Route::get('{user}', ShowUser::class)->name('admin.users.show');
    });

    Route::prefix('dhtt')->name('admin.dhtt.')->group(function () {
        Route::get('/', Panel::class)->name('index');

        Route::get('events/create', EventFormWizard::class)->name('events.create');
        Route::get('events/edit/{event}', EventFormWizard::class)->name('events.edit');

        Route::get('hikes/create', HikeFormWizard::class)->name('hikes.create');
        Route::get('hikes/edit/{hike}', HikeFormWizard::class)->name('hikes.edit');

        Route::get('participants/create/{event?}', ParticipantsForm::class)->name('participants.create');
        Route::get('participants/edit/{participant}', ParticipantsForm::class)->name('participants.edit');
    });
});

require __DIR__.'/auth.php';
