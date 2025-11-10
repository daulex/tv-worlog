<?php

use App\Models\Person;
use Livewire\Livewire;

it('can save and display new fields correctly', function () {
    $person = Person::factory()->create([
        'linkedin_profile' => 'https://linkedin.com/in/johndoe',
        'github_profile' => 'https://github.com/johndoe',
        'portfolio_url' => 'https://johndoe.dev',
        'emergency_contact_name' => 'Jane Doe',
        'emergency_contact_relationship' => 'Spouse',
        'emergency_contact_phone' => '+371 23456789',
    ]);

    $response = $this->actingAs($person)->get("/people/{$person->id}");

    $response->assertSuccessful();
    $response->assertSee('https://linkedin.com/in/johndoe');
    $response->assertSee('https://github.com/johndoe');
    $response->assertSee('https://johndoe.dev');
    $response->assertSee('Jane Doe');
    $response->assertSee('Spouse');
    $response->assertSee('+371 23456789');
});

it('validates new fields correctly', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create();

    Livewire::test('people.show', ['person' => $person])
        ->call('toggleEditMode')
        ->set('editForm.linkedin_profile', 'not-a-valid-url')
        ->set('editForm.github_profile', 'also-not-valid')
        ->set('editForm.portfolio_url', 'invalid-url')
        ->call('savePerson')
        ->assertHasErrors([
            'editForm.linkedin_profile',
            'editForm.github_profile',
            'editForm.portfolio_url',
        ]);
});

it('can update new fields', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create([
        'phone' => '+371 21234567',
        'phone2' => '+371 26123456',
    ]);

    Livewire::test('people.show', ['person' => $person])
        ->call('toggleEditMode')
        ->set('editForm.linkedin_profile', 'https://linkedin.com/in/updated')
        ->set('editForm.github_profile', 'https://github.com/updated')
        ->set('editForm.portfolio_url', 'https://updated.dev')
        ->set('editForm.emergency_contact_name', 'Updated Contact')
        ->set('editForm.emergency_contact_relationship', 'Parent')
        ->set('editForm.emergency_contact_phone', '+371 23456789')
        ->call('savePerson')
        ->assertHasNoErrors();

    $person->refresh();

    expect($person->linkedin_profile)->toBe('https://linkedin.com/in/updated');
    expect($person->github_profile)->toBe('https://github.com/updated');
    expect($person->portfolio_url)->toBe('https://updated.dev');
    expect($person->emergency_contact_name)->toBe('Updated Contact');
    expect($person->emergency_contact_relationship)->toBe('Parent');
    expect($person->emergency_contact_phone)->toBe('+371 23456789');
});
