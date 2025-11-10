<?php

namespace App\Policies;

use App\Models\Person;
use App\Models\PersonHistory;

class PersonHistoryPolicy
{
    /**
     * Determine whether user can view any models.
     */
    public function viewAny(Person $user): bool
    {
        return true; // All authenticated users can view person history
    }

    /**
     * Determine whether user can view model.
     */
    public function view(Person $user, PersonHistory $personHistory): bool
    {
        return true; // All authenticated users can view person history details
    }

    /**
     * Determine whether user can create models.
     */
    public function create(Person $user): bool
    {
        return true; // All authenticated users can create person history
    }

    /**
     * Determine whether user can update model.
     */
    public function update(Person $user, PersonHistory $personHistory): bool
    {
        return true; // All authenticated users can update person history
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(Person $user, PersonHistory $personHistory): bool
    {
        return true; // All authenticated users can delete person history
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(Person $user, PersonHistory $personHistory): bool
    {
        return true; // All authenticated users can restore person history
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(Person $user, PersonHistory $personHistory): bool
    {
        return true; // All authenticated users can force delete person history
    }
}
