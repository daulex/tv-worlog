<?php

namespace App\Observers;

use App\Models\Equipment;
use App\Models\EquipmentHistory;
use App\Models\PersonHistory;
use Illuminate\Support\Facades\Auth;

class EquipmentObserver
{
    /**
     * Handle the Equipment "created" event.
     */
    public function created(Equipment $equipment): void
    {
        // Create initial purchase history
        EquipmentHistory::create([
            'equipment_id' => $equipment->id,
            'owner_id' => $equipment->current_owner_id,
            'change_date' => $equipment->purchase_date ?? now(),
            'action' => 'Initial purchase',
            'action_type' => 'purchased',
            'notes' => 'Equipment added to inventory',
            'performed_by_id' => Auth::id(),
        ]);
    }

    /**
     * Handle the Equipment "updated" event.
     */
    public function updated(Equipment $equipment): void
    {
        // Check if owner has changed
        if ($equipment->isDirty('current_owner_id')) {
            $oldOwnerId = $equipment->getOriginal('current_owner_id');
            $newOwnerId = $equipment->current_owner_id;

            if ($oldOwnerId !== $newOwnerId) {
                $equipmentAction = $newOwnerId
                    ? ($oldOwnerId ? 'Ownership transferred' : 'Initial assignment')
                    : 'Equipment unassigned';

                $equipmentActionType = $newOwnerId ? 'assigned' : 'retired';

                // Create equipment history
                EquipmentHistory::create([
                    'equipment_id' => $equipment->id,
                    'owner_id' => $newOwnerId,
                    'change_date' => now(),
                    'action' => $equipmentAction,
                    'action_type' => $equipmentActionType,
                    'notes' => null, // No notes needed for ownership transfers
                    'performed_by_id' => Auth::id(),
                ]);

                // Create person history for the new owner
                if ($newOwnerId) {
                    PersonHistory::create([
                        'person_id' => $newOwnerId,
                        'change_date' => now(),
                        'action' => "Equipment assigned: {$equipment->brand} {$equipment->model}",
                        'action_type' => 'equipment_assigned',
                        'notes' => "Equipment '{$equipment->brand} {$equipment->model}' (S/N: {$equipment->serial}) was assigned to person",
                        'performed_by_id' => Auth::id(),
                    ]);
                }

                // Create person history for the previous owner if equipment was unassigned
                if ($oldOwnerId && ! $newOwnerId) {
                    PersonHistory::create([
                        'person_id' => $oldOwnerId,
                        'change_date' => now(),
                        'action' => "Equipment returned: {$equipment->brand} {$equipment->model}",
                        'action_type' => 'equipment_returned',
                        'notes' => "Equipment '{$equipment->brand} {$equipment->model}' (S/N: {$equipment->serial}) was returned by person",
                        'performed_by_id' => Auth::id(),
                    ]);
                }
            }
        }
    }

    /**
     * Handle the Equipment "deleted" event.
     */
    public function deleted(Equipment $equipment): void
    {
        // Create retirement history when equipment is deleted
        EquipmentHistory::create([
            'equipment_id' => $equipment->id,
            'owner_id' => $equipment->current_owner_id,
            'change_date' => now(),
            'action' => 'Equipment removed from inventory',
            'action_type' => 'retired',
            'notes' => 'Equipment deleted from system',
            'performed_by_id' => Auth::id(),
        ]);
    }

    /**
     * Handle the Equipment "restored" event.
     */
    public function restored(Equipment $equipment): void
    {
        //
    }

    /**
     * Handle the Equipment "force deleted" event.
     */
    public function forceDeleted(Equipment $equipment): void
    {
        //
    }
}
