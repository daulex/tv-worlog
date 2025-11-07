<?php

use App\Models\Person;

it('displays timestamps in correct timezone', function () {
    $person = Person::factory()->create();

    // Create a note
    $note = $person->notes()->create([
        'note_type' => 'person',
        'entity_id' => $person->id,
        'note_text' => 'Test timezone note',
    ]);

    // Test that the timezone is correctly applied
    $formattedTime = $note->created_at->setTimezone(config('app.timezone'))->format('M d, Y H:i');

    expect($formattedTime)->toBeString();
    expect(config('app.timezone'))->toBe('Europe/Riga');
});

it('uses latvian locale for faker data', function () {
    $person = Person::factory()->create();

    expect($person->first_name)->toBeString();
    expect($person->last_name)->toBeString();
    expect($person->phone)->toBeString();
    expect($person->address)->toBeString();

    // Verify personal code format (Latvian style: 6 digits - 5 digits)
    expect($person->pers_code)->toMatch('/^\d{6}-\d{5}$/');
});

it('displays currency in euros', function () {
    $equipment = \App\Models\Equipment::factory()->create(['purchase_price' => 1000]);

    // Test that the equipment Livewire component shows EUR symbol
    Livewire::test(\App\Livewire\Equipment\Index::class)
        ->assertSee('€'.number_format($equipment->purchase_price, 2))
        ->assertDontSee('$'.number_format($equipment->purchase_price, 2));
});
