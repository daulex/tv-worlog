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
        $devices = [
            ['brand' => 'Apple', 'model' => 'MacBook Pro 14"', 'price_range' => [1800, 2800]],
            ['brand' => 'Apple', 'model' => 'MacBook Air M2', 'price_range' => [1200, 1800]],
            ['brand' => 'Apple', 'model' => 'iPhone 15 Pro', 'price_range' => [900, 1200]],
            ['brand' => 'Apple', 'model' => 'iPhone 15', 'price_range' => [700, 900]],
            ['brand' => 'Lenovo', 'model' => 'ThinkPad X1 Carbon', 'price_range' => [1500, 2500]],
            ['brand' => 'Lenovo', 'model' => 'ThinkPad T14', 'price_range' => [1000, 1800]],
            ['brand' => 'Lenovo', 'model' => 'ThinkPad P1', 'price_range' => [1800, 3000]],
            ['brand' => 'Samsung', 'model' => 'Galaxy S24 Ultra', 'price_range' => [1000, 1400]],
            ['brand' => 'Samsung', 'model' => 'Galaxy Tab S9', 'price_range' => [600, 900]],
        ];

        $device = fake()->randomElement($devices);

        return [
            'brand' => $device['brand'],
            'model' => $device['model'],
            'serial' => fake()->unique()->regexify('[A-Z0-9]{8,12}'),
            'purchase_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'purchase_price' => fake()->randomFloat(2, $device['price_range'][0], $device['price_range'][1]),
            'current_owner_id' => Person::factory(),
        ];
    }
}
