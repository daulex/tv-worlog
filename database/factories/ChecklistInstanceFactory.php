<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChecklistInstance>
 */
class ChecklistInstanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'checklist_id' => \App\Models\Checklist::factory(),
            'person_id' => \App\Models\Person::factory(),
            'started_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'completed_at' => fake()->optional(0.3)->dateTimeBetween('-1 month', 'now'), // 30% completed
        ];
    }
}
