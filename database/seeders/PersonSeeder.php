<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $people = [
            [
                'first_name' => 'Anna',
                'last_name' => 'Andersson',
                'email' => 'anna.andersson@example.com',
                'pers_code' => 'ANN001',
                'phone' => '+46 70 123 45 67',
                'date_of_birth' => '1990-03-15',
                'address' => 'Drottninggatan 10, 111 20 Stockholm, Sweden',
                'position' => 'Senior Laravel Developer',
                'status' => 'Employee',
                'starting_date' => '2023-01-15',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'linkedin_profile' => 'https://linkedin.com/in/anna-andersson',
                'github_profile' => 'https://github.com/annaandersson',
                'portfolio_url' => 'https://annaandersson.dev',
                'emergency_contact_name' => 'Erik Andersson',
                'emergency_contact_relationship' => 'Spouse',
                'emergency_contact_phone' => '+46 70 987 65 43',
                'client_id' => 1, // Göteborg Digital
                'vacancy_id' => 1, // Senior Laravel Developer
            ],
            [
                'first_name' => 'Erik',
                'last_name' => 'Lundberg',
                'email' => 'erik.lundberg@example.com',
                'pers_code' => 'ERI002',
                'phone' => '+46 73 234 56 78',
                'date_of_birth' => '1988-07-22',
                'address' => 'Vasagatan 5, 113 27 Stockholm, Sweden',
                'position' => 'Full Stack Developer',
                'status' => 'Employee',
                'starting_date' => '2022-06-01',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'email2' => 'erik.lundberg.personal@example.com',
                'phone2' => '+46 76 345 67 89',
                'emergency_contact_name' => 'Maria Lundberg',
                'emergency_contact_relationship' => 'Sister',
                'emergency_contact_phone' => '+46 70 456 78 90',
                'client_id' => 2, // Stockholm Tech Hub
                'vacancy_id' => 2, // Full Stack JavaScript Developer
            ],
            [
                'first_name' => 'Sofia',
                'last_name' => 'Karlsson',
                'email' => 'sofia.karlsson@example.com',
                'pers_code' => 'SOF003',
                'phone' => '+46 72 345 67 89',
                'date_of_birth' => '1992-11-08',
                'address' => 'Götgatan 15, 118 26 Stockholm, Sweden',
                'position' => 'UI/UX Designer',
                'status' => 'Employee',
                'starting_date' => '2023-03-01',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'linkedin_profile' => 'https://linkedin.com/in/sofia-karlsson',
                'portfolio_url' => 'https://sofiakarlsson.design',
                'emergency_contact_name' => 'Lars Karlsson',
                'emergency_contact_relationship' => 'Father',
                'emergency_contact_phone' => '+46 70 234 56 78',
                'client_id' => 4, // Uppsala Code Factory
                'vacancy_id' => 4, // UI/UX Designer
            ],
            [
                'first_name' => 'Lars',
                'last_name' => 'Nilsson',
                'email' => 'lars.nilsson@example.com',
                'pers_code' => 'LAR004',
                'phone' => '+46 76 456 78 90',
                'date_of_birth' => '1985-05-12',
                'address' => 'Sveavägen 25, 113 59 Stockholm, Sweden',
                'position' => 'DevOps Engineer',
                'status' => 'Employee',
                'starting_date' => '2021-09-15',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'github_profile' => 'https://github.com/larsnilsson',
                'email2' => 'lars.nilsson.dev@example.com',
                'emergency_contact_name' => 'Anna Nilsson',
                'emergency_contact_relationship' => 'Partner',
                'emergency_contact_phone' => '+46 70 345 67 89',
                'client_id' => 3, // Malmö Dev House
                'vacancy_id' => 3, // DevOps Engineer
            ],
            [
                'first_name' => 'Maria',
                'last_name' => 'Johansson',
                'email' => 'maria.johansson@example.com',
                'pers_code' => 'MAR005',
                'phone' => '+46 70 567 89 01',
                'date_of_birth' => '1991-09-30',
                'address' => 'Kungsholmen 8, 112 40 Stockholm, Sweden',
                'position' => 'Backend Developer',
                'status' => 'Employee',
                'starting_date' => '2022-11-01',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'linkedin_profile' => 'https://linkedin.com/in/maria-johansson',
                'github_profile' => 'https://github.com/mariajohansson',
                'emergency_contact_name' => 'Johan Johansson',
                'emergency_contact_relationship' => 'Brother',
                'emergency_contact_phone' => '+46 73 456 78 90',
                'client_id' => 5, // Nordic Software AB
                'vacancy_id' => 5, // Backend Python Developer
            ],
            [
                'first_name' => 'Johan',
                'last_name' => 'Pettersson',
                'email' => 'johan.petterson@example.com',
                'pers_code' => 'JOH006',
                'phone' => '+46 73 678 90 12',
                'date_of_birth' => '1987-02-18',
                'address' => 'Östermalmstorg 1, 114 44 Stockholm, Sweden',
                'position' => 'Mobile App Developer',
                'status' => 'Employee',
                'starting_date' => '2023-05-01',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'linkedin_profile' => 'https://linkedin.com/in/johan-petterson',
                'portfolio_url' => 'https://johanpetterson.app',
                'phone2' => '+46 76 234 56 78',
                'emergency_contact_name' => 'Sofia Pettersson',
                'emergency_contact_relationship' => 'Wife',
                'emergency_contact_phone' => '+46 70 123 45 67',
                'client_id' => 1, // Göteborg Digital
                'vacancy_id' => 6, // Mobile App Developer
            ],
            [
                'first_name' => 'Emma',
                'last_name' => 'Gustafsson',
                'email' => 'emma.gustafsson@example.com',
                'pers_code' => 'EMM007',
                'phone' => '+46 72 789 01 23',
                'date_of_birth' => '1993-06-25',
                'address' => 'Norrmalm 12, 111 40 Stockholm, Sweden',
                'position' => 'QA Engineer',
                'status' => 'Employee',
                'starting_date' => '2023-07-01',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'linkedin_profile' => 'https://linkedin.com/in/emma-gustafsson',
                'email2' => 'emma.gustafsson.personal@example.com',
                'emergency_contact_name' => 'Carl Gustafsson',
                'emergency_contact_relationship' => 'Father',
                'emergency_contact_phone' => '+46 70 567 89 01',
                'client_id' => 2, // Stockholm Tech Hub
                'vacancy_id' => 7, // QA Engineer
            ],
            [
                'first_name' => 'Carl',
                'last_name' => 'Berg',
                'email' => 'carl.berg@example.com',
                'pers_code' => 'CAR008',
                'phone' => '+46 76 890 12 34',
                'date_of_birth' => '1989-12-10',
                'address' => 'Södermalm 20, 118 46 Stockholm, Sweden',
                'position' => 'Product Manager',
                'status' => 'Employee',
                'starting_date' => '2022-02-15',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'linkedin_profile' => 'https://linkedin.com/in/carl-berg',
                'github_profile' => 'https://github.com/carlberg',
                'portfolio_url' => 'https://carlberg.io',
                'emergency_contact_name' => 'Lisa Berg',
                'emergency_contact_relationship' => 'Partner',
                'emergency_contact_phone' => '+46 73 678 90 12',
                'client_id' => 3, // Malmö Dev House
                'vacancy_id' => 8, // Product Manager
            ],
            [
                'first_name' => 'Lisa',
                'last_name' => 'Lindström',
                'email' => 'lisa.lindstrom@example.com',
                'pers_code' => 'LIS009',
                'phone' => '+46 70 901 23 45',
                'date_of_birth' => '1994-04-15',
                'address' => 'Kungsholmen 15, 112 35 Stockholm, Sweden',
                'position' => 'Data Scientist',
                'status' => 'Employee',
                'starting_date' => '2023-09-01',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'linkedin_profile' => 'https://linkedin.com/in/lisa-lindstrom',
                'github_profile' => 'https://github.com/lisalindstrom',
                'email2' => 'lisa.lindstrom.research@example.com',
                'phone2' => '+46 76 789 01 23',
                'emergency_contact_name' => 'Nils Lindström',
                'emergency_contact_relationship' => 'Father',
                'emergency_contact_phone' => '+46 70 234 56 78',
                'client_id' => 4, // Uppsala Code Factory
                'vacancy_id' => 9, // Data Scientist
            ],
            [
                'first_name' => 'Nils',
                'last_name' => 'Svensson',
                'email' => 'nils.svensson@example.com',
                'pers_code' => 'NIL010',
                'phone' => '+46 73 012 34 56',
                'date_of_birth' => '1986-08-22',
                'address' => 'Vasastan 8, 113 28 Stockholm, Sweden',
                'position' => 'Cloud Architect',
                'status' => 'Employee',
                'starting_date' => '2021-03-01',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'linkedin_profile' => 'https://linkedin.com/in/nils-svensson',
                'github_profile' => 'https://github.com/nilssvensson',
                'portfolio_url' => 'https://nilssvensson.tech',
                'emergency_contact_name' => 'Astrid Svensson',
                'emergency_contact_relationship' => 'Mother',
                'emergency_contact_phone' => '+46 70 890 12 34',
                'client_id' => 5, // Nordic Software AB
                'vacancy_id' => 10, // Cloud Architect
            ],
        ];

        foreach ($people as $person) {
            Person::create($person);
        }
    }
}
