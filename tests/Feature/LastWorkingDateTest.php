<?php

use App\Models\Person;

it('can update last working date inline', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create([
        'phone' => '+371 21234567',
        'phone2' => '+371 26123456',
    ]);

    Livewire::test('people.show', ['person' => $person])
        ->call('toggleEditMode')
        ->set('editForm.last_working_date', '2024-12-31')
        ->call('savePerson')
        ->assertHasNoErrors();

    $person->refresh();
    expect($person->last_working_date->format('Y-m-d'))->toBe('2024-12-31');
});

it('can clear last working date by setting to empty', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create([
        'last_working_date' => '2024-12-31',
        'phone' => '+371 21234567',
        'phone2' => '+371 26123456',
    ]);

    Livewire::test('people.show', ['person' => $person])
        ->call('toggleEditMode')
        ->set('editForm.last_working_date', '')
        ->call('savePerson')
        ->assertHasNoErrors();

    $person->refresh();
    expect($person->last_working_date)->toBeNull();
});

it('displays last working date in view mode', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create([
        'last_working_date' => '2024-12-31',
        'phone' => '+371 21234567',
        'phone2' => '+371 26123456',
    ]);

    Livewire::test('people.show', ['person' => $person])
        ->assertSee('Dec 31, 2024');
});

it('validates last working date format', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create([
        'phone' => '+371 21234567',
        'phone2' => '+371 26123456',
    ]);

    Livewire::test('people.show', ['person' => $person])
        ->call('toggleEditMode')
        ->set('editForm.last_working_date', 'invalid-date')
        ->call('savePerson')
        ->assertHasErrors(['editForm.last_working_date' => 'date']);
});
