<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChecklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $checklist = \App\Models\Checklist::create([
            'title' => 'Employee Onboarding',
            'description' => 'Complete checklist for new employee onboarding process',
        ]);

        $items = [
            [
                'type' => 'bool',
                'label' => 'Welcome email sent',
                'required' => true,
                'order' => 0,
            ],
            [
                'type' => 'bool',
                'label' => 'Workstation prepared',
                'required' => true,
                'order' => 1,
            ],
            [
                'type' => 'bool',
                'label' => 'Access cards issued',
                'required' => true,
                'order' => 2,
            ],
            [
                'type' => 'text',
                'label' => 'Employee ID number',
                'required' => true,
                'order' => 3,
            ],
            [
                'type' => 'bool',
                'label' => 'HR paperwork completed',
                'required' => true,
                'order' => 4,
            ],
            [
                'type' => 'textarea',
                'label' => 'Notes from orientation meeting',
                'required' => false,
                'order' => 5,
            ],
            [
                'type' => 'bool',
                'label' => 'IT setup completed',
                'required' => true,
                'order' => 6,
            ],
            [
                'type' => 'bool',
                'label' => 'Manager introduction completed',
                'required' => false,
                'order' => 7,
            ],
        ];

        foreach ($items as $itemData) {
            $checklist->items()->create($itemData);
        }
    }
}
