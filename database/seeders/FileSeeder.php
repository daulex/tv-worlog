<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Person;
use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        // Get existing people or create some if none exist
        $people = Person::all();

        if ($people->isEmpty()) {
            $this->command->warn('No people found. Please run PersonSeeder first.');

            return;
        }

        // Create sample files for each person
        foreach ($people as $person) {
            // Each person gets 1-3 CVs
            $cvCount = fake()->numberBetween(1, 3);
            for ($i = 0; $i < $cvCount; $i++) {
                File::factory()->cv()->create([
                    'person_id' => $person->id,
                    'filename' => $person->first_name.'_'.$person->last_name.'_CV_'.($i + 1).'.pdf',
                    'description' => 'CV document '.($i + 1).' for '.$person->name,
                ]);
            }

            // Some people get contracts (70% chance)
            if (fake()->boolean(70)) {
                File::factory()->contract()->create([
                    'person_id' => $person->id,
                    'filename' => 'Contract_'.$person->first_name.'_'.$person->last_name.'.pdf',
                    'description' => 'Employment contract for '.$person->name,
                ]);
            }

            // Some people get certificates (40% chance)
            if (fake()->boolean(40)) {
                $certCount = fake()->numberBetween(1, 2);
                for ($i = 0; $i < $certCount; $i++) {
                    File::factory()->certificate()->create([
                        'person_id' => $person->id,
                        'description' => 'Professional certificate '.($i + 1).' for '.$person->name,
                    ]);
                }
            }

            // Some people get other files (30% chance)
            if (fake()->boolean(30)) {
                $otherCount = fake()->numberBetween(1, 2);
                for ($i = 0; $i < $otherCount; $i++) {
                    File::factory()->create([
                        'person_id' => $person->id,
                        'description' => 'Additional document '.($i + 1).' for '.$person->name,
                    ]);
                }
            }
        }

        $this->command->info('Files seeded successfully!');
    }
}
