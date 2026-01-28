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

                // Use startOfDay to ensure accurate day calculation regardless of time
                $days = now()->startOfDay()->diffInDays($nextBirthday->startOfDay(), false);

                // Only include if within next 365 days
                if ($days > 365) {
                    return null;
                }

                $age = $nextBirthday->year - $birthday->year;

                // Determine display text for days
                if ($days === 0) {
                    $daysText = 'today';
                } elseif ($days === 1) {
                    $daysText = 'tomorrow';
                } else {
                    $daysText = "in {$days} day".($days === 1 ? '' : 's');
                }

                return [
                    'id' => $person->id,
                    'name' => $person->full_name,
                    'date_of_birth' => $birthday->format('d.m.Y'),
                    'days' => $days,
                    'age' => $age,
                    'days_text' => $daysText,
                ];
            })
            ->filter()
            ->sortBy('days')
            ->values();

        return view('livewire.dashboard', compact('stats', 'upcomingBirthdays'))->layout('components.layouts.app');
    }
}
