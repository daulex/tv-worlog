<?php

namespace App\Policies;

use App\Models\Checklist;
use App\Models\Person;

class ChecklistPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Person $person): bool
    {
        return true; // Allow authenticated users to view checklists
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Person $person, Checklist $checklist): bool
    {
        return true; // Allow authenticated users to view checklists
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Person $person): bool
    {
        return true; // Allow authenticated users to create checklists
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Person $person, Checklist $checklist): bool
    {
        return true; // Allow authenticated users to update checklists
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Person $person, Checklist $checklist): bool
    {
        return true; // Allow authenticated users to delete checklists
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Person $person, Checklist $checklist): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Person $person, Checklist $checklist): bool
    {
        return false;
    }
}
