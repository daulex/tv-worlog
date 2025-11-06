<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 week', '+1 month');
        $endDate = (clone $startDate)->modify('+'.fake()->numberBetween(1, 8).' hours');

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => fake()->optional()->city(),
            'type' => fake()->randomElement(['Meeting', 'Interview', 'Training', 'Other']),
        ];
    }
}
