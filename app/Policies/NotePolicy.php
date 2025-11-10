<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\Person;

class NotePolicy
{
    /**
     * Determine whether user can view any models.
     */
    public function viewAny(Person $user): bool
    {
        return true; // All authenticated users can view notes list
    }

    /**
     * Determine whether user can view model.
     */
    public function view(Person $user, Note $note): bool
    {
        return true; // All authenticated users can view note details
    }

    /**
     * Determine whether user can create models.
     */
    public function create(Person $user): bool
    {
        return true; // All authenticated users can create notes
    }

    /**
     * Determine whether user can update model.
     */
    public function update(Person $user, Note $note): bool
    {
        return true; // All authenticated users can update notes
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(Person $user, Note $note): bool
    {
        return true; // All authenticated users can delete notes
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(Person $user, Note $note): bool
    {
        return true; // All authenticated users can restore notes
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(Person $user, Note $note): bool
    {
        return true; // All authenticated users can force delete notes
    }
}
