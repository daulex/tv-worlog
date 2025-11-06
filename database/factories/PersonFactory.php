<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->randomElement(['Candidate', 'Employee', 'Retired']),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'pers_code' => fake()->unique()->regexify('[A-Z]{3}[0-9]{4}'),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'date_of_birth' => fake()->date(),
            'address' => fake()->address(),
            'starting_date' => fake()->optional()->date(),
            'position' => fake()->jobTitle(),
            'password' => bcrypt('password'),
        ];
    }
}
