<?php

use App\Models\Client;
use App\Models\Person;
use App\Models\Vacancy;
use Livewire\Livewire;

it('loads all person fields into edit form correctly', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $client = Client::factory()->create();
    $vacancy = Vacancy::factory()->create(['client_id' => $client->id]);

    $person = Person::factory()->create([
        'status' => 'Employee',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'pers_code' => '161175-19997',
        'phone' => '+371 21234567',
        'phone2' => '+371 26123456',
        'email' => 'john.doe@example.com',
        'email2' => 'john.secondary@example.com',
        'date_of_birth' => '1990-01-15',
        'address' => '123 Main St, Riga, Latvia',
        'starting_date' => '2020-01-01',
        'last_working_date' => '2024-12-31',
        'position' => 'Senior Developer',
        'client_id' => $client->id,
        'vacancy_id' => $vacancy->id,
        'linkedin_profile' => 'https://linkedin.com/in/johndoe',
        'github_profile' => 'https://github.com/johndoe',
        'portfolio_url' => 'https://johndoe.dev',
        'emergency_contact_name' => 'Jane Doe',
        'emergency_contact_relationship' => 'Spouse',
        'emergency_contact_phone' => '+371 29876543',
    ]);

    $component = Livewire::test('people.edit', ['person' => $person]);

    // Test all fields are loaded correctly
    expect($component->get('first_name'))->toBe('John');
    expect($component->get('last_name'))->toBe('Doe');
    expect($component->get('pers_code'))->toBe('161175-19997');
    expect($component->get('phone'))->toBe('+371 21234567');
    expect($component->get('phone2'))->toBe('+371 26123456');
    expect($component->get('email'))->toBe('john.doe@example.com');
    expect($component->get('email2'))->toBe('john.secondary@example.com');
    expect($component->get('date_of_birth'))->toBe('1990-01-15');
    expect($component->get('address'))->toBe('123 Main St, Riga, Latvia');
    expect($component->get('starting_date'))->toBe('2020-01-01');
    expect($component->get('last_working_date'))->toBe('2024-12-31');
    expect($component->get('position'))->toBe('Senior Developer');
    expect($component->get('status'))->toBe('Employee');
    expect($component->get('client_id'))->toBe($client->id);
    expect($component->get('vacancy_id'))->toBe($vacancy->id);
    expect($component->get('linkedin_profile'))->toBe('https://linkedin.com/in/johndoe');
    expect($component->get('github_profile'))->toBe('https://github.com/johndoe');
    expect($component->get('portfolio_url'))->toBe('https://johndoe.dev');
    expect($component->get('emergency_contact_name'))->toBe('Jane Doe');
    expect($component->get('emergency_contact_relationship'))->toBe('Spouse');
    expect($component->get('emergency_contact_phone'))->toBe('+371 29876543');
});

it('can update all person fields successfully', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $client = Client::factory()->create();
    $vacancy = Vacancy::factory()->create(['client_id' => $client->id]);

    $person = Person::factory()->create([
        'status' => 'Candidate',
        'first_name' => 'Original',
        'last_name' => 'Name',
        'pers_code' => '161175-19997',
        'phone' => '+371 21234567',
        'email' => 'original@example.com',
        'date_of_birth' => '1990-01-15',
        'starting_date' => '2020-01-01',
        'last_working_date' => '2024-12-31',
    ]);

    $component = Livewire::test('people.edit', ['person' => $person]);

    // Update all fields
    $component
        ->set('status', 'Employee')
        ->set('first_name', 'Updated')
        ->set('last_name', 'Person')
        ->set('pers_code', '161175-19998')
        ->set('phone', '+371 29876543')
        ->set('phone2', '+371 23456789')
        ->set('email', 'updated@example.com')
        ->set('email2', 'updated.secondary@example.com')
        ->set('date_of_birth', '1985-05-20')
        ->set('address', '456 Updated St, Riga, Latvia')
        ->set('starting_date', '2021-06-01')
        ->set('last_working_date', '2024-12-31')
        ->set('position', 'Lead Developer')
        ->set('client_id', $client->id)
        ->set('vacancy_id', $vacancy->id)
        ->set('linkedin_profile', 'https://linkedin.com/in/updatedperson')
        ->set('github_profile', 'https://github.com/updatedperson')
        ->set('portfolio_url', 'https://updatedperson.dev')
        ->set('emergency_contact_name', 'Emergency Contact')
        ->set('emergency_contact_relationship', 'Parent')
        ->set('emergency_contact_phone', '+371 21234567')
        ->call('save')
        ->assertHasNoErrors();

    $person->refresh();

    // Verify all fields were updated
    expect($person->status)->toBe('Employee');
    expect($person->first_name)->toBe('Updated');
    expect($person->last_name)->toBe('Person');
    expect($person->pers_code)->toBe('161175-19998');
    expect($person->phone)->toBe('+371 29876543');
    expect($person->phone2)->toBe('+371 23456789');
    expect($person->email)->toBe('updated@example.com');
    expect($person->email2)->toBe('updated.secondary@example.com');
    expect($person->date_of_birth->format('Y-m-d'))->toBe('1985-05-20');
    expect($person->address)->toBe('456 Updated St, Riga, Latvia');
    expect($person->starting_date->format('Y-m-d'))->toBe('2021-06-01');
    expect($person->last_working_date->format('Y-m-d'))->toBe('2024-12-31');
    expect($person->position)->toBe('Lead Developer');
    expect($person->client_id)->toBe($client->id);
    expect($person->vacancy_id)->toBe($vacancy->id);
    expect($person->linkedin_profile)->toBe('https://linkedin.com/in/updatedperson');
    expect($person->github_profile)->toBe('https://github.com/updatedperson');
    expect($person->portfolio_url)->toBe('https://updatedperson.dev');
    expect($person->emergency_contact_name)->toBe('Emergency Contact');
    expect($person->emergency_contact_relationship)->toBe('Parent');
    expect($person->emergency_contact_phone)->toBe('+371 21234567');
});

