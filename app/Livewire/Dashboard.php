<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Equipment;
use App\Models\Person;
use App\Models\Vacancy;
use Carbon\Carbon;
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

        // Get all upcoming birthdays (next 365 days)
        $upcomingBirthdays = Person::whereNotNull('date_of_birth')
            ->get()
            ->map(function ($person) {
                $birthday = Carbon::parse($person->date_of_birth);
                $currentYear = now()->year;
                $nextBirthday = $birthday->copy()->year($currentYear);

                if ($nextBirthday->isPast()) {
                    $nextBirthday->addYear();
                }

                $days = floor(now()->diffInDays($nextBirthday, false));

                // Only include if within next 365 days
                if ($days > 365) {
                    return null;
                }

                $age = $nextBirthday->year - $birthday->year;

                return [
                    'name' => $person->full_name,
                    'date_of_birth' => $birthday->format('d.m.Y'),
                    'days' => $days,
                    'age' => $age,
                ];
            })
            ->filter()
            ->sortBy('days')
            ->values();

        return view('livewire.dashboard', compact('stats', 'upcomingBirthdays'))->layout('components.layouts.app');
    }
}
