<?php

namespace Database\Seeders;

use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vacancies = [
            [
                'title' => 'Senior Laravel Developer',
                'description' => 'We are looking for an experienced Laravel developer to join our team. You will work on modern web applications using the latest Laravel technologies.',
                'date_opened' => '2025-01-15',
                'budget' => 65000.00,
                'status' => 'Open',
                'client_id' => 1, // Göteborg Digital
            ],
            [
                'title' => 'Full Stack JavaScript Developer',
                'description' => 'Join our frontend team to build amazing user experiences. Experience with React, Vue, or Angular required.',
                'date_opened' => '2025-01-20',
                'budget' => 58000.00,
                'status' => 'Open',
                'client_id' => 2, // Stockholm Tech Hub
            ],
            [
                'title' => 'DevOps Engineer',
                'description' => 'We need a DevOps engineer to help us build and maintain our cloud infrastructure. AWS/Docker experience essential.',
                'date_opened' => '2025-01-10',
                'budget' => 72000.00,
                'status' => 'Open',
                'client_id' => 3, // Malmö Dev House
            ],
            [
                'title' => 'UI/UX Designer',
                'description' => 'Creative designer needed to help us build beautiful and intuitive user interfaces. Portfolio required.',
                'date_opened' => '2025-01-25',
                'budget' => 52000.00,
                'status' => 'Open',
                'client_id' => 4, // Uppsala Code Factory
            ],
            [
                'title' => 'Backend Python Developer',
                'description' => 'Python developer needed for our data processing team. Experience with Django and data libraries preferred.',
                'date_opened' => '2025-01-18',
                'budget' => 61000.00,
                'status' => 'Open',
                'client_id' => 5, // Nordic Software AB
            ],
            [
                'title' => 'Mobile App Developer',
                'description' => 'iOS/Android developer to help us build cross-platform mobile applications. React Native experience preferred.',
                'date_opened' => '2025-01-22',
                'budget' => 59000.00,
                'status' => 'Open',
                'client_id' => 1, // Göteborg Digital
            ],
            [
                'title' => 'QA Engineer',
                'description' => 'Quality assurance engineer to ensure our products meet the highest standards. Automation experience required.',
                'date_opened' => '2025-01-12',
                'budget' => 48000.00,
                'status' => 'Open',
                'client_id' => 2, // Stockholm Tech Hub
            ],
            [
                'title' => 'Product Manager',
                'description' => 'Experienced product manager to lead our product development team. Agile experience required.',
                'date_opened' => '2025-01-08',
                'budget' => 75000.00,
                'status' => 'Open',
                'client_id' => 3, // Malmö Dev House
            ],
            [
                'title' => 'Data Scientist',
                'description' => 'Data scientist to help us analyze and interpret complex data sets. Machine learning experience preferred.',
                'date_opened' => '2025-01-28',
                'budget' => 68000.00,
                'status' => 'Open',
                'client_id' => 4, // Uppsala Code Factory
            ],
            [
                'title' => 'Cloud Architect',
                'description' => 'Senior cloud architect to design and implement scalable cloud solutions. Multi-cloud experience required.',
                'date_opened' => '2025-01-05',
                'budget' => 85000.00,
                'status' => 'Open',
                'client_id' => 5, // Nordic Software AB
            ],
        ];

        foreach ($vacancies as $vacancy) {
            Vacancy::firstOrCreate(
                ['title' => $vacancy['title'], 'client_id' => $vacancy['client_id']],
                $vacancy
            );
        }
    }
}
