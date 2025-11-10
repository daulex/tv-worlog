<?php

namespace App\Livewire\Equipment;

use App\Models\Equipment;
use App\Models\EquipmentHistory;
use App\Models\Note;
use App\Models\Person;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Equipment $equipment;

    public $newNote = '';

    public $showNoteForm = false;

    public $isEditing = false;

    public $showRetireForm = false;

    public $retirementNotes = '';

    public $editingHistory = null;

    public $historyEditForm = [
        'id' => null,
        'notes' => '',
        'action' => '',
    ];

    public $editingNote = null;

    public $noteEditForm = [
        'id' => null,
        'note_text' => '',
    ];

    public $editForm = [
        'brand' => '',
        'model' => '',
        'serial' => '',
        'purchase_date' => '',
        'purchase_price' => '',
        'current_holder_id' => '',
    ];

    public function mount(Equipment $equipment)
    {
        $this->authorize('view', $equipment);

        $this->equipment = $equipment->load([
            'currentHolder',
            'equipmentHistory.holder',
            'equipmentHistory.performedBy',
            'notes',
        ]);

        $this->initializeEditForm();
    }

    public function getTimeline()
    {
        // Force fresh load from database to avoid caching issues
        $freshEquipment = Equipment::with([
            'equipmentHistory' => function ($query) {
                $query->where('action_type', '!=', 'note')->orderBy('change_date', 'desc')->orderBy('id', 'desc');
            },
            'notes' => function ($query) {
                $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
            },
        ])->find($this->equipment->id);

        $timeline = collect();

        // Add equipment history items (excluding notes since they're handled separately)
        foreach ($freshEquipment->equipmentHistory as $history) {
            $timeline->push([
                'type' => 'history',
                'date' => $history->change_date,
                'id' => $history->id,
                'data' => $history,
            ]);
        }

        // Add notes
        foreach ($freshEquipment->notes as $note) {
            $timeline->push([
                'type' => 'note',
                'date' => $note->created_at,
                'id' => $note->id,
                'data' => $note,
            ]);
        }

        // Sort by date first, then by ID (newest first)
        return $timeline->sortByDesc(function ($item) {
            return $item['date']->timestamp.str_pad((string) $item['id'], 10, '0', STR_PAD_LEFT);
        })->values();
    }

    private function initializeEditForm()
    {
        $this->editForm = [
            'brand' => $this->equipment->brand,
            'model' => $this->equipment->model,
            'serial' => $this->equipment->serial,
            'purchase_date' => $this->equipment->purchase_date->format('Y-m-d'),
            'purchase_price' => $this->equipment->purchase_price,
            'current_holder_id' => $this->equipment->current_holder_id,
        ];
    }

    public function addNote()
    {
        $this->validate([
            'newNote' => 'required|string|max:1000',
        ]);

        $this->equipment->notes()->create([
            'note_text' => $this->newNote,
        ]);

        $this->newNote = '';
        $this->showNoteForm = false;

        // Refresh equipment and notes
        $this->equipment->load('notes');
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
            'editForm.current_holder_id' => 'nullable|exists:people,id',
        ]);

        $oldHolderId = $this->equipment->current_holder_id;
        $newHolderId = $this->editForm['current_holder_id'];

        $this->equipment->update([
            'brand' => $this->editForm['brand'],
            'model' => $this->editForm['model'],
            'serial' => $this->editForm['serial'],
            'purchase_date' => $this->editForm['purchase_date'],
            'purchase_price' => $this->editForm['purchase_price'],
            'current_holder_id' => $newHolderId,
        ]);

        // Note: Equipment history is now handled by the EquipmentObserver
        // This prevents duplicate entries for ownership changes

        // Refresh equipment data
        $this->equipment->load([
            'currentHolder',
            'equipmentHistory.holder',
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
            'currentHolder',
            'equipmentHistory.holder',
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
            'currentHolder',
            'equipmentHistory.holder',
            'equipmentHistory.performedBy',
        ]);
    }

    public function editHistory($historyId)
    {
        $history = EquipmentHistory::find($historyId);

        if (! $history || $history->equipment_id !== $this->equipment->id) {
            return;
        }

        // Only allow editing notes and action for certain types
        if (! in_array($history->action_type, ['note', 'repaired'])) {
            return;
        }

        $this->editingHistory = $history->id;
        $this->historyEditForm = [
            'id' => $history->id,
            'notes' => $history->notes,
            'action' => $history->action,
        ];
    }

    public function cancelHistoryEdit()
    {
        $this->editingHistory = null;
        $this->historyEditForm = [
            'id' => null,
            'notes' => '',
            'action' => '',
        ];
    }

    public function saveHistoryEdit()
    {
        $this->validate([
            'historyEditForm.notes' => 'required|string|max:1000',
            'historyEditForm.action' => 'required|string|max:255',
        ]);

        $history = EquipmentHistory::find($this->historyEditForm['id']);

        if (! $history || ! in_array($history->action_type, ['note', 'repaired'])) {
            return;
        }

        $history->update([
            'notes' => $this->historyEditForm['notes'],
            'action' => $this->historyEditForm['action'],
        ]);

        // Add a tracking entry for the edit
        EquipmentHistory::create([
            'equipment_id' => $this->equipment->id,
            'holder_id' => $history->holder_id,
            'change_date' => now(),
            'action' => 'History entry edited',
            'action_type' => 'note',
            'notes' => "History entry from {$history->change_date->format('M d, Y')} was modified",
            'performed_by_id' => auth()->id(),
        ]);

        $this->cancelHistoryEdit();

        // Refresh equipment data
        $this->equipment->load([
            'currentHolder',
            'equipmentHistory.holder',
            'equipmentHistory.performedBy',
        ]);
    }

    public function deleteHistory($historyId)
    {
        $history = EquipmentHistory::find($historyId);

        if (! $history || $history->equipment_id !== $this->equipment->id) {
            return;
        }

        // Don't allow deleting certain critical history types
        if (in_array($history->action_type, ['purchased', 'assigned', 'retired'])) {
            return;
        }

        $history->delete();

        // Add a tracking entry for the deletion
        EquipmentHistory::create([
            'equipment_id' => $this->equipment->id,
            'holder_id' => $this->equipment->current_holder_id,
            'change_date' => now(),
            'action' => 'History entry deleted',
            'action_type' => 'note',
            'notes' => "History entry from {$history->change_date->format('M d, Y')} was deleted: {$history->action}",
            'performed_by_id' => auth()->id(),
        ]);

        // Refresh equipment data
        $this->equipment->load([
            'currentHolder',
            'equipmentHistory.holder',
            'equipmentHistory.performedBy',
        ]);
    }

    public function canEditHistory($history): bool
    {
        return in_array($history->action_type, ['repaired']);
    }

    public function canDeleteHistory($history): bool
    {
        return ! in_array($history->action_type, ['purchased', 'assigned', 'retired']);
    }

    public function editNote($noteId)
    {
        $note = Note::find($noteId);

        if (! $note || $note->note_type !== 'equipment' || $note->entity_id !== $this->equipment->id) {
            return;
        }

        $this->editingNote = $note->id;
        $this->noteEditForm = [
            'id' => $note->id,
            'note_text' => $note->note_text,
        ];
    }

    public function cancelNoteEdit()
    {
        $this->editingNote = null;
        $this->noteEditForm = [
            'id' => null,
            'note_text' => '',
        ];
    }

    public function saveNoteEdit()
    {
        $this->validate([
            'noteEditForm.note_text' => 'required|string|max:1000',
        ]);

        $note = Note::find($this->noteEditForm['id']);

        if (! $note || $note->note_type !== 'equipment' || $note->entity_id !== $this->equipment->id) {
            return;
        }

        $note->update([
            'note_text' => $this->noteEditForm['note_text'],
        ]);

        $this->editingNote = null;
        $this->noteEditForm = [
            'id' => null,
            'note_text' => '',
        ];

        // Refresh notes
        $this->equipment->load('notes');
    }

    public function deleteNote($noteId)
    {
        $note = Note::find($noteId);

        if (! $note || $note->note_type !== 'equipment' || $note->entity_id !== $this->equipment->id) {
            return;
        }

        $note->delete();

        // Refresh notes
        $this->equipment->load('notes');
    }

    protected function rules(): array
    {
        return [
            'newNote' => 'required|string|max:1000',
            'retirementNotes' => 'required|string|max:1000',
            'historyEditForm.notes' => 'required|string|max:1000',
            'historyEditForm.action' => 'required|string|max:255',
            'noteEditForm.note_text' => 'required|string|max:1000',
            'editForm.brand' => 'required|string|max:255',
            'editForm.model' => 'required|string|max:255',
            'editForm.serial' => 'required|string|max:255',
            'editForm.purchase_date' => 'required|date',
            'editForm.purchase_price' => 'required|numeric|min:0',
            'editForm.current_holder_id' => 'nullable|exists:people,id',
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
