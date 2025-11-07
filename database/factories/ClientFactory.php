<?php

namespace Database\Factories;

use Database\Factories\Providers\CustomFakerProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
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

        $companyNames = [
            'Wahsel AB', 'Inselo Group', 'Home Maid Sweden', 'Swedish Tech Solutions',
            'Nordic Software AB', 'Stockholm Digital', 'Gothenburg Tech', 'Malmö Dev House',
            'Uppsala Innovations', 'Västerås IT', 'Örebro Systems', 'Linköping Software',
            'Stockholm Tech Hub', 'Göteborg Digital', 'Malmö Innovation Lab', 'Uppsala Code Factory',
            'Västerås Systems', 'Örebro Tech Solutions', 'Norrköping Software', 'Helsingborg Dev',
            'Jönköping IT', 'Karlstad Digital', 'Sundsvall Tech', 'Växjö Systems',
        ];

        return [
            'name' => isset($this->attributes['name']) ? $this->attributes['name'] : $faker->unique()->randomElement($companyNames),
            'address' => $faker->swedishAddress(),
            'contact_email' => $faker->companyEmail(),
            'contact_phone' => '+46 '.$faker->phoneNumber(),
        ];
    }
}
