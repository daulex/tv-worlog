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

    Livewire::test('people.edit', ['person' => $person])
        ->set('linkedin_profile', 'not-a-valid-url')
        ->set('github_profile', 'also-not-valid')
        ->set('portfolio_url', 'invalid-url')
        ->call('save')
        ->assertHasErrors([
            'linkedin_profile',
            'github_profile',
            'portfolio_url',
        ]);
});

it('can update new fields', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create([
        'linkedin_profile' => 'https://linkedin.com/in/original',
        'github_profile' => 'https://github.com/original',
        'portfolio_url' => 'https://original.dev',
        'emergency_contact_name' => 'Original Contact',
        'emergency_contact_relationship' => 'Spouse',
        'emergency_contact_phone' => '+371 21234567',
        'phone' => '+371 21234567',
        'phone2' => '+371 26123456',
        'last_working_date' => null, // Ensure no future date causes validation issues
    ]);

    Livewire::test('people.edit', ['person' => $person])
        ->set('linkedin_profile', 'https://linkedin.com/in/updated')
        ->set('github_profile', 'https://github.com/updated')
        ->set('portfolio_url', 'https://updated.dev')
        ->set('emergency_contact_name', 'Updated Contact')
        ->set('emergency_contact_relationship', 'Parent')
        ->set('emergency_contact_phone', '+371 23456789')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('people.show', $person));

    $person->refresh();

    expect($person->linkedin_profile)->toBe('https://linkedin.com/in/updated');
    expect($person->github_profile)->toBe('https://github.com/updated');
    expect($person->portfolio_url)->toBe('https://updated.dev');
    expect($person->emergency_contact_name)->toBe('Updated Contact');
    expect($person->emergency_contact_relationship)->toBe('Parent');
    expect($person->emergency_contact_phone)->toBe('+371 23456789');
});
