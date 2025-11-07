<?php

namespace App\Livewire\People;

use App\Models\Note;
use App\Models\Person;
use Livewire\Component;

class Show extends Component
{
    public Person $person;

    public $newNote = '';

    public $showNoteForm = false;

    public $isEditing = false;

    public $editingNote = null;

    public $noteEditForm = [
        'id' => null,
        'note_text' => '',
    ];

    public $editForm = [
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'email2' => '',
        'phone' => '',
        'phone2' => '',
        'date_of_birth' => '',
        'address' => '',
        'position' => '',
        'status' => '',
        'client_id' => '',
        'vacancy_id' => '',
        'cv_id' => '',
        'starting_date' => '',
        'last_working_date' => '',
    ];

    public function mount(Person $person)
    {
        $this->person = $person->load([
            'client',
            'vacancy',
            'cv',
            'personHistory.performedBy',
            'notes',
            'events',
            'equipment',
        ]);

        $this->initializeEditForm();
    }

    public function getTimeline()
    {
        // Force fresh load from database to avoid caching issues
        $freshPerson = Person::with([
            'personHistory' => function ($query) {
                $query->orderBy('change_date', 'desc')->orderBy('id', 'desc');
            },
            'notes' => function ($query) {
                $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
            },
            'events' => function ($query) {
                $query->orderBy('start_date', 'desc')->orderBy('id', 'desc');
            },
        ])->find($this->person->id);

        $timeline = collect();

        // Add person history items
        foreach ($freshPerson->personHistory as $history) {
            $timeline->push([
                'type' => 'history',
                'date' => $history->change_date,
                'id' => $history->id,
                'data' => $history,
            ]);
        }

        // Add notes
        foreach ($freshPerson->notes as $note) {
            $timeline->push([
                'type' => 'note',
                'date' => $note->created_at,
                'id' => $note->id,
                'data' => $note,
            ]);
        }

        // Add events (joined events)
        foreach ($freshPerson->events as $event) {
            $timeline->push([
                'type' => 'event',
                'date' => $event->start_date,
                'id' => $event->id,
                'data' => $event,
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
            'first_name' => $this->person->first_name,
            'last_name' => $this->person->last_name,
            'email' => $this->person->email,
            'email2' => $this->person->email2,
            'phone' => $this->person->phone,
            'phone2' => $this->person->phone2,
            'date_of_birth' => $this->person->date_of_birth?->format('Y-m-d'),
            'starting_date' => $this->person->starting_date?->format('Y-m-d'),
            'last_working_date' => $this->person->last_working_date?->format('Y-m-d'),
            'address' => $this->person->address,
            'position' => $this->person->position,
            'status' => $this->person->status,
            'client_id' => $this->person->client_id ?? '',
            'vacancy_id' => $this->person->vacancy_id ?? '',
            'cv_id' => $this->person->cv_id ?? '',
        ];
    }

    public function addNote()
    {
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

        // Refresh person and notes
        $this->person->load('notes');
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

    public function savePerson()
    {
        $this->validate([
            'editForm.first_name' => 'required|string|max:255',
            'editForm.last_name' => 'required|string|max:255',
            'editForm.email' => 'nullable|email|max:255',
            'editForm.email2' => 'nullable|email|max:255',
            'editForm.phone' => 'nullable|string|max:255',
            'editForm.phone2' => 'nullable|string|max:255',
            'editForm.date_of_birth' => 'nullable|date',
            'editForm.starting_date' => 'nullable|date',
            'editForm.last_working_date' => 'nullable|date',
            'editForm.address' => 'nullable|string|max:1000',
            'editForm.position' => 'nullable|string|max:255',
            'editForm.status' => 'required|in:Candidate,Employee,Retired',
            'editForm.client_id' => 'nullable|exists:clients,id',
            'editForm.vacancy_id' => 'nullable|exists:vacancies,id',
            'editForm.cv_id' => 'nullable|exists:c_vs,id',
        ]);

        $this->person->update([
            'first_name' => $this->editForm['first_name'],
            'last_name' => $this->editForm['last_name'],
            'email' => $this->editForm['email'],
            'email2' => $this->editForm['email2'] ?: null,
            'phone' => $this->editForm['phone'],
            'phone2' => $this->editForm['phone2'] ?: null,
            'date_of_birth' => $this->editForm['date_of_birth'] ?: null,
            'starting_date' => $this->editForm['starting_date'] ?: null,
            'last_working_date' => $this->editForm['last_working_date'] ?: null,
            'address' => $this->editForm['address'],
            'position' => $this->editForm['position'],
            'status' => $this->editForm['status'],
            'client_id' => $this->editForm['client_id'] ?: null,
            'vacancy_id' => $this->editForm['vacancy_id'] ?: null,
            'cv_id' => $this->editForm['cv_id'] ?: null,
        ]);

        // Refresh person data
        $this->person->load([
            'client',
            'vacancy',
            'cv',
            'personHistory.performedBy',
        ]);

        $this->isEditing = false;
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->initializeEditForm();
    }

    public function editNote($noteId)
    {
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

        // Refresh notes
        $this->person->load('notes');
    }

    public function deleteNote($noteId)
    {
        $note = Note::find($noteId);

        if (! $note || $note->note_type !== 'person' || $note->entity_id !== $this->person->id) {
            return;
        }

        $note->delete();

        // Refresh notes
        $this->person->load('notes');
    }

    protected function rules(): array
    {
        return [
            'newNote' => 'required|string|max:1000',
            'noteEditForm.note_text' => 'required|string|max:1000',
            'editForm.first_name' => 'required|string|max:255',
            'editForm.last_name' => 'required|string|max:255',
            'editForm.email' => 'nullable|email|max:255',
            'editForm.email2' => 'nullable|email|max:255',
            'editForm.phone' => 'nullable|string|max:255',
            'editForm.phone2' => 'nullable|string|max:255',
            'editForm.date_of_birth' => 'nullable|date',
            'editForm.starting_date' => 'nullable|date',
            'editForm.last_working_date' => 'nullable|date',
            'editForm.address' => 'nullable|string|max:1000',
            'editForm.position' => 'nullable|string|max:255',
            'editForm.status' => 'required|in:Candidate,Employee,Retired',
            'editForm.client_id' => 'nullable|exists:clients,id',
            'editForm.vacancy_id' => 'nullable|exists:vacancies,id',
            'editForm.cv_id' => 'nullable|exists:c_vs,id',
        ];
    }

    public function getClientsProperty()
    {
        return \App\Models\Client::orderBy('name')->get();
    }

    public function getVacanciesProperty()
    {
        return \App\Models\Vacancy::with('client')->orderBy('title')->get();
    }

    public function getCvsProperty()
    {
        return \App\Models\CV::with('person')->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.people.show');
    }
}
