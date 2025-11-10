<?php

use App\Models\CV;
use App\Models\Person;
use Livewire\Livewire;

test('cv create component validation works', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test(\App\Livewire\CVs\Create::class)
        ->set('person_id', '')
        ->set('file_path_or_url', '')
        ->call('save')
        ->assertHasErrors(['person_id', 'file_path_or_url']);
});

test('cv create component renders form', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test(\App\Livewire\CVs\Create::class)
        ->assertSee('Create CV')
        ->assertSee('Person')
        ->assertSee('File Path/URL');
});

test('cv index component renders correctly', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test(\App\Livewire\CVs\Index::class)
        ->assertSee('CVs')
        ->assertSee('Add CV')
        ->assertSee('Search CVs');
});

test('cv edit component renders form', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $person = Person::create([
        'status' => 'Candidate',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'pers_code' => 'ABC1234',
        'email' => 'john@example.com',
        'date_of_birth' => '1990-01-01',
        'password' => 'password',
    ]);

    $cv = CV::create([
        'person_id' => $person->id,
        'file_path_or_url' => '/test/cv.pdf',
        'uploaded_at' => now(),
    ]);

    Livewire::test(\App\Livewire\CVs\Edit::class, ['cv' => $cv])
        ->assertSee('Edit CV');
});
