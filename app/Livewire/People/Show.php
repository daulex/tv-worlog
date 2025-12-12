<?php

namespace App\Livewire\People;

use App\Models\Checklist;
use App\Models\ChecklistInstance;
use App\Models\ChecklistItemInstance;
use App\Models\Note;
use App\Models\Person;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Person $person;

    public $newNote = '';

    public $showNoteForm = false;

    public $editingNote = null;

    public $noteEditForm = [
        'id' => null,
        'note_text' => '',
    ];

    public $showChecklistDropdown = false;

    public $selectedChecklistId = null;

    public function mount(Person $person)
    {
        $this->authorize('view', $person);

        $this->person = $person->load([
            'client',
            'vacancy',
            'files',
            'personHistory.performedBy',
            'notes',
            'events',
            'equipment',
            'checklistInstances.checklist',
        ]);
    }

    public function getTimeline()
    {
        // Use cached timeline data with proper eager loading
        $cacheKey = "person_timeline_{$this->person->id}";

        return Cache::remember($cacheKey, 300, function () {
            $freshPerson = Person::with([
                'personHistory' => function ($query) {
                    $query->orderBy('change_date', 'desc')->orderBy('id', 'desc')
                        ->select('id', 'person_id', 'change_date', 'action', 'action_type', 'notes', 'performed_by_id');
                },
                'notes' => function ($query) {
                    $query->orderBy('created_at', 'desc')->orderBy('id', 'desc')
                        ->select('id', 'note_type', 'entity_id', 'note_text', 'created_at');
                },
                'events' => function ($query) {
                    $query->orderBy('start_date', 'desc')->orderBy('id', 'desc');
                },
            ])->find($this->person->id);

            $timeline = collect();

            // Add person history items
            $freshPerson->personHistory->each(function ($history) use ($timeline) {
                $timeline->push([
                    'type' => 'history',
                    'date' => $history->change_date,
                    'id' => $history->id,
                    'data' => $history,
                ]);
            });

            // Add notes
            $freshPerson->notes->each(function ($note) use ($timeline) {
                $timeline->push([
                    'type' => 'note',
                    'date' => $note->created_at,
                    'id' => $note->id,
                    'data' => $note,
                ]);
            });

            // Add events
            $freshPerson->events->each(function ($event) use ($timeline) {
                $timeline->push([
                    'type' => 'event',
                    'date' => $event->start_date,
                    'id' => $event->id,
                    'data' => $event,
                ]);
            });

            // Add checklist instances
            $freshPerson->checklistInstances->each(function ($checklistInstance) use ($timeline) {
                $timeline->push([
                    'type' => 'checklist',
                    'date' => $checklistInstance->started_at,
                    'id' => $checklistInstance->id,
                    'data' => $checklistInstance,
                ]);
            });

            // Sort by date first, then by ID (newest first)
            return $timeline->sortByDesc(function ($item) {
                return $item['date']->timestamp.str_pad((string) $item['id'], 10, '0', STR_PAD_LEFT);
            })->values();
        });
    }

    public function addNote()
    {
        $this->authorize('manageNotes', $this->person);

        $this->validate([
            'newNote' => 'required|string|max:1000',
        ]);

        $this->person->notes()->create([
            'note_type' => 'person',
            'entity_id' => $this->person->id,
            'note_text' => $this->newNote,
        ]);

        $this->newNote = '';
        $this->showNoteForm = false;

        // Clear timeline cache
        $this->clearTimelineCache();
    }

    public function toggleNoteForm()
    {
        $this->showNoteForm = ! $this->showNoteForm;
        $this->newNote = '';
    }

    public function editNote($noteId)
    {
        $this->authorize('manageNotes', $this->person);

        $note = Note::find($noteId);

        if (! $note || $note->note_type !== 'person' || $note->entity_id !== $this->person->id) {
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
        $this->authorize('manageNotes', $this->person);

        $this->validate([
            'noteEditForm.note_text' => 'required|string|max:1000',
        ]);

        $note = Note::find($this->noteEditForm['id']);

        if (! $note || $note->note_type !== 'person' || $note->entity_id !== $this->person->id) {
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

        // Clear timeline cache
        $this->clearTimelineCache();
    }

    public function deleteNote($noteId)
    {
        $this->authorize('manageNotes', $this->person);

        $note = Note::find($noteId);

        if (! $note || $note->note_type !== 'person' || $note->entity_id !== $this->person->id) {
            return;
        }

        $note->delete();

        // Clear timeline cache
        $this->clearTimelineCache();
    }

    public function startChecklist()
    {
        $this->authorize('create', ChecklistInstance::class);

        $this->validate([
            'selectedChecklistId' => 'required|exists:checklists,id',
        ]);

        // Check if this checklist is already started for this person
        $existing = ChecklistInstance::where('person_id', $this->person->id)
            ->where('checklist_id', $this->selectedChecklistId)
            ->first();

        if ($existing) {
            session()->flash('error', 'This checklist has already been started for this person.');

            return;
        }

        $checklist = Checklist::find($this->selectedChecklistId);

        // Create checklist instance
        $instance = ChecklistInstance::create([
            'checklist_id' => $checklist->id,
            'person_id' => $this->person->id,
            'started_at' => now(),
        ]);

        // Create item instances
        foreach ($checklist->items as $item) {
            ChecklistItemInstance::create([
                'checklist_instance_id' => $instance->id,
                'checklist_item_id' => $item->id,
                'value' => null,
            ]);
        }

        $this->showChecklistDropdown = false;
        $this->selectedChecklistId = null;

        // Reload person with new checklist instances
        $this->person->load('checklistInstances.checklist');

        session()->flash('message', 'Checklist started successfully.');
    }

    public function toggleChecklistDropdown()
    {
        $this->showChecklistDropdown = ! $this->showChecklistDropdown;
        $this->selectedChecklistId = null;
    }

    protected function rules(): array
    {
        return [
            'newNote' => 'required|string|max:1000',
            'noteEditForm.note_text' => 'required|string|max:1000',
        ];
    }

    public function getFilesProperty()
    {
        return \App\Models\File::where('person_id', $this->person->id)
            ->orderBy('uploaded_at', 'desc')
            ->get();
    }

    private function clearTimelineCache(): void
    {
        Cache::forget("person_timeline_{$this->person->id}");
    }

    public function render()
    {
        return view('livewire.people.show');
    }
}
