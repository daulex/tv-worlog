<?php

namespace Database\Seeders;

use App\Models\CV;
use Illuminate\Database\Seeder;

class CVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cvs = [
            [
                'person_id' => 1, // Anna Andersson
                'file_path_or_url' => 'cvs/anna_andersson_cv.pdf',
                'uploaded_at' => now(),
            ],
            [
                'person_id' => 2, // Erik Lundberg
                'file_path_or_url' => 'cvs/erik_lundberg_cv.pdf',
                'uploaded_at' => now(),
            ],
            [
                'person_id' => 3, // Sofia Karlsson
                'file_path_or_url' => 'cvs/sofia_karlsson_cv.pdf',
                'uploaded_at' => now(),
            ],
            [
                'person_id' => 4, // Lars Nilsson
                'file_path_or_url' => 'cvs/lars_nilsson_cv.pdf',
                'uploaded_at' => now(),
            ],
            [
                'person_id' => 5, // Maria Johansson
                'file_path_or_url' => 'cvs/maria_johansson_cv.pdf',
                'uploaded_at' => now(),
            ],
            [
                'person_id' => 6, // Johan Pettersson
                'file_path_or_url' => 'cvs/johan_petterson_cv.pdf',
                'uploaded_at' => now(),
            ],
            [
                'person_id' => 7, // Emma Gustafsson
                'file_path_or_url' => 'cvs/emma_gustafsson_cv.pdf',
                'uploaded_at' => now(),
            ],
            [
                'person_id' => 8, // Carl Berg
                'file_path_or_url' => 'cvs/carl_berg_cv.pdf',
                'uploaded_at' => now(),
            ],
            [
                'person_id' => 9, // Lisa Lindström
                'file_path_or_url' => 'cvs/lisa_lindstrom_cv.pdf',
                'uploaded_at' => now(),
            ],
            [
                'person_id' => 10, // Nils Svensson
                'file_path_or_url' => 'cvs/nils_svensson_cv.pdf',
                'uploaded_at' => now(),
            ],
        ];

        foreach ($cvs as $cv) {
            CV::create($cv);
        }
    }
}
