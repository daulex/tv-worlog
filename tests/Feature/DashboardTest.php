<?php

use App\Models\Person;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($user = Person::factory()->create());

    $this->get('/dashboard')->assertStatus(200);
});
