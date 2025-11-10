<?php

namespace App\Policies;

use App\Models\Person;

class PersonPolicy
{
    /**
     * Determine whether user can view any models.
     */
    public function viewAny(Person $user): bool
    {
        return true; // All authenticated users can view people list
    }

    /**
     * Determine whether user can view model.
     */
    public function view(Person $user, Person $person): bool
    {
        return true; // All authenticated users can view person details
    }

    /**
     * Determine whether user can create models.
     */
    public function create(Person $user): bool
    {
        return true; // For now, allow all authenticated users to create
    }

    /**
     * Determine whether user can update model.
     */
    public function update(Person $user, Person $person): bool
    {
        // Users can update their own profile
        if ($user->id === $person->id) {
            return true;
        }

        // For now, allow all authenticated users to update any person
        return true;
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(Person $user, Person $person): bool
    {
        // Users cannot delete themselves
        if ($user->id === $person->id) {
            return false;
        }

        // For now, allow all authenticated users to delete people (except themselves)
        return true;
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(Person $user, Person $person): bool
    {
        return true; // For now, allow all authenticated users to restore
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(Person $user, Person $person): bool
    {
        return true; // For now, allow all authenticated users to force delete
    }

    /**
     * Determine whether user can manage notes for person.
     */
    public function manageNotes(Person $user, Person $person): bool
    {
        // Users can manage notes on their own profile
        if ($user->id === $person->id) {
            return true;
        }

        // For now, allow all authenticated users to manage notes on any person
        return true;
    }
}