it('can clear nullable fields by setting them to empty', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create([
        'phone2' => '+371 26123456',
        'email2' => 'secondary@example.com',
        'address' => '123 Main St',
        'starting_date' => '2020-01-01',
        'last_working_date' => '2024-12-31',
        'position' => 'Developer',
        'client_id' => Client::factory()->create()->id,
        'vacancy_id' => Vacancy::factory()->create()->id,
        'linkedin_profile' => 'https://linkedin.com/in/test',
        'github_profile' => 'https://github.com/test',
        'portfolio_url' => 'https://test.dev',
        'emergency_contact_name' => 'Emergency',
        'emergency_contact_relationship' => 'Spouse',
        'emergency_contact_phone' => '+371 29876543',
    ]);

    $component = Livewire::test('people.edit', ['person' => $person]);

    // Clear all nullable fields (but keep required ones)
    $component
        ->set('phone2', '')
        ->set('email2', '')
        ->set('address', '')
        ->set('starting_date', '')
        ->set('last_working_date', '')
        ->set('position', '')
        ->set('client_id', '')
        ->set('vacancy_id', '')
        ->set('linkedin_profile', '')
        ->set('github_profile', '')
        ->set('portfolio_url', '')
        ->set('emergency_contact_name', '')
        ->set('emergency_contact_relationship', '')
        ->set('emergency_contact_phone', '')
        ->call('save')
        ->assertHasNoErrors();

    $person->refresh();

    // Verify nullable fields are now null or empty
    expect($person->phone2)->toBeEmpty();
    expect($person->email2)->toBeEmpty();
    expect($person->address)->toBeEmpty();
    expect($person->starting_date)->toBeNull();
    expect($person->last_working_date)->toBeNull();
    expect($person->position)->toBeEmpty();
    expect($person->client_id)->toBeNull();
    expect($person->vacancy_id)->toBeNull();
    expect($person->linkedin_profile)->toBeEmpty();
    expect($person->github_profile)->toBeEmpty();
    expect($person->portfolio_url)->toBeEmpty();
    expect($person->emergency_contact_name)->toBeEmpty();
    expect($person->emergency_contact_relationship)->toBeEmpty();
    expect($person->emergency_contact_phone)->toBeEmpty();
});

it('validates required fields cannot be empty', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create();

    $component = Livewire::test('people.edit', ['person' => $person]);

    // Try to save with empty required fields
    $component
        ->set('first_name', '')
        ->set('last_name', '')
        ->set('pers_code', '')
        ->set('email', '')
        ->set('date_of_birth', '')
        ->set('status', '')
        ->call('save')
        ->assertHasErrors([
            'first_name' => 'required',
            'last_name' => 'required',
            'pers_code' => 'required',
            'email' => 'required',
            'date_of_birth' => 'required',
            'status' => 'required',
        ]);
});

it('validates email and URL formats', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create();

    $component = Livewire::test('people.edit', ['person' => $person]);

    // Try to save with invalid formats
    $component
        ->set('email', 'invalid-email')
        ->set('email2', 'invalid-email')
        ->set('linkedin_profile', 'not-a-url')
        ->set('github_profile', 'not-a-url')
        ->set('portfolio_url', 'not-a-url')
        ->call('save')
        ->assertHasErrors([
            'email' => 'email',
            'email2' => 'email',
            'linkedin_profile' => 'url',
            'github_profile' => 'url',
            'portfolio_url' => 'url',
        ]);
});

it('validates unique constraints on update', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $existingPerson = Person::factory()->create([
        'email' => 'existing@example.com',
        'pers_code' => '161175-19997',
    ]);

    $person = Person::factory()->create();

    $component = Livewire::test('people.edit', ['person' => $person]);

    // Try to use existing email and pers_code
    $component
        ->set('email', 'existing@example.com')
        ->set('pers_code', '161175-19997')
        ->call('save')
        ->assertHasErrors([
            'email' => 'unique',
            'pers_code' => 'unique',
        ]);
});
