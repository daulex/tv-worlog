<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Equipment;
use App\Models\Event;
use App\Models\Person;
use App\Models\Vacancy;
use Database\Factories\Providers\CustomFakerProvider;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Initialize custom faker provider
        $faker = fake();
        $faker->addProvider(new CustomFakerProvider($faker));

        // Create test user for authentication
        Person::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@example.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'status' => 'Employee',
                'pers_code' => 'TEST001',
                'date_of_birth' => '1990-01-01',
            ]
        );

        // Create sample data with unique clients
        $clientNames = [
            'Wahsel AB', 'Inselo Group', 'Home Maid Sweden', 'Swedish Tech Solutions',
            'Nordic Software AB', 'Stockholm Digital', 'Gothenburg Tech', 'Malmö Dev House',
            'Uppsala Innovations', 'Västerås IT', 'Örebro Systems', 'Linköping Software',
            'Stockholm Tech Hub', 'Göteborg Digital', 'Malmö Innovation Lab', 'Uppsala Code Factory',
            'Västerås Systems', 'Örebro Tech Solutions', 'Norrköping Software', 'Helsingborg Dev',
            'Jönköping IT', 'Karlstad Digital', 'Sundsvall Tech', 'Växjö Systems',
        ];

        // Shuffle and take 5 unique names
        shuffle($clientNames);
        $selectedNames = array_slice($clientNames, 0, 5);

        // Create clients directly without factory to avoid duplicates
        $createdClients = [];
        foreach ($selectedNames as $name) {
            $client = Client::create([
                'name' => $name,
                'address' => $faker->swedishAddress(),
                'contact_email' => $faker->companyEmail(),
                'contact_phone' => $this->generateSwedishPhoneNumber($faker),
            ]);
            $createdClients[] = $client->id;
        }

        // Create vacancies using existing clients with distribution
        for ($i = 0; $i < 10; $i++) {
            Vacancy::factory()->create([
                'client_id' => fake()->randomElement($createdClients),
            ]);
        }

        Equipment::factory(15)->create();
        Event::factory(8)->create();
        Person::factory(20)->create();
    }

    /**
     * Generate a proper Swedish phone number
     */
    private function generateSwedishPhoneNumber($faker): string
    {
        // Swedish phone formats:
        // Mobile: +46 70 123 45 67, +46 73 987 65 43
        // Landline: +46 8 123 45 67 (Stockholm), +46 911 123 45 (other areas)
        
        $mobilePrefixes = ['70', '72', '73', '76', '79'];
        $areaCodes = ['8', '911', '912', '913', '914', '915', '916', '917', '918', '919'];
        
        if (rand(0, 1)) {
            // Mobile number
            $prefix = $faker->randomElement($mobilePrefixes);
            $number = $faker->numerify('### ## ##');
            return "+46 {$prefix} {$number}";
        } else {
            // Landline number
            $areaCode = $faker->randomElement($areaCodes);
            if ($areaCode === '8') {
                // Stockholm format
                $number = $faker->numerify('### ## ##');
                return "+46 {$areaCode} {$number}";
            } else {
                // Other areas format
                $number = $faker->numerify('### ##');
                return "+46 {$areaCode} {$number}";
            }
        }
    }
}
