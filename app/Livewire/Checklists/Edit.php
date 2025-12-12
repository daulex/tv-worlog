<?php

namespace App\Livewire\Checklists;

use App\Models\Checklist;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Edit extends Component
{
    use AuthorizesRequests;

    public Checklist $checklist;

    public $title = '';

    public $description = '';

    public $items = [];

    public function mount(Checklist $checklist)
    {
        $this->authorize('update', $checklist);

        $this->checklist = $checklist->load('items');

        $this->title = $checklist->title;
        $this->description = $checklist->description;

        // Load existing items
        $this->items = $checklist->items->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'label' => $item->label,
                'required' => $item->required,
                'order' => $item->order,
            ];
        })->toArray();

        // Ensure at least one item
        if (empty($this->items)) {
            $this->items = [
                [
                    'type' => 'bool',
                    'label' => '',
                    'required' => false,
                    'order' => 0,
                ],
            ];
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'type' => 'bool',
            'label' => '',
            'required' => false,
            'order' => count($this->items),
        ];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items); // Reindex array
            $this->reorderItems();
        }
    }

    public function moveItemUp($index)
    {
        if ($index > 0) {
            $temp = $this->items[$index];
            $this->items[$index] = $this->items[$index - 1];
            $this->items[$index - 1] = $temp;
            $this->reorderItems();
        }
    }

    public function moveItemDown($index)
    {
        if ($index < count($this->items) - 1) {
            $temp = $this->items[$index];
            $this->items[$index] = $this->items[$index + 1];
            $this->items[$index + 1] = $temp;
            $this->reorderItems();
        }
    }

    private function reorderItems()
    {
        foreach ($this->items as $index => $item) {
            $this->items[$index]['order'] = $index;
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:bool,text,number,textarea',
            'items.*.label' => 'required|string|max:255',
            'items.*.required' => 'boolean',
        ]);

        $this->checklist->update([
            'title' => $this->title,
            'description' => $this->description,
        ]);

        // Delete existing items and recreate them
        $this->checklist->items()->delete();

        foreach ($this->items as $itemData) {
            $this->checklist->items()->create($itemData);
        }

        session()->flash('message', 'Checklist updated successfully.');

        return redirect()->route('checklists.show', $this->checklist);
    }

    public function render()
    {
        return view('livewire.checklists.edit');
    }
}
