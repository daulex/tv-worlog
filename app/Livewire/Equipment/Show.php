<?php

namespace App\Livewire\Equipment;

use App\Models\Equipment;
use App\Models\EquipmentHistory;
use App\Models\Person;
use Livewire\Component;

class Show extends Component
{
    public Equipment $equipment;

    public $newNote = '';

    public $showNoteForm = false;

    public $isEditing = false;

    public $showRetireForm = false;

    public $retirementNotes = '';

    public $editForm = [
        'brand' => '',
        'model' => '',
        'serial' => '',
        'purchase_date' => '',
        'purchase_price' => '',
        'current_owner_id' => '',
    ];

    public function mount(Equipment $equipment)
    {
        $this->equipment = $equipment->load([
            'currentOwner',
            'equipmentHistory.owner',
            'equipmentHistory.performedBy',
        ]);

        $this->initializeEditForm();
    }

    private function initializeEditForm()
    {
        $this->editForm = [
            'brand' => $this->equipment->brand,
            'model' => $this->equipment->model,
            'serial' => $this->equipment->serial,
            'purchase_date' => $this->equipment->purchase_date->format('Y-m-d'),
            'purchase_price' => $this->equipment->purchase_price,
            'current_owner_id' => $this->equipment->current_owner_id,
        ];
    }

    public function addNote()
    {
        $this->validate([
            'newNote' => 'required|string|max:1000',
        ]);

        EquipmentHistory::create([
            'equipment_id' => $this->equipment->id,
            'owner_id' => $this->equipment->current_owner_id,
            'change_date' => now(),
            'action' => 'Note added',
            'action_type' => 'note',
            'notes' => $this->newNote,
            'performed_by_id' => auth()->id(),
        ]);

        $this->newNote = '';
        $this->showNoteForm = false;

        // Refresh equipment history
        $this->equipment->load([
            'equipmentHistory.owner',
            'equipmentHistory.performedBy',
        ]);
    }

    public function toggleNoteForm()
    {
        $this->showNoteForm = ! $this->showNoteForm;
        $this->newNote = '';
    }

    public function toggleEditMode()
    {
        $this->isEditing = ! $this->isEditing;

        if ($this->isEditing) {
            $this->initializeEditForm();
        }
    }

    public function saveEquipment()
    {
        $this->validate([
            'editForm.brand' => 'required|string|max:255',
            'editForm.model' => 'required|string|max:255',
            'editForm.serial' => 'required|string|max:255',
            'editForm.purchase_date' => 'required|date',
            'editForm.purchase_price' => 'required|numeric|min:0',
            'editForm.current_owner_id' => 'nullable|exists:people,id',
        ]);

        $oldOwnerId = $this->equipment->current_owner_id;
        $newOwnerId = $this->editForm['current_owner_id'];

        $this->equipment->update([
            'brand' => $this->editForm['brand'],
            'model' => $this->editForm['model'],
            'serial' => $this->editForm['serial'],
            'purchase_date' => $this->editForm['purchase_date'],
            'purchase_price' => $this->editForm['purchase_price'],
            'current_owner_id' => $newOwnerId,
        ]);

        // Create history record if owner changed
        if ($oldOwnerId != $newOwnerId && $newOwnerId) {
            EquipmentHistory::create([
                'equipment_id' => $this->equipment->id,
                'owner_id' => $newOwnerId,
                'change_date' => now(),
                'action' => 'Equipment updated and assigned',
                'action_type' => 'assigned',
                'notes' => 'Equipment details were updated and assigned to new owner',
                'performed_by_id' => auth()->id(),
            ]);
        } elseif ($oldOwnerId != $newOwnerId) {
            EquipmentHistory::create([
                'equipment_id' => $this->equipment->id,
                'owner_id' => null,
                'change_date' => now(),
                'action' => 'Equipment updated and unassigned',
                'action_type' => 'assigned',
                'notes' => 'Equipment details were updated and unassigned from previous owner',
                'performed_by_id' => auth()->id(),
            ]);
        } else {
            EquipmentHistory::create([
                'equipment_id' => $this->equipment->id,
                'owner_id' => $this->equipment->current_owner_id,
                'change_date' => now(),
                'action' => 'Equipment details updated',
                'action_type' => 'note',
                'notes' => 'Equipment information was modified',
                'performed_by_id' => auth()->id(),
            ]);
        }

        // Refresh equipment data
        $this->equipment->load([
            'currentOwner',
            'equipmentHistory.owner',
            'equipmentHistory.performedBy',
        ]);

        $this->isEditing = false;
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->initializeEditForm();
    }

    public function toggleRetireForm()
    {
        $this->showRetireForm = ! $this->showRetireForm;
        $this->retirementNotes = '';
    }

    public function retireEquipment()
    {
        $this->validate([
            'retirementNotes' => 'required|string|max:1000',
        ]);

        $this->equipment->retire($this->retirementNotes);

        // Refresh equipment data
        $this->equipment->load([
            'currentOwner',
            'equipmentHistory.owner',
            'equipmentHistory.performedBy',
        ]);

        $this->showRetireForm = false;
        $this->retirementNotes = '';
    }

    public function unretireEquipment()
    {
        $this->equipment->unretire();

        // Refresh equipment data
        $this->equipment->load([
            'currentOwner',
            'equipmentHistory.owner',
            'equipmentHistory.performedBy',
        ]);
    }

    protected function rules(): array
    {
        return [
            'newNote' => 'required|string|max:1000',
            'retirementNotes' => 'required|string|max:1000',
            'editForm.brand' => 'required|string|max:255',
            'editForm.model' => 'required|string|max:255',
            'editForm.serial' => 'required|string|max:255',
            'editForm.purchase_date' => 'required|date',
            'editForm.purchase_price' => 'required|numeric|min:0',
            'editForm.current_owner_id' => 'nullable|exists:people,id',
        ];
    }

    public function getPeopleProperty()
    {
        return Person::orderBy('first_name')->orderBy('last_name')->get();
    }

    public function render()
    {
        return view('livewire.equipment.show');
    }
}
