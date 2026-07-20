<?php

namespace App\Livewire;

use App\Mail\BirthdayReminder;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\Person;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Dashboard extends Component
{
    public function getUpcomingBirthdays(): \Illuminate\Support\Collection
    {
        return Person::upcomingBirthdays();
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
