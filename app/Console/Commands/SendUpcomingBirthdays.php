<?php

namespace App\Console\Commands;

use App\Mail\BirthdayReminder;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendUpcomingBirthdays extends Command
{
    protected $signature = 'birthdays:send-upcoming';

    protected $description = 'Send birthday reminders for birthdays occurring in 1 or 7 days';

    public function handle(): void
    {
        $birthdays = Person::where('status', 'Employee')
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

                if ($days !== 1 && $days !== 7) {
                    return null;
                }

                $age = $nextBirthday->year - $birthday->year;

                if ($days === 1) {
                    $daysText = 'tomorrow';
                } else {
                    $daysText = 'in 7 days';
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
            ->values()
            ->toArray();

        if (empty($birthdays)) {
            $this->info('No upcoming birthdays in 1 or 7 days.');

            return;
        }

        $recipients = collect(explode(',', config('lettermint.birthday_recipients')))
            ->map(fn ($email) => trim($email))
            ->filter();

        foreach ($recipients as $email) {
            Mail::mailer('lettermint')
                ->to($email)
                ->send(new BirthdayReminder($birthdays));

            $this->info("Sent birthday reminder to {$email}");
        }
    }
}
