<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EquipmentHistory>
 */
class EquipmentHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'equipment_id' => Equipment::factory(),
            'owner_id' => Person::factory(),
            'change_date' => fake()->date(),
            'action' => fake()->randomElement(['Assigned', 'Returned', 'Maintenance', 'Retired']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
