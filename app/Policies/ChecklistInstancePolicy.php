<?php

namespace App\Policies;

use App\Models\ChecklistInstance;
use App\Models\Person;

class ChecklistInstancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Person $person): bool
    {
        return true; // Allow authenticated users to view checklist instances
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Person $person, ChecklistInstance $checklistInstance): bool
    {
        // Allow viewing if user can view the associated person
        return $person->can('view', $checklistInstance->person);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Person $person): bool
    {
        return true; // Allow authenticated users to create checklist instances
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Person $person, ChecklistInstance $checklistInstance): bool
    {
        // Allow updating if user can update the associated person
        return $person->can('update', $checklistInstance->person);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Person $person, ChecklistInstance $checklistInstance): bool
    {
        // Allow deleting if user can update the associated person
        return $person->can('update', $checklistInstance->person);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Person $person, ChecklistInstance $checklistInstance): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Person $person, ChecklistInstance $checklistInstance): bool
    {
        return false;
    }
}
