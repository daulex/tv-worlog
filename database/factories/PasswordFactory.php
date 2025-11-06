<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Password>
 */
class PasswordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => Person::factory(),
            'password' => fake()->password(),
            'description' => fake()->sentence(),
            'created_at' => fake()->dateTime(),
        ];
    }
}
