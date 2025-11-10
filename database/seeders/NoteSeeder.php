<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notes = [
            [
                'note_type' => 'person',
                'entity_id' => 1, // Anna Andersson
                'note_text' => 'Anna has excellent Laravel skills and quickly adapted to our codebase. She\'s already contributing to production features.',
            ],
            [
                'note_type' => 'person',
                'entity_id' => 2, // Erik Lundberg
                'note_text' => 'Erik showed strong problem-solving skills during the last sprint. He successfully resolved several critical bugs.',
            ],
            [
                'note_type' => 'person',
                'entity_id' => 3, // Sofia Karlsson
                'note_text' => 'Sofia\'s UI designs for the new dashboard are exceptional. Great attention to detail and user experience.',
            ],
            [
                'note_type' => 'person',
                'entity_id' => 4, // Lars Nilsson
                'note_text' => 'Lars successfully implemented the new CI/CD pipeline. Deployment time reduced by 60%.',
            ],
            [
                'note_type' => 'person',
                'entity_id' => 5, // Maria Johansson
                'note_text' => 'Maria has been mentoring junior developers effectively. Her code reviews are thorough and constructive.',
            ],
            [
                'note_type' => 'equipment',
                'entity_id' => 1, // MacBook Pro
                'note_text' => 'The MacBook Pro assigned to Anna is working perfectly. No issues reported.',
            ],
            [
                'note_type' => 'equipment',
                'entity_id' => 2, // Dell XPS
                'note_text' => 'Dell XPS laptop shows some performance issues. Need to investigate SSD upgrade possibilities.',
            ],
            [
                'note_type' => 'event',
                'entity_id' => 1, // Q1 Planning Meeting
                'note_text' => 'Q1 Planning meeting was very productive. All teams are aligned on quarterly goals.',
            ],
            [
                'note_type' => 'event',
                'entity_id' => 2, // Laravel Workshop
                'note_text' => 'Laravel workshop received excellent feedback. Team members are already applying new techniques.',
            ],
            [
                'note_type' => 'client',
                'entity_id' => 1, // Göteborg Digital
                'note_text' => 'Göteborg Digital client is very satisfied with our work. They want to expand the contract.',
            ],
        ];

        foreach ($notes as $note) {
            Note::create($note);
        }
    }
}
