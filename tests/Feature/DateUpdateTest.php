<?php

use App\Models\Person;

it('can update date of birth inline', function () {
    $person = Person::factory()->create(['date_of_birth' => '1990-01-01']);

    Livewire::test(\App\Livewire\People\Show::class, ['person' => $person])
        ->call('toggleEditMode')
        ->set('editForm.date_of_birth', '1985-05-15')
        ->call('savePerson')
        ->assertHasNoErrors();

    $person->refresh();
    expect($person->date_of_birth->format('Y-m-d'))->toBe('1985-05-15');
});

it('can update starting date inline', function () {
    $person = Person::factory()->create(['starting_date' => null]);

    Livewire::test(\App\Livewire\People\Show::class, ['person' => $person])
        ->call('toggleEditMode')
        ->set('editForm.starting_date', '2023-01-01')
        ->call('savePerson')
        ->assertHasNoErrors();

    $person->refresh();
    expect($person->starting_date->format('Y-m-d'))->toBe('2023-01-01');
});

it('can clear dates by setting to empty', function () {
    $person = Person::factory()->create(['starting_date' => '2020-01-01']);

    Livewire::test(\App\Livewire\People\Show::class, ['person' => $person])
        ->call('toggleEditMode')
        ->set('editForm.starting_date', '')
        ->call('savePerson')
        ->assertHasNoErrors();

    $person->refresh();
    expect($person->starting_date)->toBeNull();
});
