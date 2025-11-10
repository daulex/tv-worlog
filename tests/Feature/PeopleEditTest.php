<?php

use App\Models\Client;
use App\Models\Person;
use App\Models\Vacancy;
use Livewire\Livewire;

it('renders edit person form with existing data', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

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
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create();
    $newClient = Client::factory()->create();
    $newVacancy = Vacancy::factory()->create(['client_id' => $newClient->id]);

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'Jane')
        ->set('last_name', 'Smith')
        ->set('email', 'jane.updated@example.com')
        ->set('phone', '+371 21234567')
        ->set('phone2', '+371 61234567')
        ->set('email2', 'jane2.updated@example.com')
        ->set('date_of_birth', '1992-02-02')
        ->set('address', '456 Oak Ave')
        ->set('position', 'Designer')
        ->set('status', 'Candidate')
        ->set('client_id', $newClient->id)
        ->set('vacancy_id', $newVacancy->id)
        ->set('pers_code', '280394-15750') // Valid Latvian personal code
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('people.index'));

    $this->assertDatabaseHas('people', [
        'id' => $person->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.updated@example.com',
        'phone' => '+371 21234567',
        'phone2' => '+371 61234567',
        'email2' => 'jane2.updated@example.com',
        'date_of_birth' => '1992-02-02 00:00:00',
        'address' => '456 Oak Ave',
        'position' => 'Designer',
        'status' => 'Candidate',
        'client_id' => $newClient->id,
        'vacancy_id' => $newVacancy->id,
    ]);
});

it('validates required fields on update', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

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
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create();

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'invalid-email')
        ->set('pers_code', '280394-15750')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertHasErrors(['email' => 'email']);
});

it('validates secondary email format on update', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create();

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('email2', 'invalid-secondary-email')
        ->set('pers_code', '100259-16214')
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertHasErrors(['email2' => 'email']);
});

it('validates unique email on update (excluding current person)', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $existingPerson = Person::factory()->create();
    $person = Person::factory()->create();

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', $existingPerson->email) // Try to use existing person's email
        ->set('pers_code', '280394-15750') // Valid Latvian personal code
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertHasErrors(['email' => 'unique']);
});

it('allows keeping current email on update', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create();
    $newClient = Client::factory()->create();
    $newVacancy = Vacancy::factory()->create(['client_id' => $newClient->id]);

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', $person->email) // Keep the same email
        ->set('phone', '+371 21234567')
        ->set('phone2', '+371 61234567')
        ->set('email2', 'john2.updated@example.com')
        ->set('date_of_birth', '1992-02-02')
        ->set('address', '456 Oak Ave')
        ->set('position', 'Designer')
        ->set('status', 'Candidate')
        ->set('client_id', $newClient->id)
        ->set('vacancy_id', $newVacancy->id)
        ->set('pers_code', '280394-15750') // Valid Latvian personal code
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('people.index'));
});

it('validates unique personal code on update (excluding current person)', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $existingPerson = Person::factory()->create(['pers_code' => '280394-15750']);
    $person = Person::factory()->create(['pers_code' => '100259-16214']);

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', $person->email)
        ->set('pers_code', $existingPerson->pers_code)
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertHasErrors(['pers_code' => 'unique']);
});

it('validates status options on update', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create();

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', 'john@example.com')
        ->set('pers_code', '050471-12384') // Valid Latvian personal code
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'InvalidStatus')
        ->call('save')
        ->assertHasErrors(['status' => 'in']);
});

it('clears secondary fields when empty', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create([
        'pers_code' => '280394-15750', // Valid Latvian personal code
        'phone' => '+371 21234567',
        'phone2' => '+371 61234567',
        'email2' => 'secondary@example.com',
    ]);

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('email', $person->email)
        ->set('email2', '')
        ->set('phone2', '')
        ->set('pers_code', '190273-14178') // Valid Latvian personal code
        ->set('date_of_birth', '1990-01-01')
        ->set('status', 'Employee')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('people.index'));

    $person->refresh();
    expect($person->phone2)->toBeNull();
    expect($person->email2)->toBeNull();
});

it('shows vacancy options with company names', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $client = Client::factory()->create(['name' => 'Test Company']);
    $vacancy = Vacancy::factory()->create(['title' => 'Developer', 'client_id' => $client->id]);
    $person = Person::factory()->create();

    Livewire::test('people.edit', ['person' => $person])
        ->assertSee('Developer - Test Company');
});

it('shows success message after update', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::factory()->create();
    $newClient = Client::factory()->create();
    $newVacancy = Vacancy::factory()->create(['client_id' => $newClient->id]);

    Livewire::test('people.edit', ['person' => $person])
        ->set('first_name', 'Jane')
        ->set('last_name', 'Smith')
        ->set('email', $person->email)
        ->set('phone', '+371 21234567')
        ->set('phone2', '+371 61234567')
        ->set('email2', 'jane2.updated@example.com')
        ->set('date_of_birth', '1992-02-02')
        ->set('address', '456 Oak Ave')
        ->set('position', 'Designer')
        ->set('status', 'Candidate')
        ->set('client_id', $newClient->id)
        ->set('vacancy_id', $newVacancy->id)
        ->set('pers_code', '230663-10893')
        ->call('save')
        ->assertRedirect(route('people.index'))
        ->assertSessionHas('message', 'Person updated successfully.');
});
