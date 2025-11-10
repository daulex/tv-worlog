<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ClientSeeder::class,
            VacancySeeder::class,
            PersonSeeder::class,
            EquipmentSeeder::class,
            EventSeeder::class,
            CVSeeder::class,
            NoteSeeder::class,
        ]);

        // Create test user for authentication
        Person::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'first_name' => 'Test',
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'status' => 'Employee',
                'pers_code' => '161175-19997', // Valid Latvian personal code
                'date_of_birth' => '1990-01-01',
                'email_verified_at' => now(),
            ]
        );
    }
}
