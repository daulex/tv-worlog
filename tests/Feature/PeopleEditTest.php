<?php

use App\Models\Client;
use App\Models\Person;
use App\Models\Vacancy;
use Livewire\Livewire;

it('renders edit person form with existing data', function () {
    $client = Client::factory()->create();
    $vacancy = Vacancy::factory()->create(['client_id' => $client->id]);
    $person = Person::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '555-1234',
        'phone2' => '555-5678',
        'email2' => 'john2@example.com',
        'date_of_birth' => '1990-01-01',
        'address' => '123 Main St',
        'position' => 'Developer',
        'status' => 'Employee',
        'client_id' => $client->id,
        'vacancy_id' => $vacancy->id,
    ]);

    Livewire::test('people.edit', ['person' => $person])
        ->assertSee('Edit Person')
        ->assertSet('first_name', 'John')
        ->assertSet('last_name', 'Doe')
        ->assertSet('email', 'john@example.com')
        ->assertSet('phone', '555-1234')
        ->assertSet('phone2', '555-5678')
        ->assertSet('email2', 'john2@example.com')
        ->assertSet('address', '123 Main St')
        ->assertSet('position', 'Developer')
        ->assertSet('status', 'Employee')
        ->assertSet('client_id', $client->id)
        ->assertSet('vacancy_id', $vacancy->id);
});

it('updates person with valid data', function () {
    $person = Person::factory()->create();
    $newClient = Client::factory()->create();
    $newVacancy = Vacancy::factory()->create(['client_id' => $newClient->id]);

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'Jane')
        ->set('last_name', 'Smith')
        ->set('email', 'jane@example.com')
        ->set('phone', '555-9999')
        ->set('phone2', '555-8888')
        ->set('email2', 'jane2@example.com')
        ->set('date_of_birth', '1992-02-02')
        ->set('address', '456 Oak Ave')
        ->set('position', 'Designer')
        ->set('status', 'Candidate')
        ->set('client_id', $newClient->id)
        ->set('vacancy_id', $newVacancy->id)
        ->call('save')
        ->assertRedirect(route('people.index'));

    $this->assertDatabaseHas('people', [
        'id' => $person->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane@example.com',
        'phone' => '555-9999',
        'phone2' => '555-8888',
        'email2' => 'jane2@example.com',
        'date_of_birth' => '1992-02-02 00:00:00',
        'address' => '456 Oak Ave',
        'position' => 'Designer',
        'status' => 'Candidate',
        'client_id' => $newClient->id,
        'vacancy_id' => $newVacancy->id,
    ]);
});

it('validates required fields on update', function () {
    $person = Person::factory()->create();

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', '')
        ->set('last_name', '')
        ->set('email', '')
        ->set('pers_code', '')
        ->set('date_of_birth', '')
        ->set('status', '')
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

it('validates email format on update', function () {
    $person = Person::factory()->create();

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'invalid-email')
        ->set('pers_code', '123456')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertHasErrors(['email' => 'email']);
});

it('validates secondary email format on update', function () {
    $person = Person::factory()->create();

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('email2', 'invalid-secondary-email')
        ->set('pers_code', '123456')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertHasErrors(['email2' => 'email']);
});

it('validates unique email on update (excluding current person)', function () {
    $existingPerson = Person::factory()->create(['email' => 'existing@example.com']);
    $person = Person::factory()->create(['email' => 'current@example.com']);

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'existing@example.com')
        ->set('pers_code', '123456')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertHasErrors(['email' => 'unique']);
});

it('allows keeping current email on update', function () {
    $person = Person::factory()->create(['email' => 'current@example.com']);

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'current@example.com')
        ->set('pers_code', '123456')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertRedirect(route('people.index'))
        ->assertHasNoErrors();
});

it('validates unique personal code on update (excluding current person)', function () {
    $existingPerson = Person::factory()->create(['pers_code' => '987654']);
    $person = Person::factory()->create(['pers_code' => '123456']);

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('pers_code', '987654')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertHasErrors(['pers_code' => 'unique']);
});

it('validates status options on update', function () {
    $person = Person::factory()->create();

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('pers_code', '123456')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'InvalidStatus')
        ->call('save')
        ->assertHasErrors(['status' => 'in']);
});

it('clears secondary fields when empty', function () {
    $person = Person::factory()->create([
        'phone2' => '555-5678',
        'email2' => 'secondary@example.com',
    ]);

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('email2', '')
        ->set('phone2', '')
        ->set('pers_code', '123456')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertRedirect(route('people.index'));

    $person->refresh();
    expect($person->phone2)->toBeNull();
    expect($person->email2)->toBeNull();
});

it('shows vacancy options with company names', function () {
    $client = Client::factory()->create(['name' => 'Test Company']);
    $vacancy = Vacancy::factory()->create(['title' => 'Developer', 'client_id' => $client->id]);
    $person = Person::factory()->create();

    Livewire::test('people.edit', ['person' => $person])
        ->assertSee('Developer - Test Company');
});

it('shows success message after update', function () {
    $person = Person::factory()->create();

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'Jane')
        ->set('last_name', 'Smith')
        ->set('email', 'jane@example.com')
        ->set('pers_code', '987654-32101')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertRedirect(route('people.index'))
        ->assertSessionHas('message', 'Person updated successfully.');
});
