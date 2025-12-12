<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChecklistItemInstance>
 */
class ChecklistItemInstanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $item = \App\Models\ChecklistItem::factory()->create();
        $instance = \App\Models\ChecklistInstance::factory()->create();

        return [
            'checklist_instance_id' => $instance->id,
            'checklist_item_id' => $item->id,
            'value' => $this->generateValueForType($item->type),
            'completed_at' => fake()->optional(0.5)->dateTimeBetween('-1 month', 'now'), // 50% completed
        ];
    }

    private function generateValueForType(string $type): ?string
    {
        return match ($type) {
            'bool' => fake()->randomElement(['0', '1']),
            'text' => fake()->optional(0.7)->word(), // 70% chance of having a value
            'number' => fake()->optional(0.7)->numberBetween(1, 100),
            'textarea' => fake()->optional(0.7)->paragraph(),
            default => null,
        };
    }
}
