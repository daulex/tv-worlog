<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Person;

class EventPolicy
{
    /**
     * Determine whether user can view any models.
     */
    public function viewAny(Person $user): bool
    {
        return true; // All authenticated users can view events list
    }

    /**
     * Determine whether user can view model.
     */
    public function view(Person $user, Event $event): bool
    {
        return true; // All authenticated users can view event details
    }

    /**
     * Determine whether user can create models.
     */
    public function create(Person $user): bool
    {
        return true; // All authenticated users can create events
    }

    /**
     * Determine whether user can update model.
     */
    public function update(Person $user, Event $event): bool
    {
        return true; // All authenticated users can update events
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(Person $user, Event $event): bool
    {
        return true; // All authenticated users can delete events
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(Person $user, Event $event): bool
    {
        return true; // All authenticated users can restore events
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(Person $user, Event $event): bool
    {
        return true; // All authenticated users can force delete events
    }

    /**
     * Determine whether user can manage event participants.
     */
    public function manageParticipants(Person $user, Event $event): bool
    {
        return true; // All authenticated users can manage event participants
    }
}
