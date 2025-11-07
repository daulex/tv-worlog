<?php

use App\Models\Equipment;
use App\Models\Person;
use Livewire\Livewire;

it('displays equipment details correctly', function () {
    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $equipment = Equipment::factory()->create([
        'current_holder_id' => $person->id,
    ]);

    $response = $this->actingAs($user)->get("/equipment/{$equipment->id}");

    $response->assertStatus(200)
        ->assertSee($equipment->brand)
        ->assertSee($equipment->model)
        ->assertSee($equipment->serial)
        ->assertSee($person->full_name);
});

it('shows equipment history timeline', function () {
    $user = Person::factory()->create();
    $equipment = Equipment::factory()->create();

    $response = $this->actingAs($user)->get("/equipment/{$equipment->id}");

    $response->assertStatus(200)
        ->assertSee('Timeline & History');
});

it('can add a note to equipment', function () {
    $user = Person::factory()->create();
    $equipment = Equipment::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('toggleNoteForm')
        ->set('newNote', 'Test note for equipment')
        ->call('addNote')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('notes', [
        'note_type' => 'equipment',
        'entity_id' => $equipment->id,
        'note_text' => 'Test note for equipment',
    ]);
});

it('can edit equipment details inline', function () {
    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $equipment = Equipment::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('toggleEditMode')
        ->set('editForm.brand', 'Updated Brand')
        ->set('editForm.model', 'Updated Model')
        ->set('editForm.serial', 'Updated Serial')
        ->set('editForm.purchase_date', '2024-01-01')
        ->set('editForm.purchase_price', '999.99')
        ->set('editForm.current_holder_id', $person->id)
        ->call('saveEquipment')
        ->assertHasNoErrors();

    $equipment->refresh();
    expect($equipment->brand)->toBe('Updated Brand');
    expect($equipment->model)->toBe('Updated Model');
    expect($equipment->current_holder_id)->toBe($person->id);
});

