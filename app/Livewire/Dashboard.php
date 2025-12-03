<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Equipment;
use App\Models\Person;
use App\Models\Vacancy;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'candidates' => Person::where('status', 'Candidate')->count(),
            'employees' => Person::where('status', 'Employee')->count(),
            'retired' => Person::where('status', 'Retired')->count(),
            'clients' => Client::count(),
            'vacancies' => Vacancy::count(),
            'active_equipment' => Equipment::whereNull('retired_at')->count(),
        ];

        return view('livewire.dashboard', compact('stats'));
    }
}
