<?php

use App\Models\Client;
use App\Models\Person;
use Livewire\Livewire;

it('prevents deletion of client with associated people', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $client = Client::factory()->create();
    $person = Person::factory()->create(['client_id' => $client->id]);

    $component = Livewire::test('clients.edit', ['client' => $client]);

    $component->call('delete');

    $component->assertHasErrors('delete');

    expect(Client::find($client->id))->not->toBeNull();
});

it('prevents deletion of client with associated vacancies', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $client = Client::factory()->create();
    $vacancy = \App\Models\Vacancy::factory()->create(['client_id' => $client->id]);

    $component = Livewire::test('clients.edit', ['client' => $client]);

    $component->call('delete');

    $component->assertHasErrors('delete');

    expect(Client::find($client->id))->not->toBeNull();
});

it('allows deletion of client with no associations', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $client = Client::factory()->create();

    $component = Livewire::test('clients.edit', ['client' => $client]);

    $component->call('delete');

    $component->assertHasNoErrors();

    expect(Client::find($client->id))->toBeNull();
});
