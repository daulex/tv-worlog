<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand' => fake()->company(),
            'model' => fake()->word(),
            'serial' => fake()->unique()->regexify('[A-Z0-9]{10}'),
            'purchase_date' => fake()->date(),
            'purchase_price' => fake()->randomFloat(2, 100, 5000),
            'current_owner_id' => Person::factory(),
        ];
    }
}
