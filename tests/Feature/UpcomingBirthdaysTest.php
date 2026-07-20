<?php

use App\Mail\BirthdayReminder;
use App\Models\Person;
use Illuminate\Support\Facades\Mail;

test('upcoming birthdays returns employees sorted by nearest', function () {
    $this->travelTo(now()->setDate(2026, 7, 15));

    $nearest = Person::factory()->create([
        'status' => 'Employee',
        'date_of_birth' => '2000-07-20',
    ]);

    $farther = Person::factory()->create([
        'status' => 'Employee',
        'date_of_birth' => '1995-08-15',
    ]);

    $birthdays = Person::upcomingBirthdays();

    expect($birthdays)->toHaveCount(2)
        ->and($birthdays->first()['id'])->toBe($nearest->id)
        ->and($birthdays->last()['id'])->toBe($farther->id);
});

test('upcoming birthdays excludes non employees', function () {
    $this->travelTo(now()->setDate(2026, 7, 15));

    Person::factory()->create([
        'status' => 'Candidate',
        'date_of_birth' => '2000-07-18',
    ]);

    Person::factory()->create([
        'status' => 'Retired',
        'date_of_birth' => '1960-07-25',
    ]);

    Person::factory()->create([
        'status' => 'Employee',
        'date_of_birth' => '1990-08-01',
    ]);

    $birthdays = Person::upcomingBirthdays();

    expect($birthdays)->toHaveCount(1);
});

test('upcoming birthdays shows today text for same day', function () {
    $this->travelTo(now()->setDate(2026, 7, 15));

    Person::factory()->create([
        'status' => 'Employee',
        'date_of_birth' => '2000-07-15',
    ]);

    $birthday = Person::upcomingBirthdays()->first();

    expect($birthday['days'])->toBe(0)
        ->and($birthday['days_text'])->toBe('today');
});

test('upcoming birthdays shows tomorrow text', function () {
    $this->travelTo(now()->setDate(2026, 7, 15));

    Person::factory()->create([
        'status' => 'Employee',
        'date_of_birth' => '2000-07-16',
    ]);

    $birthday = Person::upcomingBirthdays()->first();

    expect($birthday['days'])->toBe(1)
        ->and($birthday['days_text'])->toBe('tomorrow');
});

test('upcoming birthdays shows correct days text', function () {
    $this->travelTo(now()->setDate(2026, 7, 15));

    Person::factory()->create([
        'status' => 'Employee',
        'date_of_birth' => '2000-07-25',
    ]);

    $birthday = Person::upcomingBirthdays()->first();

    expect($birthday['days'])->toBe(10)
        ->and($birthday['days_text'])->toBe('in 10 days');
});

test('command sends email with closest 5 birthdays', function () {
    Mail::fake();
    $this->travelTo(now()->setDate(2026, 7, 15));

    for ($i = 1; $i <= 7; $i++) {
        Person::factory()->create([
            'status' => 'Employee',
            'date_of_birth' => "2000-07-1{$i}",
        ]);
    }

    $this->artisan('birthdays:send-upcoming')->assertExitCode(0);

    Mail::assertSent(BirthdayReminder::class, function (BirthdayReminder $mail) {
        return $mail->hasTo('kirillgalenko@gmail.com')
            && count($mail->birthdays) === 5;
    });
});

test('command does not send email when no employees exist', function () {
    Mail::fake();
    $this->travelTo(now()->setDate(2026, 7, 15));

    $this->artisan('birthdays:send-upcoming')->assertExitCode(0);

    Mail::assertNothingSent();
});

test('command calculates correct age', function () {
    Mail::fake();
    $this->travelTo(now()->setDate(2026, 7, 15));

    Person::factory()->create([
        'status' => 'Employee',
        'date_of_birth' => '1990-07-20',
    ]);

    $this->artisan('birthdays:send-upcoming');

    Mail::sent(BirthdayReminder::class, function ($mail) {
        expect($mail->birthdays[0]['age'])->toBe(36);
    });
});
