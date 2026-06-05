<?php

namespace App\Livewire;

use App\Mail\BirthdayReminder;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\Person;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Dashboard extends Component
{
    public function getUpcomingBirthdays(): \Illuminate\Support\Collection
    {
        return Person::where('status', 'Employee')
            ->whereNotNull('date_of_birth')
            ->get()
            ->map(function ($person) {
                $birthday = Carbon::parse($person->date_of_birth);
                $currentYear = now()->year;
                $nextBirthday = $birthday->copy()->year($currentYear);

                if ($nextBirthday->isPast()) {
                    $nextBirthday->addYear();
                }

                $days = now()->startOfDay()->diffInDays($nextBirthday->startOfDay(), false);

                if ($days > 365) {
                    return null;
                }

                $age = $nextBirthday->year - $birthday->year;

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
    }

    public function sendBirthdayEmails(): void
    {
        $birthdays = $this->getUpcomingBirthdays()->take(5)->toArray();

        if (empty($birthdays)) {
            session()->flash('message', 'No upcoming birthdays to send.');

            return;
        }

        Mail::mailer('lettermint')
            ->to(explode(',', config('lettermint.birthday_recipients')))
            ->send(new BirthdayReminder($birthdays));

        session()->flash('message', 'Birthday reminder sent to '.config('lettermint.birthday_recipients').'.');
    }

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

        $upcomingBirthdays = $this->getUpcomingBirthdays();

        return view('livewire.dashboard', compact('stats', 'upcomingBirthdays'))->layout('components.layouts.app');
    }
}
