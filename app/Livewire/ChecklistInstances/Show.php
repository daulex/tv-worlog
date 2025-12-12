<?php

namespace App\Livewire\ChecklistInstances;

use App\Models\ChecklistInstance;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public ChecklistInstance $checklistInstance;

    public $itemValues = [];

    public function mount(ChecklistInstance $checklistInstance)
    {
        $this->authorize('view', $checklistInstance);

        $this->checklistInstance = $checklistInstance->load(['checklist.items', 'itemInstances']);

        // Initialize item values for all checklist items
        foreach ($this->checklistInstance->checklist->items as $item) {
            $itemInstance = $this->checklistInstance->itemInstances->where('checklist_item_id', $item->id)->first();
            if ($item->type === 'bool') {
                $this->itemValues[$item->id] = $itemInstance ? ($itemInstance->value === '1') : false;
            } else {
                $this->itemValues[$item->id] = $itemInstance ? $itemInstance->value : '';
            }
        }
    }

    public function toggleItem($itemId)
    {
        try {
            $this->authorize('update', $this->checklistInstance);

            // Find the item instance
            $itemInstance = $this->checklistInstance->itemInstances()
                ->where('checklist_item_id', $itemId)
                ->first();

            // Determine the new value (toggle current state)
            $currentValue = $itemInstance ? $itemInstance->value : null;
            $newValue = ($currentValue === '1') ? '0' : '1';

            if (! $itemInstance) {
                // Create new item instance if it doesn't exist
                $itemInstance = $this->checklistInstance->itemInstances()->create([
                    'checklist_item_id' => $itemId,
                    'value' => $newValue,
                ]);
            } else {
                // Update existing item instance
                $itemInstance->update(['value' => $newValue]);
            }

            // Update the local itemValues array
            $this->itemValues[$itemId] = $newValue === '1';

            // Check completion status
            $this->checklistInstance->checkCompletion();
            $this->checklistInstance->refresh();

        } catch (\Exception $e) {
            \Log::error('Failed to toggle checklist item', [
                'itemId' => $itemId,
                'checklistInstanceId' => $this->checklistInstance->id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to update checklist item. Please try again.');
        }
    }

    public function updateItem($itemId, $value)
    {
        $this->authorize('update', $this->checklistInstance);

        // Validate input based on item type
        $item = $this->checklistInstance->checklist->items->find($itemId);
        if (! $item) {
            return;
        }

        // Validate value based on type
        if (! $this->validateItemValue($item, $value)) {
            session()->flash('error', 'Invalid value provided for '.$item->label);

            return;
        }

        // Find or create the item instance
        $itemInstance = $this->checklistInstance->itemInstances()
            ->where('checklist_item_id', $itemId)
            ->first();

        if (! $itemInstance) {
            // Create new item instance if it doesn't exist
            $itemInstance = $this->checklistInstance->itemInstances()->create([
                'checklist_item_id' => $itemId,
                'value' => $value,
            ]);
        } else {
            // Update existing item instance
            $itemInstance->update(['value' => $value]);
        }

        // Update the local itemValues array
        $this->itemValues[$itemId] = $value;

        // Check completion status
        $this->checklistInstance->checkCompletion();
        $this->checklistInstance->refresh();

        // Clear progress cache to ensure fresh data
        \Cache::forget("checklist_instance_progress_{$this->checklistInstance->id}");
    }

    protected function validateItemValue($item, $value): bool
    {
        return match ($item->type) {
            'bool' => in_array($value, ['0', '1', true, false], true),
            'text' => is_string($value) && strlen($value) <= 1000,
            'number' => is_numeric($value),
            'textarea' => is_string($value) && strlen($value) <= 5000,
            default => false,
        };
    }

    public function getItemInstance($itemId)
    {
        return $this->checklistInstance->itemInstances->where('checklist_item_id', $itemId)->first();
    }

    public function updatedItemValues($value, $key)
    {
        // Handle real-time updates from wire:model.live
        // $key will be the item ID, $value will be the new value
        $this->updateItem($key, $value);
    }

    public function isItemCompleted($itemId): bool
    {
        $itemInstance = $this->getItemInstance($itemId);

        return $itemInstance && $itemInstance->is_completed;
    }

    public function render()
    {
        return view('livewire.checklist-instances.show');
    }
}
