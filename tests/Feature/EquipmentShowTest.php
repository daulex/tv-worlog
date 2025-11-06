<?php

use App\Models\Equipment;
use App\Models\Person;
use Livewire\Livewire;

it('displays equipment details correctly', function () {
    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $equipment = Equipment::factory()->create([
        'current_owner_id' => $person->id,
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
        ->assertSee('Equipment History');
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

    $this->assertDatabaseHas('equipment_history', [
        'equipment_id' => $equipment->id,
        'action_type' => 'note',
        'notes' => 'Test note for equipment',
        'performed_by_id' => $user->id,
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
        ->set('editForm.current_owner_id', $person->id)
        ->call('saveEquipment')
        ->assertHasNoErrors();

    $equipment->refresh();
    expect($equipment->brand)->toBe('Updated Brand');
    expect($equipment->model)->toBe('Updated Model');
    expect($equipment->current_owner_id)->toBe($person->id);
});

it('creates history record when equipment owner changes', function () {
    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $equipment = Equipment::factory()->create(['current_owner_id' => null]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Equipment\Show::class, ['equipment' => $equipment])
        ->call('toggleEditMode')
        ->set('editForm.current_owner_id', $person->id)
        ->call('saveEquipment')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('equipment_history', [
        'equipment_id' => $equipment->id,
        'owner_id' => $person->id,
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
