<?php

namespace App\Observers;

use App\Models\EventParticipant;
use App\Models\PersonHistory;
use Illuminate\Support\Facades\Auth;

class EventParticipantObserver
{
    /**
     * Handle the EventParticipant "created" event.
     */
    public function created(EventParticipant $eventParticipant): void
    {
        PersonHistory::create([
            'person_id' => $eventParticipant->person_id,
            'change_date' => now(),
            'action' => "Joined event: {$eventParticipant->event->title}",
            'action_type' => 'event_joined',
            'notes' => "Person joined event '{$eventParticipant->event->title}' on {$eventParticipant->event->start_date->format('M d, Y')}",
            'performed_by_id' => Auth::id(),
        ]);
    }

    /**
     * Handle the EventParticipant "updated" event.
     */
    public function updated(EventParticipant $eventParticipant): void
    {
        //
    }

    /**
     * Handle the EventParticipant "deleted" event.
     */
    public function deleted(EventParticipant $eventParticipant): void
    {
        PersonHistory::create([
            'person_id' => $eventParticipant->person_id,
            'change_date' => now(),
            'action' => "Left event: {$eventParticipant->event->title}",
            'action_type' => 'event_left',
            'notes' => "Person left event '{$eventParticipant->event->title}'",
            'performed_by_id' => Auth::id(),
        ]);
    }

    /**
     * Handle the EventParticipant "restored" event.
     */
    public function restored(EventParticipant $eventParticipant): void
    {
        //
    }

    /**
     * Handle the EventParticipant "force deleted" event.
     */
    public function forceDeleted(EventParticipant $eventParticipant): void
    {
        //
    }
}
