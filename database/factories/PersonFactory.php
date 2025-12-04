<?php

namespace Database\Factories;

use Database\Factories\Providers\CustomFakerProvider;
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
        $faker = $this->withFaker();
        $faker->addProvider(new CustomFakerProvider($faker));

        return [
            'status' => $faker->randomElement(['Candidate', 'Employee', 'Retired']),
            'first_name' => $faker->randomElement([
                'Jānis', 'Andris', 'Mārtiņš', 'Guntis', 'Aigars', 'Juris',
                'Viktors', 'Edgars', 'Raimonds', 'Oskars', 'Kristaps',
                'Mikhail', 'Alexei', 'Dmitry', 'Sergey', 'Vladimir', 'Igor',
                'Anna', 'Līga', 'Inga', 'Maija', 'Zanda', 'Laura',
            ]),
            'last_name' => $faker->randomElement([
                'Bērziņš', 'Kalniņš', 'Ozoliņš', 'Jansons', 'Liepiņš', 'Krūmiņš',
                'Petrov', 'Ivanov', 'Sidorov', 'Kuznetsov', 'Popov', 'Volkov',
                'Ozola', 'Kalniņa', 'Bērziņa', 'Liepiņa', 'Krūmiņa',
            ]),
            'pers_code' => $faker->unique()->regexify('[0-9]{6}-[0-9]{5}'),
            'phone' => '+371'.$faker->numerify('2#######'),
            'phone2' => $faker->optional(0.3)->numerify('2#######') ? '+371'.$faker->numerify('2#######') : null,
            'email' => $faker->unique()->safeEmail(),
            'email2' => $faker->optional(0.3)->safeEmail(),
            'date_of_birth' => $faker->dateTimeBetween('-50 years', '-22 years'),
            'address' => $faker->latvianAddress(),
            'starting_date' => $faker->optional()->dateTimeBetween('-2 years', 'now'),
            'last_working_date' => $faker->optional()->dateTimeBetween('-1 year', 'now'),
            'position' => $faker->randomElement([
                '.NET Developer', 'Node.js Developer', 'Data Engineer',
                'Laravel Developer', 'React Developer', 'Python Developer',
                'DevOps Engineer', 'Frontend Developer', 'Backend Developer',
            ]),
            'password' => bcrypt('password'),
        ];
    }

    /**
     * Indicate that the user's email address is not verified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user should not have two-factor authentication enabled.
     */
    public function withoutTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);
    }
}