it('creates history record when equipment holder changes', function () {
    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $equipment = Equipment::factory()->create(['current_holder_id' => null]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('toggleEditMode')
        ->set('editForm.current_holder_id', $person->id)
        ->call('saveEquipment')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('equipment_history', [
        'equipment_id' => $equipment->id,
        'holder_id' => $person->id,
        'action_type' => 'assigned',
        'performed_by_id' => $user->id,
    ]);
});

it('validates equipment edit form', function () {
    $user = Person::factory()->create();
    $equipment = Equipment::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('toggleEditMode')
        ->set('editForm.brand', '')
        ->set('editForm.purchase_price', -50)
        ->call('saveEquipment')
        ->assertHasErrors([
            'editForm.brand' => 'required',
            'editForm.purchase_price' => 'min',
        ]);
});

it('can retire equipment with notes', function () {
    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $equipment = Equipment::factory()->create(['current_holder_id' => $person->id]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('toggleRetireForm')
        ->set('retirementNotes', 'Equipment is obsolete and being replaced')
        ->call('retireEquipment')
        ->assertHasNoErrors();

    $equipment->refresh();
    expect($equipment->isRetired())->toBeTrue();
    expect($equipment->current_holder_id)->toBeNull();
    expect($equipment->retirement_notes)->toBe('Equipment is obsolete and being replaced');

    $this->assertDatabaseHas('equipment_history', [
        'equipment_id' => $equipment->id,
        'action_type' => 'retired',
        'performed_by_id' => $user->id,
    ]);
});

it('can unretire equipment', function () {
    $user = Person::factory()->create();
    $equipment = Equipment::factory()->create([
        'retired_at' => now(),
        'retirement_notes' => 'Previously retired',
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('unretireEquipment')
        ->assertHasNoErrors();

    $equipment->refresh();
    expect($equipment->isRetired())->toBeFalse();
    expect($equipment->retirement_notes)->toBeNull();

    $this->assertDatabaseHas('equipment_history', [
        'equipment_id' => $equipment->id,
        'action_type' => 'purchased',
        'action' => 'Equipment returned to service',
        'performed_by_id' => $user->id,
    ]);
});

it('validates retirement form', function () {
    $user = Person::factory()->create();
    $equipment = Equipment::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('toggleRetireForm')
        ->set('retirementNotes', '')
        ->call('retireEquipment')
        ->assertHasErrors([
            'retirementNotes' => 'required',
        ]);
});

it('can edit note history entries', function () {
    $user = Person::factory()->create();
    $equipment = Equipment::factory()->create();

    $history = $equipment->equipmentHistory()->create([
        'owner_id' => null,
        'change_date' => now(),
        'action' => 'Original note',
        'action_type' => 'note',
        'notes' => 'Original note content',
        'performed_by_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('editHistory', $history->id)
        ->set('historyEditForm.action', 'Updated note')
        ->set('historyEditForm.notes', 'Updated note content')
        ->call('saveHistoryEdit')
        ->assertHasNoErrors();

    $history->refresh();
    expect($history->action)->toBe('Updated note');
    expect($history->notes)->toBe('Updated note content');
});

it('cannot edit protected history types', function () {
    $user = Person::factory()->create();
    $equipment = Equipment::factory()->create();

    $history = $equipment->equipmentHistory()->create([
        'holder_id' => null,
        'change_date' => now(),
        'action' => 'Equipment purchased',
        'action_type' => 'purchased',
        'notes' => 'Purchase record',
        'performed_by_id' => $user->id,
    ]);

    $component = Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('editHistory', $history->id);

    // Should not be in editing mode for protected types
    $this->assertNull($component->get('editingHistory'));
});

it('can delete note history entries', function () {
    $user = Person::factory()->create();
    $equipment = Equipment::factory()->create();

    $history = $equipment->equipmentHistory()->create([
        'holder_id' => null,
        'change_date' => now(),
        'action' => 'Original note',
        'action_type' => 'note',
        'notes' => 'Original note content',
        'performed_by_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('deleteHistory', $history->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('equipment_history', [
        'id' => $history->id,
    ]);

    // Should have a deletion tracking entry
    $this->assertDatabaseHas('equipment_history', [
        'equipment_id' => $equipment->id,
        'action_type' => 'note',
        'action' => 'History entry deleted',
        'performed_by_id' => $user->id,
    ]);
});

it('cannot delete protected history types', function () {
    $user = Person::factory()->create();
    $equipment = Equipment::factory()->create();

    $history = $equipment->equipmentHistory()->create([
        'holder_id' => null,
        'change_date' => now(),
        'action' => 'Equipment purchased',
        'action_type' => 'purchased',
        'notes' => 'Purchase record',
        'performed_by_id' => $user->id,
    ]);

    $originalCount = $equipment->equipmentHistory()->count();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('deleteHistory', $history->id);

    // Should still have the same count (not deleted)
    expect($equipment->fresh()->equipmentHistory()->count())->toBe($originalCount);
});

it('validates history edit form', function () {
    $user = Person::factory()->create();
    $equipment = Equipment::factory()->create();

    $history = $equipment->equipmentHistory()->create([
        'owner_id' => null,
        'change_date' => now(),
        'action' => 'Original note',
        'action_type' => 'note',
        'notes' => 'Original note content',
        'performed_by_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('editHistory', $history->id)
        ->set('historyEditForm.action', '')
        ->set('historyEditForm.notes', '')
        ->call('saveHistoryEdit')
        ->assertHasErrors([
            'historyEditForm.action' => 'required',
            'historyEditForm.notes' => 'required',
        ]);
});

it('can edit equipment notes', function () {
    $user = Person::factory()->create();
    $equipment = Equipment::factory()->create();

    $note = $equipment->notes()->create([
        'note_text' => 'Original note content',
        'created_by_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('editNote', $note->id)
        ->set('noteEditForm.note_text', 'Updated note content')
        ->call('saveNoteEdit')
        ->assertHasNoErrors();

    $note->refresh();
    expect($note->note_text)->toBe('Updated note content');
});

it('can delete equipment notes', function () {
    $user = Person::factory()->create();
    $equipment = Equipment::factory()->create();

    $note = $equipment->notes()->create([
        'note_text' => 'Note to delete',
        'created_by_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('deleteNote', $note->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('notes', [
        'id' => $note->id,
    ]);
});

it('does not create duplicate history entries for holder changes', function () {
    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $equipment = Equipment::factory()->create(['current_holder_id' => null]);

    $initialHistoryCount = $equipment->equipmentHistory()->count();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('toggleEditMode')
        ->set('editForm.current_holder_id', $person->id)
        ->call('saveEquipment')
        ->assertHasNoErrors();

    // Should only create ONE history entry for holder change (handled by observer)
    $newHistoryCount = $equipment->fresh()->equipmentHistory()->count();
    expect($newHistoryCount)->toBe($initialHistoryCount + 1);

    // Verify history entry was created correctly
    $this->assertDatabaseHas('equipment_history', [
        'equipment_id' => $equipment->id,
        'holder_id' => $person->id,
        'action_type' => 'assigned',
        'performed_by_id' => $user->id,
    ]);
});
