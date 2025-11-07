<?php

use App\Models\Client;
use App\Models\Person;
use App\Models\Vacancy;
use Livewire\Livewire;

it('displays people list with pagination', function () {
    Person::factory()->count(60)->create();

    Livewire::test('people.index')
        ->assertSee('People')
        ->assertViewHas('people')
        ->assertViewIs('livewire.people.index');
});

it('displays client and vacancy columns', function () {
    $client = Client::factory()->create(['name' => 'Test Client']);
    $vacancy = Vacancy::factory()->create(['title' => 'Test Vacancy', 'client_id' => $client->id]);
    $person = Person::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'client_id' => $client->id,
        'vacancy_id' => $vacancy->id,
    ]);

    Livewire::test('people.index')
        ->assertSee('John Doe')
        ->assertSee('Test Client')
        ->assertSee('Test Vacancy')
        ->assertSee('Client')
        ->assertSee('Vacancy');
});

it('searches people by name', function () {
    Person::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
    Person::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith']);

    Livewire::test('people.index')
        ->set('search', 'John')
        ->assertSee('John Doe')
        ->assertDontSee('Jane Smith');
});

it('searches people by email', function () {
    Person::factory()->create(['email' => 'john@example.com']);
    Person::factory()->create(['email' => 'jane@example.com']);

    Livewire::test('people.index')
        ->set('search', 'john@example.com')
        ->assertSee('john@example.com')
        ->assertDontSee('jane@example.com');
});

it('filters by status', function () {
    $employee = Person::factory()->create(['status' => 'Employee', 'first_name' => 'John']);
    $candidate = Person::factory()->create(['status' => 'Candidate', 'first_name' => 'Jane']);

    Livewire::test('people.index')
        ->set('statusFilter', 'Employee')
        ->assertSee('John')
        ->assertDontSee('Jane');
});

it('filters by client', function () {
    $client1 = Client::factory()->create(['name' => 'Client A']);
    $client2 = Client::factory()->create(['name' => 'Client B']);
    $person1 = Person::factory()->create(['client_id' => $client1->id, 'first_name' => 'John']);
    $person2 = Person::factory()->create(['client_id' => $client2->id, 'first_name' => 'Jane']);

    Livewire::test('people.index')
        ->set('clientFilter', $client1->id)
        ->assertSee('John')
        ->assertDontSee('Jane');
});

it('filters by vacancy', function () {
    $vacancy1 = Vacancy::factory()->create(['title' => 'Developer']);
    $vacancy2 = Vacancy::factory()->create(['title' => 'Designer']);
    $person1 = Person::factory()->create(['vacancy_id' => $vacancy1->id, 'first_name' => 'John']);
    $person2 = Person::factory()->create(['vacancy_id' => $vacancy2->id, 'first_name' => 'Jane']);

    Livewire::test('people.index')
        ->set('vacancyFilter', $vacancy1->id)
        ->assertSee('John')
        ->assertDontSee('Jane');
});

it('clears all filters', function () {
    Person::factory()->create(['status' => 'Employee', 'email' => 'test@example.com']);

    Livewire::test('people.index')
        ->set('search', 'test')
        ->set('statusFilter', 'Employee')
        ->call('clearFilters')
        ->assertSet('search', '')
        ->assertSet('statusFilter', '')
        ->assertSet('clientFilter', '')
        ->assertSet('vacancyFilter', '');
});

it('shows no people found message', function () {
    Livewire::test('people.index')
        ->assertSee('No people found');
});

it('displays status badges with correct colors', function () {
    $employee = Person::factory()->create(['status' => 'Employee']);
    $candidate = Person::factory()->create(['status' => 'Candidate']);
    $retired = Person::factory()->create(['status' => 'Retired']);

    Livewire::test('people.index')
        ->assertSee('Employee')
        ->assertSee('Candidate')
        ->assertSee('Retired');
});

it('shows fallback for missing client and vacancy', function () {
    $person = Person::factory()->create([
        'first_name' => 'John',
        'client_id' => null,
        'vacancy_id' => null,
    ]);

    Livewire::test('people.index')
        ->assertSee('John')
        ->assertSee('-'); // Should show dash for missing client/vacancy
});

it('deletes person with confirmation', function () {
    $person = Person::factory()->create();

    Livewire::test('people.index')
        ->call('delete', $person);

    $this->assertModelMissing($person);
    $this->assertDatabaseMissing('people', ['id' => $person->id]);
});

it('loads filter options correctly', function () {
    $client = Client::factory()->create(['name' => 'Test Client']);
    $vacancy = Vacancy::factory()->create(['title' => 'Test Vacancy']);

    Livewire::test('people.index')
        ->assertViewHas('clients')
        ->assertViewHas('vacancies')
        ->assertSee('Test Client')
        ->assertSee('Test Vacancy');
});
