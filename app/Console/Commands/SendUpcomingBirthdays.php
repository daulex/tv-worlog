<?php

namespace App\Console\Commands;

use App\Mail\BirthdayReminder;
use App\Models\Person;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendUpcomingBirthdays extends Command
{
    protected $signature = 'birthdays:send-upcoming';

    protected $description = 'Send monthly reminder for the 5 closest upcoming birthdays';

    public function handle(): void
    {
        $birthdays = Person::upcomingBirthdays()->take(5)->toArray();

        if (empty($birthdays)) {
            $this->info('No upcoming birthdays found.');

            return;
        }

        Mail::mailer('lettermint')
            ->to(explode(',', config('lettermint.birthday_recipients')))
            ->send(new BirthdayReminder($birthdays));

        $this->info('Birthday reminder sent to '.config('lettermint.birthday_recipients').'.');
    }
}
