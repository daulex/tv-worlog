<?php

use App\Models\Person;
use App\Models\Vacancy;
use Livewire\Livewire;

it('prevents deletion of vacancy with associated people', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $vacancy = Vacancy::factory()->create();
    $person = Person::factory()->create(['vacancy_id' => $vacancy->id]);

    $component = Livewire::test('vacancies.edit', ['vacancy' => $vacancy]);

    $component->call('delete');

    $component->assertHasErrors('delete');

    expect(Vacancy::find($vacancy->id))->not->toBeNull();
});

it('allows deletion of vacancy with no associations', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $vacancy = Vacancy::factory()->create();

    $component = Livewire::test('vacancies.edit', ['vacancy' => $vacancy]);

    $component->call('delete');

    $component->assertHasNoErrors();

    expect(Vacancy::find($vacancy->id))->toBeNull();
});

it('can unassign people from vacancy', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $vacancy = Vacancy::factory()->create();
    $person = Person::factory()->create(['vacancy_id' => $vacancy->id]);

    $component = Livewire::test('vacancies.edit', ['vacancy' => $vacancy]);

    $component->call('unassignPerson', $person->id);

    $component->assertHasNoErrors();

    $person->refresh();
    expect($person->vacancy_id)->toBeNull();
});
