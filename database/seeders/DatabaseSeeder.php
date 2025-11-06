<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Equipment;
use App\Models\Event;
use App\Models\Person;
use App\Models\Vacancy;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
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

        // Create sample data
        Client::factory(5)->create();
        Vacancy::factory(10)->create();
        Equipment::factory(15)->create();
        Event::factory(8)->create();
        Person::factory(20)->create();
    }
}
