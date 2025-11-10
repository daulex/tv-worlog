<?php

namespace App\Policies;

use App\Models\EventParticipant;
use App\Models\Person;

class EventParticipantPolicy
{
    /**
     * Determine whether user can view any models.
     */
    public function viewAny(Person $user): bool
    {
        return true; // All authenticated users can view event participants
    }

    /**
     * Determine whether user can view model.
     */
    public function view(Person $user, EventParticipant $eventParticipant): bool
    {
        return true; // All authenticated users can view event participant details
    }

    /**
     * Determine whether user can create models.
     */
    public function create(Person $user): bool
    {
        return true; // All authenticated users can create event participants
    }

    /**
     * Determine whether user can update model.
     */
    public function update(Person $user, EventParticipant $eventParticipant): bool
    {
        return true; // All authenticated users can update event participants
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(Person $user, EventParticipant $eventParticipant): bool
    {
        return true; // All authenticated users can delete event participants
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(Person $user, EventParticipant $eventParticipant): bool
    {
        return true; // All authenticated users can restore event participants
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(Person $user, EventParticipant $eventParticipant): bool
    {
        return true; // All authenticated users can force delete event participants
    }
}
