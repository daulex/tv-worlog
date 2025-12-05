<?php

use App\Models\Equipment;
use App\Models\EquipmentHistory;
use App\Models\Person;
use Livewire\Livewire;

it('allows deletion of equipment and cascades to history records', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    $equipment = Equipment::factory()->create();

    // Verify initial history record was created by observer
    expect($equipment->equipmentHistory()->count())->toBe(1);

    // Create additional history record
    $equipment->equipmentHistory()->create([
        'holder_id' => null,
        'change_date' => now(),
        'action' => 'Additional action',
        'action_type' => 'note',
        'performed_by_id' => $user->id,
    ]);

    // Verify we have 2 history records
    expect($equipment->equipmentHistory()->count())->toBe(2);

    $component = Livewire::test('equipment.edit', ['equipment' => $equipment]);

    $component->call('delete');

    $component->assertHasNoErrors();

    // Equipment should be deleted
    expect(Equipment::find($equipment->id))->toBeNull();

    // History records should also be deleted due to CASCADE
    expect(EquipmentHistory::where('equipment_id', $equipment->id)->count())->toBe(0);
});
