<?php

use App\Models\Person;

it('renders fieldsets correctly in show view', function () {
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
    $response->assertSee('Personal Information');
    $response->assertSee('Professional Information');
    $response->assertSee('Professional Profiles');
    $response->assertSee('Emergency Contact');
});

it('renders fieldsets correctly in create view', function () {
    $person = Person::factory()->create();

    $response = $this->actingAs($person)->get('/people/create');

    $response->assertSuccessful();
    $response->assertSee('Personal Information');
    $response->assertSee('Contact Information');
    $response->assertSee('Professional Information');
    $response->assertSee('Professional Profiles');
    $response->assertSee('Emergency Contact');
});
