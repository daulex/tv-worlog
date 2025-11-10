<?php

use App\Models\Client;
use App\Models\Person;
use App\Models\Vacancy;
use Livewire\Livewire;

it('renders create person form', function () {
    Livewire::test('people.create')
        ->assertSee('Create Person')
        ->assertSee('First Name')
        ->assertSee('Last Name')
        ->assertSee('Email')
        ->assertSee('Personal Code')
        ->assertSee('Status')
        ->assertViewHas('clients')
        ->assertViewHas('vacancies');
});

it('creates person with valid data', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $client = Client::factory()->create();
    $vacancy = Vacancy::factory()->create(['client_id' => $client->id]);

    Livewire::test('people.create')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('pers_code', '280394-15750')
        ->set('phone', '+371 21234567')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->set('client_id', $client->id)
        ->set('vacancy_id', $vacancy->id)
        ->call('save')
        ->assertRedirect(route('people.index'));

    $this->assertDatabaseHas('people', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'pers_code' => '280394-15750',
        'phone' => '+371 21234567',
        'date_of_birth' => '1990-01-01 00:00:00',
        'status' => 'Employee',
        'client_id' => $client->id,
        'vacancy_id' => $vacancy->id,
    ]);
});

it('validates required fields', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test('people.create')
        ->call('save')
        ->assertHasErrors([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'pers_code' => 'required',
            'date_of_birth' => 'required',
            'status' => 'required',
        ]);
});

it('validates email format', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test('people.create')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'invalid-email')
        ->set('pers_code', '280394-15750')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertHasErrors(['email' => 'email']);
});

it('validates unique email', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Person::factory()->create(['email' => 'john@example.com']);

    Livewire::test('people.create')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('pers_code', '280394-15750')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertHasErrors(['email' => 'unique']);
});

it('validates unique personal code', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Person::factory()->create(['pers_code' => '280394-15750']);

    Livewire::test('people.create')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('pers_code', '280394-15750')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertHasErrors(['pers_code' => 'unique']);
});

it('validates status options', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test('people.create')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('pers_code', '280394-15750')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'InvalidStatus')
        ->call('save')
        ->assertHasErrors(['status' => 'in']);
});

it('validates date format', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test('people.create')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('pers_code', '280394-15750')
        ->set('date_of_birth', 'invalid-date')
        ->set('status', 'Employee')
        ->call('save')
        ->assertHasErrors(['date_of_birth' => 'date']);
});

it('creates person without optional fields', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test('people.create')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('pers_code', '280394-15750')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertRedirect(route('people.index'));

    $this->assertDatabaseHas('people', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'pers_code' => '280394-15750',
        'date_of_birth' => '1990-01-01 00:00:00',
        'status' => 'Employee',
        'phone' => null,
        'address' => null,
        'starting_date' => null,
        'last_working_date' => null,
        'position' => null,
        'client_id' => null,
        'vacancy_id' => null,
    ]);
});

it('shows vacancy options with company names', function () {
    $client = Client::factory()->create(['name' => 'Test Company']);
    $vacancy = Vacancy::factory()->create(['title' => 'Developer', 'client_id' => $client->id]);

    Livewire::test('people.create')
        ->assertSee('Developer - Test Company');
});

it('shows success message after creation', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test('people.create')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('pers_code', '280394-15750')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertRedirect(route('people.index'))
        ->assertSessionHas('message', 'Person created successfully.');
});

it('can create person with new fields', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test('people.create')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john.newfields@example.com')
        ->set('pers_code', '100259-16214')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->set('linkedin_profile', 'https://linkedin.com/in/johndoe')
        ->set('github_profile', 'https://github.com/johndoe')
        ->set('portfolio_url', 'https://johndoe.dev')
        ->set('emergency_contact_name', 'Jane Doe')
        ->set('emergency_contact_relationship', 'Spouse')
        ->set('emergency_contact_phone', '+371 23456789')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('people.index'));

    $this->assertDatabaseHas('people', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.newfields@example.com',
        'pers_code' => '100259-16214',
        'linkedin_profile' => 'https://linkedin.com/in/johndoe',
        'github_profile' => 'https://github.com/johndoe',
        'portfolio_url' => 'https://johndoe.dev',
        'emergency_contact_name' => 'Jane Doe',
        'emergency_contact_relationship' => 'Spouse',
        'emergency_contact_phone' => '+371 23456789',
    ]);
});

it('validates new fields on creation', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test('people.create')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john.validation@example.com')
        ->set('pers_code', '010190-11258')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
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
