<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::redirect('/', '/login')->name('home');

Route::get('dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // People routes
    Route::get('people', \App\Livewire\People\Index::class)->name('people.index');
    Route::get('people/create', \App\Livewire\People\Create::class)->name('people.create');
    Route::get('people/{person}', \App\Livewire\People\Show::class)->name('people.show');
    Route::get('people/{person}/edit', \App\Livewire\People\Edit::class)->name('people.edit');

    // Client routes
    Route::get('clients', \App\Livewire\Clients\Index::class)->name('clients.index');
    Route::get('clients/create', \App\Livewire\Clients\Create::class)->name('clients.create');
    Route::get('clients/{client}', \App\Livewire\Clients\Show::class)->name('clients.show');
    Route::get('clients/{client}/edit', \App\Livewire\Clients\Edit::class)->name('clients.edit');

    // Vacancy routes
    Route::get('vacancies', \App\Livewire\Vacancies\Index::class)->name('vacancies.index');
    Route::get('vacancies/create', \App\Livewire\Vacancies\Create::class)->name('vacancies.create');
    Route::get('vacancies/{vacancy}', \App\Livewire\Vacancies\Show::class)->name('vacancies.show');
    Route::get('vacancies/{vacancy}/edit', \App\Livewire\Vacancies\Edit::class)->name('vacancies.edit');

    // Equipment routes
    Route::get('equipment', \App\Livewire\Equipment\Index::class)->name('equipment.index');
    Route::get('equipment/create', \App\Livewire\Equipment\Create::class)->name('equipment.create');
    Route::get('equipment/{equipment}', \App\Livewire\Equipment\Show::class)->name('equipment.show');
    Route::get('equipment/{equipment}/edit', \App\Livewire\Equipment\Edit::class)->name('equipment.edit');

    // Event routes
    Route::get('events', \App\Livewire\Events\Index::class)->name('events.index');
    Route::get('events/create', \App\Livewire\Events\Create::class)->name('events.create');
    Route::get('events/{event}', \App\Livewire\Events\Show::class)->name('events.show');
    Route::get('events/{event}/edit', \App\Livewire\Events\Edit::class)->name('events.edit');

    // Note routes
    Route::get('notes', \App\Livewire\Notes\Index::class)->name('notes.index');
    Route::get('notes/create', \App\Livewire\Notes\Create::class)->name('notes.create');
    Route::get('notes/{note}', \App\Livewire\Notes\Show::class)->name('notes.show');
    Route::get('notes/{note}/edit', \App\Livewire\Notes\Edit::class)->name('notes.edit');

    // File routes
    Route::get('files', \App\Livewire\Files\Index::class)->name('files.index');
    Route::get('files/create', \App\Livewire\Files\Create::class)->name('files.create');
    Route::get('files/{file}', \App\Livewire\Files\Show::class)->name('files.show');
    Route::get('files/{file}/edit', \App\Livewire\Files\Edit::class)->name('files.edit');
    Route::get('files/{file}/download', [\App\Http\Controllers\FileController::class, 'download'])->name('files.download');
});
