<?php

use App\Models\Person;

test('confirm password screen can be rendered', function () {
    $user = Person::factory()->create();

    $response = $this->actingAs($user)->get(route('password.confirm'));

    $response->assertStatus(200);
});
