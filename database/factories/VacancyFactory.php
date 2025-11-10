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
            'title' => fake()->randomElement([
                '.NET Full Stack Developer', 'Node.js Developer', 'Data Engineer',
                'Laravel Developer', 'React Developer', 'Python Developer',
                'DevOps Engineer', 'Frontend Developer', 'Backend Developer',
                'Full Stack JavaScript Developer', 'Senior .NET Developer',
            ]),
            'description' => fake()->paragraph(3),
            'date_opened' => fake()->dateTimeBetween('-6 months', 'now'),
            'date_closed' => fake()->optional(0.3)->dateTimeBetween('-3 months', 'now'),
            'budget' => fake()->optional(0.8)->randomFloat(0, 3000, 6000),
            'status' => fake()->randomElement(['Open', 'Closed', 'On Hold']),
        ];
    }
}
