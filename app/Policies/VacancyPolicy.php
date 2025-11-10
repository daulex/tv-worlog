<?php

namespace App\Policies;

use App\Models\Person;
use App\Models\Vacancy;

class VacancyPolicy
{
    /**
     * Determine whether user can view any models.
     */
    public function viewAny(Person $user): bool
    {
        return true; // All authenticated users can view vacancies list
    }

    /**
     * Determine whether user can view model.
     */
    public function view(Person $user, Vacancy $vacancy): bool
    {
        return true; // All authenticated users can view vacancy details
    }

    /**
     * Determine whether user can create models.
     */
    public function create(Person $user): bool
    {
        return true; // All authenticated users can create vacancies
    }

    /**
     * Determine whether user can update model.
     */
    public function update(Person $user, Vacancy $vacancy): bool
    {
        return true; // All authenticated users can update vacancies
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(Person $user, Vacancy $vacancy): bool
    {
        return true; // All authenticated users can delete vacancies
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(Person $user, Vacancy $vacancy): bool
    {
        return true; // All authenticated users can restore vacancies
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(Person $user, Vacancy $vacancy): bool
    {
        return true; // All authenticated users can force delete vacancies
    }
}
