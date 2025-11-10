<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Q1 Planning Meeting',
                'description' => 'Quarterly planning meeting to discuss goals and roadmap for Q1 2025. All team leads required to attend.',
                'start_date' => '2025-01-15 09:00:00',
                'end_date' => '2025-01-15 12:00:00',
                'location' => 'Conference Room A, Stockholm Office',
                'type' => 'Meeting',
            ],
            [
                'title' => 'Laravel Workshop',
                'description' => 'Hands-on workshop covering advanced Laravel concepts including queues, events, and performance optimization.',
                'start_date' => '2025-01-20 13:00:00',
                'end_date' => '2025-01-20 17:00:00',
                'location' => 'Training Room B, Stockholm Office',
                'type' => 'Training',
            ],
            [
                'title' => 'Senior Developer Interview - Anna Andersson',
                'description' => 'Technical interview for Senior Laravel Developer position. Focus on backend development and system design.',
                'start_date' => '2025-01-22 14:00:00',
                'end_date' => '2025-01-22 16:00:00',
                'location' => 'Meeting Room 3, Stockholm Office',
                'type' => 'Interview',
            ],
            [
                'title' => 'Team Building Event',
                'description' => 'Monthly team building activity. This month we\'ll go bowling and have dinner together.',
                'start_date' => '2025-01-25 17:00:00',
                'end_date' => '2025-01-25 21:00:00',
                'location' => 'Bowl Hall, Stockholm',
                'type' => 'Other',
            ],
            [
                'title' => 'Code Review Session',
                'description' => 'Weekly code review session. This week we\'ll review the new authentication module and payment gateway integration.',
                'start_date' => '2025-01-29 10:00:00',
                'end_date' => '2025-01-29 12:00:00',
                'location' => 'Development Area, Stockholm Office',
                'type' => 'Meeting',
            ],
            [
                'title' => 'DevOps Pipeline Training',
                'description' => 'Training session on CI/CD pipelines, Docker containerization, and Kubernetes deployment strategies.',
                'start_date' => '2025-02-03 09:00:00',
                'end_date' => '2025-02-03 13:00:00',
                'location' => 'Training Room A, Stockholm Office',
                'type' => 'Training',
            ],
            [
                'title' => 'Frontend Developer Interview',
                'description' => 'Interview for Frontend Developer position with focus on React, TypeScript, and modern CSS.',
                'start_date' => '2025-02-05 15:00:00',
                'end_date' => '2025-02-05 17:00:00',
                'location' => 'Meeting Room 2, Stockholm Office',
                'type' => 'Interview',
            ],
            [
                'title' => 'Sprint Planning',
                'description' => 'Sprint planning meeting for the upcoming 2-week sprint. All developers and product managers required.',
                'start_date' => '2025-02-10 09:30:00',
                'end_date' => '2025-02-10 11:30:00',
                'location' => 'Conference Room B, Stockholm Office',
                'type' => 'Meeting',
            ],
            [
                'title' => 'Security Workshop',
                'description' => 'Workshop on web application security, covering OWASP top 10, secure coding practices, and penetration testing basics.',
                'start_date' => '2025-02-14 13:00:00',
                'end_date' => '2025-02-14 17:00:00',
                'location' => 'Training Room C, Stockholm Office',
                'type' => 'Training',
            ],
            [
                'title' => 'Product Demo - Q1 Results',
                'description' => 'Demo of Q1 deliverables to stakeholders and management team. Prepare your presentations.',
                'start_date' => '2025-02-20 14:00:00',
                'end_date' => '2025-02-20 16:30:00',
                'location' => 'Main Conference Room, Stockholm Office',
                'type' => 'Meeting',
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
