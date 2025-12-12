<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChecklistItem>
 */
class ChecklistItemFactory extends Factory
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
            'type' => fake()->randomElement(['bool', 'text', 'number', 'textarea']),
            'label' => fake()->sentence(2),
            'required' => fake()->boolean(30), // 30% chance of being required
            'order' => 0,
        ];
    }
}
