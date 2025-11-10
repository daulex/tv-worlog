<?php

use App\Models\Person;

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($user = Person::factory()->create());

    $this->get('/dashboard')->assertStatus(200);
});
