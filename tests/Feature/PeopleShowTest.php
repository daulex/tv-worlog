<?php

use App\Models\Client;
use App\Models\Person;
use Livewire\Livewire;

it('displays person details correctly', function () {
    $client = Client::factory()->create();
    $person = Person::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '555-1234',
        'position' => 'Developer',
        'status' => 'Employee',
        'client_id' => $client->id,
    ]);

    Livewire::test('people.show', ['person' => $person])
        ->assertSee('John Doe')
        ->assertSee('john@example.com')
        ->assertSee('555-1234')
        ->assertSee('Developer')
        ->assertSee('Employee')
        ->assertSee($client->name);
});

it('can add a note to a person', function () {
    $person = Person::factory()->create();

    Livewire::test('people.show', ['person' => $person])
        ->set('newNote', 'Test note content')
        ->call('addNote')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('notes', [
        'entity_id' => $person->id,
        'note_type' => 'person',
        'note_text' => 'Test note content',
    ]);
});

it('can edit person information', function () {
    $client = Client::factory()->create();
    $person = Person::factory()->create(['first_name' => 'Jane']);

    Livewire::test('people.show', ['person' => $person])
        ->call('toggleEditMode')
        ->set('editForm.first_name', 'Sarah')
        ->set('editForm.client_id', $client->id)
        ->call('savePerson')
        ->assertHasNoErrors();

    $person->refresh();
    expect($person->first_name)->toBe('Sarah');
    expect($person->client_id)->toBe($client->id);
});

it('displays timeline items in correct order', function () {
    $person = Person::factory()->create();

    // Create a note
    $note = $person->notes()->create([
        'note_type' => 'person',
        'entity_id' => $person->id,
        'note_text' => 'First note',
    ]);

    // Update person to create history
    $person->update(['position' => 'Senior Developer']);

    // Create another note
    $note2 = $person->notes()->create([
        'note_type' => 'person',
        'entity_id' => $person->id,
        'note_text' => 'Second note',
    ]);

    // Create a new component instance to test the method
    $component = new \App\Livewire\People\Show;
    $component->person = $person->fresh(['personHistory', 'notes', 'events']);
    $timeline = $component->getTimeline();

    // Should have 4 items: 2 notes + 2 history (creation + update)
    expect($timeline)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($timeline->count())->toBe(4);

    // Items are sorted by date (newest first), then by ID (newest first) when dates are equal
    // Since history items are created after notes, they have higher IDs
    expect($timeline[0]['type'])->toBe('history'); // History update (newest ID)
    expect($timeline[1]['type'])->toBe('note'); // Second note
    expect($timeline[2]['type'])->toBe('history'); // History creation
    expect($timeline[3]['type'])->toBe('note'); // First note (oldest ID)
});

it('validates note input', function () {
    $person = Person::factory()->create();

    Livewire::test('people.show', ['person' => $person])
        ->call('addNote')
        ->assertHasErrors(['newNote' => 'required']);
});

it('can add and edit secondary email and phone', function () {
    $person = Person::factory()->create();

    Livewire::test('people.show', ['person' => $person])
        ->call('toggleEditMode')
        ->set('editForm.email2', 'secondary@example.com')
        ->set('editForm.phone2', '555-9876')
        ->call('savePerson')
        ->assertHasNoErrors();

    $person->refresh();
    expect($person->email2)->toBe('secondary@example.com');
    expect($person->phone2)->toBe('555-9876');
});

it('validates secondary email format', function () {
    $person = Person::factory()->create();

    Livewire::test('people.show', ['person' => $person])
        ->call('toggleEditMode')
        ->set('editForm.email2', 'invalid-email')
        ->call('savePerson')
        ->assertHasErrors(['editForm.email2' => 'email']);
});

it('can clear secondary email and phone', function () {
    $person = Person::factory()->create([
        'email2' => 'old.secondary@example.com',
        'phone2' => '555-1234',
    ]);

    Livewire::test('people.show', ['person' => $person])
        ->call('toggleEditMode')
        ->set('editForm.email2', '')
        ->set('editForm.phone2', '')
        ->call('savePerson')
        ->assertHasNoErrors();

    $person->refresh();
    expect($person->email2)->toBeNull();
    expect($person->phone2)->toBeNull();
});

it('validates person edit form', function () {
    $person = Person::factory()->create();

    Livewire::test('people.show', ['person' => $person])
        ->call('toggleEditMode')
        ->set('editForm.first_name', '') // Empty first name should fail
        ->call('savePerson')
        ->assertHasErrors(['editForm.first_name' => 'required']);
});
