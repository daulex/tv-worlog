<?php

use App\Models\Event;

test('event can be created with factory', function () {
    $event = Event::factory()->create([
        'title' => 'Test Event',
        'type' => 'Meeting',
    ]);

    expect($event->title)->toBe('Test Event');
    expect($event->type)->toBe('Meeting');
    expect(Event::where('title', 'Test Event')->exists())->toBeTrue();
});

test('event factory generates valid data', function () {
    $event = Event::factory()->create();

    expect($event->title)->not->toBeEmpty();
    expect($event->type)->toBeIn(['Meeting', 'Interview', 'Training', 'Other']);
    expect($event->start_date)->toBeInstanceOf(\DateTime::class);
    expect($event->end_date)->toBeInstanceOf(\DateTime::class);
    expect($event->end_date)->toBeGreaterThan($event->start_date);
});
