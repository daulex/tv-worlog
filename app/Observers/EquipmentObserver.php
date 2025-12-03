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
            'holder_id' => $equipment->current_holder_id,
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
        // Check if holder has changed
        if ($equipment->isDirty('current_holder_id')) {
            $oldHolderId = $equipment->getOriginal('current_holder_id');
            $newHolderId = $equipment->current_holder_id;

            if ($oldHolderId !== $newHolderId) {
                $equipmentAction = $newHolderId
                    ? ($oldHolderId ? 'Assignment transferred' : 'Initial assignment')
                    : 'Transferred to Unassigned';

                $equipmentActionType = 'assigned';

                // Create equipment history
                EquipmentHistory::create([
                    'equipment_id' => $equipment->id,
                    'holder_id' => $newHolderId,
                    'change_date' => now(),
                    'action' => $equipmentAction,
                    'action_type' => $equipmentActionType,
                    'notes' => null, // No notes needed for assignment transfers
                    'performed_by_id' => Auth::id(),
                ]);

                // Create person history for the new holder
                if ($newHolderId) {
                    PersonHistory::create([
                        'person_id' => $newHolderId,
                        'change_date' => now(),
                        'action' => "Equipment assigned: {$equipment->brand} {$equipment->model}",
                        'action_type' => 'equipment_assigned',
                        'notes' => "Equipment '{$equipment->brand} {$equipment->model}' (S/N: {$equipment->serial}) was assigned to person",
                        'performed_by_id' => Auth::id(),
                    ]);
                }

                // Create person history for the previous holder if equipment was unassigned
                if ($oldHolderId && ! $newHolderId) {
                    PersonHistory::create([
                        'person_id' => $oldHolderId,
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
