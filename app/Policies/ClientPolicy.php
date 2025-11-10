<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\Person;

class ClientPolicy
{
    /**
     * Determine whether user can view any models.
     */
    public function viewAny(Person $user): bool
    {
        return true; // All authenticated users can view clients list
    }

    /**
     * Determine whether user can view model.
     */
    public function view(Person $user, Client $client): bool
    {
        return true; // All authenticated users can view client details
    }

    /**
     * Determine whether user can create models.
     */
    public function create(Person $user): bool
    {
        return true; // All authenticated users can create clients
    }

    /**
     * Determine whether user can update model.
     */
    public function update(Person $user, Client $client): bool
    {
        return true; // All authenticated users can update clients
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(Person $user, Client $client): bool
    {
        return true; // All authenticated users can delete clients
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(Person $user, Client $client): bool
    {
        return true; // All authenticated users can restore clients
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(Person $user, Client $client): bool
    {
        return true; // All authenticated users can force delete clients
    }
}
