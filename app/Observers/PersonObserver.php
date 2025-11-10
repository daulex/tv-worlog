<?php

namespace App\Observers;

use App\Models\Person;
use App\Models\PersonHistory;
use Illuminate\Support\Facades\Auth;

class PersonObserver
{
    /**
     * Handle the Person "created" event.
     */
    public function created(Person $person): void
    {
        PersonHistory::create([
            'person_id' => $person->id,
            'change_date' => now(),
            'action' => 'Person created',
            'action_type' => 'profile_updated',
            'notes' => "New person profile created: {$person->full_name}",
            'performed_by_id' => Auth::id() ?: null,
        ]);
    }

    /**
     * Handle the Person "updated" event.
     */
    public function updated(Person $person): void
    {
        $changes = [];
        $original = $person->getOriginal();

        $fieldsToTrack = ['first_name', 'last_name', 'email', 'email2', 'phone', 'phone2', 'position', 'status', 'client_id', 'vacancy_id'];

        foreach ($fieldsToTrack as $field) {
            if ($person->wasChanged($field)) {
                $oldValue = $original[$field] ?? 'null';
                $newValue = $person->$field;

                if ($field === 'client_id') {
                    $oldClient = $oldValue ? \App\Models\Client::find($oldValue)?->name : 'None';
                    $newClient = $newValue ? \App\Models\Client::find($newValue)?->name : 'None';
                    $changes[] = "Client: {$oldClient} → {$newClient}";
                } elseif ($field === 'vacancy_id') {
                    $oldVacancy = $oldValue ? \App\Models\Vacancy::find($oldValue)?->title : 'None';
                    $newVacancy = $newValue ? \App\Models\Vacancy::find($newValue)?->title : 'None';
                    $changes[] = "Vacancy: {$oldVacancy} → {$newVacancy}";
                } else {
                    $changes[] = ucfirst(str_replace('_', ' ', $field)).": {$oldValue} → {$newValue}";
                }
            }
        }

        if (! empty($changes)) {
            PersonHistory::create([
                'person_id' => $person->id,
                'change_date' => now(),
                'action' => 'Profile updated',
                'action_type' => 'profile_updated',
                'notes' => implode(', ', $changes),
                'performed_by_id' => Auth::id() ?: null,
            ]);
        }
    }

    /**
     * Handle the Person "deleting" event.
     */
    public function deleting(Person $person): void
    {
        // Only create history if someone else deleted the person, not self-deletion
        if (Auth::id() && Auth::id() !== $person->id) {
            PersonHistory::create([
                'person_id' => $person->id,
                'change_date' => now(),
                'action' => 'Person deleted',
                'action_type' => 'profile_updated',
                'notes' => "Person profile deleted: {$person->full_name}",
                'performed_by_id' => Auth::id(),
            ]);
        }
    }

    /**
     * Handle the Person "restored" event.
     */
    public function restored(Person $person): void
    {
        PersonHistory::create([
            'person_id' => $person->id,
            'change_date' => now(),
            'action' => 'Person restored',
            'action_type' => 'profile_updated',
            'notes' => "Person profile restored: {$person->full_name}",
            'performed_by_id' => Auth::id() ?: null,
        ]);
    }

    /**
     * Handle the Person "force deleted" event.
     */
    public function forceDeleted(Person $person): void
    {
        PersonHistory::create([
            'person_id' => $person->id,
            'change_date' => now(),
            'action' => 'Person permanently deleted',
            'action_type' => 'profile_updated',
            'notes' => "Person profile permanently deleted: {$person->full_name}",
            'performed_by_id' => Auth::id() ?: null,
        ]);
    }
}
