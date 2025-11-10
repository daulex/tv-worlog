<?php

namespace App\Policies;

use App\Models\EquipmentHistory;
use App\Models\Person;

class EquipmentHistoryPolicy
{
    /**
     * Determine whether user can view any models.
     */
    public function viewAny(Person $user): bool
    {
        return true; // All authenticated users can view equipment history
    }

    /**
     * Determine whether user can view model.
     */
    public function view(Person $user, EquipmentHistory $equipmentHistory): bool
    {
        return true; // All authenticated users can view equipment history details
    }

    /**
     * Determine whether user can create models.
     */
    public function create(Person $user): bool
    {
        return true; // All authenticated users can create equipment history
    }

    /**
     * Determine whether user can update model.
     */
    public function update(Person $user, EquipmentHistory $equipmentHistory): bool
    {
        return true; // All authenticated users can update equipment history
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(Person $user, EquipmentHistory $equipmentHistory): bool
    {
        return true; // All authenticated users can delete equipment history
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(Person $user, EquipmentHistory $equipmentHistory): bool
    {
        return true; // All authenticated users can restore equipment history
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(Person $user, EquipmentHistory $equipmentHistory): bool
    {
        return true; // All authenticated users can force delete equipment history
    }
}
