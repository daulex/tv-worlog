<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\Person;

class EquipmentPolicy
{
    /**
     * Determine whether user can view any models.
     */
    public function viewAny(Person $user): bool
    {
        return true; // All authenticated users can view equipment list
    }

    /**
     * Determine whether user can view model.
     */
    public function view(Person $user, Equipment $equipment): bool
    {
        return true; // All authenticated users can view equipment details
    }

    /**
     * Determine whether user can create models.
     */
    public function create(Person $user): bool
    {
        return true; // All authenticated users can create equipment
    }

    /**
     * Determine whether user can update model.
     */
    public function update(Person $user, Equipment $equipment): bool
    {
        return true; // All authenticated users can update equipment
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(Person $user, Equipment $equipment): bool
    {
        return true; // All authenticated users can delete equipment
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(Person $user, Equipment $equipment): bool
    {
        return true; // All authenticated users can restore equipment
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(Person $user, Equipment $equipment): bool
    {
        return true; // All authenticated users can force delete equipment
    }

    /**
     * Determine whether user can manage equipment history.
     */
    public function manageHistory(Person $user, Equipment $equipment): bool
    {
        return true; // All authenticated users can manage equipment history
    }

    /**
     * Determine whether user can retire equipment.
     */
    public function retire(Person $user, Equipment $equipment): bool
    {
        return true; // All authenticated users can retire equipment
    }

    /**
     * Determine whether user can unretire equipment.
     */
    public function unretire(Person $user, Equipment $equipment): bool
    {
        return true; // All authenticated users can unretire equipment
    }
}
