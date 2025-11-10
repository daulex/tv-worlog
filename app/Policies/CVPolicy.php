<?php

namespace App\Policies;

use App\Models\CV;
use App\Models\Person;

class CVPolicy
{
    /**
     * Determine whether user can view any models.
     */
    public function viewAny(Person $user): bool
    {
        return true; // All authenticated users can view CVs list
    }

    /**
     * Determine whether user can view model.
     */
    public function view(Person $user, CV $cv): bool
    {
        return true; // All authenticated users can view CV details
    }

    /**
     * Determine whether user can create models.
     */
    public function create(Person $user): bool
    {
        return true; // All authenticated users can create CVs
    }

    /**
     * Determine whether user can update model.
     */
    public function update(Person $user, CV $cv): bool
    {
        return true; // All authenticated users can update CVs
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(Person $user, CV $cv): bool
    {
        return true; // All authenticated users can delete CVs
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(Person $user, CV $cv): bool
    {
        return true; // All authenticated users can restore CVs
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(Person $user, CV $cv): bool
    {
        return true; // All authenticated users can force delete CVs
    }

    /**
     * Determine whether user can upload CV files.
     */
    public function upload(Person $user): bool
    {
        return true; // All authenticated users can upload CV files
    }
}
