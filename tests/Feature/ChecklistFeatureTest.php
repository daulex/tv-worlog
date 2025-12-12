<?php

use App\Models\Checklist;
use App\Models\ChecklistInstance;
use App\Models\ChecklistItem;
use App\Models\ChecklistItemInstance;
use App\Models\Person;
use Livewire\Livewire;

it('can create a checklist', function () {
    $user = Person::factory()->create();

    Livewire::actingAs($user)->test(\App\Livewire\Checklists\Create::class)
        ->set('title', 'Test Checklist')
        ->set('description', 'A test checklist')
        ->set('items', [
            [
                'type' => 'bool',
                'label' => 'Test boolean item',
                'required' => true,
                'order' => 0,
            ],
            [
                'type' => 'text',
                'label' => 'Test text item',
                'required' => false,
                'order' => 1,
            ],
        ])
        ->call('save')
        ->assertRedirect();

    expect(Checklist::where('title', 'Test Checklist')->exists())->toBeTrue();
    $checklist = Checklist::where('title', 'Test Checklist')->first();
    expect($checklist->items)->toHaveCount(2);
});

it('can start a checklist instance for a person', function () {
    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $checklist = Checklist::factory()->create();

    Livewire::actingAs($user)->test(\App\Livewire\People\Show::class, ['person' => $person])
        ->set('selectedChecklistId', $checklist->id)
        ->call('startChecklist')
        ->assertHasNoErrors();

    expect(ChecklistInstance::where('person_id', $person->id)->where('checklist_id', $checklist->id)->exists())->toBeTrue();
});

it('calculates checklist item completion correctly', function () {
    $person = Person::factory()->create();
    $checklist = Checklist::factory()->create();
    $item = ChecklistItem::factory()->create([
        'checklist_id' => $checklist->id,
        'type' => 'bool',
        'required' => true,
    ]);

    $instance = ChecklistInstance::factory()->create([
        'checklist_id' => $checklist->id,
        'person_id' => $person->id,
    ]);

    $itemInstance = ChecklistItemInstance::factory()->create([
        'checklist_instance_id' => $instance->id,
        'checklist_item_id' => $item->id,
        'value' => '1', // Completed
    ]);

    expect($itemInstance->is_completed)->toBeTrue();
    expect($instance->fresh()->progress['completed'])->toBe(1);
    expect($instance->fresh()->progress['total'])->toBe(1);
});

it('completes checklist instance when all items are done', function () {
    $person = Person::factory()->create();
    $checklist = Checklist::factory()->create();

    // Create required item
    $requiredItem = ChecklistItem::factory()->create([
        'checklist_id' => $checklist->id,
        'type' => 'bool',
        'required' => true,
    ]);

    // Create optional item
    $optionalItem = ChecklistItem::factory()->create([
        'checklist_id' => $checklist->id,
        'type' => 'text',
        'required' => false,
    ]);

    $instance = ChecklistInstance::factory()->create([
        'checklist_id' => $checklist->id,
        'person_id' => $person->id,
        'completed_at' => null,
    ]);

    // Complete required item
    $requiredItemInstance = ChecklistItemInstance::factory()->create([
        'checklist_instance_id' => $instance->id,
        'checklist_item_id' => $requiredItem->id,
        'value' => '1',
    ]);

    // Complete optional item
    $optionalItemInstance = ChecklistItemInstance::factory()->create([
        'checklist_instance_id' => $instance->id,
        'checklist_item_id' => $optionalItem->id,
        'value' => 'Some text',
    ]);

    // Manually check completion since observer might not trigger in test
    $instance->checkCompletion();

    expect($instance->fresh()->is_completed)->toBeTrue();
});

it('deletes checklist and cascades to all related data', function () {
    $checklist = Checklist::factory()->create();
    $item = ChecklistItem::factory()->create(['checklist_id' => $checklist->id]);
    $person = Person::factory()->create();
    $instance = ChecklistInstance::factory()->create([
        'checklist_id' => $checklist->id,
        'person_id' => $person->id,
    ]);
    $itemInstance = ChecklistItemInstance::factory()->create([
        'checklist_instance_id' => $instance->id,
        'checklist_item_id' => $item->id,
    ]);

    // Verify all records exist
    expect(Checklist::find($checklist->id))->not->toBeNull();
    expect(ChecklistItem::find($item->id))->not->toBeNull();
    expect(ChecklistInstance::find($instance->id))->not->toBeNull();
    expect(ChecklistItemInstance::find($itemInstance->id))->not->toBeNull();

    // Delete checklist
    $checklist->delete();

    // Verify cascade deletion
    expect(Checklist::find($checklist->id))->toBeNull();
    expect(ChecklistItem::find($item->id))->toBeNull();
    expect(ChecklistInstance::find($instance->id))->toBeNull();
    expect(ChecklistItemInstance::find($itemInstance->id))->toBeNull();
});
