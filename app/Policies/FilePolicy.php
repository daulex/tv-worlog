<?php

namespace App\Policies;

use App\Models\File;
use App\Models\Person;

class FilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Person $person): bool
    {
        return true; // All authenticated users can view files list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Person $person, File $file): bool
    {
        return true; // All authenticated users can view file details
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Person $person): bool
    {
        return true; // All authenticated users can create files
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Person $person, File $file): bool
    {
        return true; // All authenticated users can update files
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Person $person, File $file): bool
    {
        return true; // All authenticated users can delete files
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Person $person, File $file): bool
    {
        return true; // All authenticated users can restore files
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Person $person, File $file): bool
    {
        return true; // All authenticated users can force delete files
    }

    /**
     * Determine whether the user can upload files.
     */
    public function upload(Person $person): bool
    {
        return true; // All authenticated users can upload files
    }

    /**
     * Determine whether the user can download files.
     */
    public function download(Person $person, File $file): bool
    {
        return true; // All authenticated users can download files
    }

    /**
     * Determine whether the user can manage files for a specific person.
     */
    public function manageForPerson(Person $person, Person $targetPerson): bool
    {
        // For now, all authenticated users can manage files for any person
        // In a real application, you might want to add role-based restrictions
        return true;
    }
}
