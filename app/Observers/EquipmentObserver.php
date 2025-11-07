<?php

namespace App\Observers;

use App\Models\Equipment;
use App\Models\EquipmentHistory;
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
                EquipmentHistory::create([
                    'equipment_id' => $equipment->id,
                    'owner_id' => $newOwnerId,
                    'change_date' => now(),
                    'action' => $newOwnerId
                        ? ($oldOwnerId ? 'Ownership transferred' : 'Initial assignment')
                        : 'Equipment unassigned',
                    'action_type' => 'assigned',
                    'notes' => null, // No notes needed for ownership transfers
                    'performed_by_id' => Auth::id(),
                ]);
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
