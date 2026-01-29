<?php

namespace App\Observers;

use App\Models\Reimbursement;
use App\Models\ReimbursementHistory;
use Illuminate\Support\Facades\Auth;

class ReimbursementObserver
{
    /**
     * Handle the Reimbursement "created" event.
     */
    public function created(Reimbursement $reimbursement): void
    {
        ReimbursementHistory::create([
            'reimbursement_id' => $reimbursement->id,
            'change_date' => now(),
            'action' => 'Reimbursement created',
            'action_type' => 'created',
            'notes' => "Created reimbursement: {$reimbursement->name} for {$reimbursement->client->name} (\${$reimbursement->amount})",
            'performed_by_id' => Auth::id() ?: null,
        ]);
    }

    /**
     * Handle the Reimbursement "updated" event.
     */
    public function updated(Reimbursement $reimbursement): void
    {
        $changes = [];
        $original = $reimbursement->getOriginal();

        $fieldsToTrack = ['name', 'amount', 'notes', 'client_id'];

        foreach ($fieldsToTrack as $field) {
            if ($reimbursement->wasChanged($field)) {
                $oldValue = $original[$field] ?? 'null';
                $newValue = $reimbursement->$field;

                if ($field === 'client_id') {
                    $oldClient = $oldValue ? \App\Models\Client::find($oldValue)?->name : 'None';
                    $newClient = $newValue ? \App\Models\Client::find($newValue)?->name : 'None';
                    $changes[] = "Client: {$oldClient} → {$newClient}";
                } elseif ($field === 'amount') {
                    $changes[] = "Amount: \${$oldValue} → \${$newValue}";
                } else {
                    $changes[] = ucfirst(str_replace('_', ' ', $field)).": {$oldValue} → {$newValue}";
                }
            }
        }

        if (! empty($changes)) {
            ReimbursementHistory::create([
                'reimbursement_id' => $reimbursement->id,
                'change_date' => now(),
                'action' => 'Reimbursement updated',
                'action_type' => 'updated',
                'notes' => implode(', ', $changes),
                'performed_by_id' => Auth::id() ?: null,
            ]);
        }
    }

    /**
     * Handle the Reimbursement "deleting" event (before deletion).
     */
    public function deleting(Reimbursement $reimbursement): void
    {
        ReimbursementHistory::create([
            'reimbursement_id' => $reimbursement->id,
            'change_date' => now(),
            'action' => 'Reimbursement deleted',
            'action_type' => 'deleted',
            'notes' => "Deleted reimbursement: {$reimbursement->name} (\${$reimbursement->amount})",
            'performed_by_id' => Auth::id() ?: null,
        ]);
    }

    /**
     * Handle the Reimbursement "deleted" event.
     */
    public function deleted(Reimbursement $reimbursement): void
    {
        // History already created in deleting() - no need to do anything here
    }

    /**
     * Handle the Reimbursement "restored" event.
     */
    public function restored(Reimbursement $reimbursement): void
    {
        ReimbursementHistory::create([
            'reimbursement_id' => $reimbursement->id,
            'change_date' => now(),
            'action' => 'Reimbursement restored',
            'action_type' => 'created',
            'notes' => "Restored reimbursement: {$reimbursement->name}",
            'performed_by_id' => Auth::id() ?: null,
        ]);
    }

    /**
     * Handle the Reimbursement "force deleted" event.
     */
    public function forceDeleted(Reimbursement $reimbursement): void
    {
        ReimbursementHistory::create([
            'reimbursement_id' => $reimbursement->id,
            'change_date' => now(),
            'action' => 'Reimbursement permanently deleted',
            'action_type' => 'deleted',
            'notes' => "Permanently deleted reimbursement: {$reimbursement->name}",
            'performed_by_id' => Auth::id() ?: null,
        ]);
    }
}
