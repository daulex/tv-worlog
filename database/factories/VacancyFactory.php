<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vacancy>
 */
class VacancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraph(),
            'date_opened' => fake()->date(),
            'date_closed' => fake()->optional(0.3)->date(),
            'budget' => fake()->optional(0.7)->randomFloat(2, 30000, 120000),
            'status' => fake()->randomElement(['Open', 'Closed', 'Paused']),
        ];
    }
}
