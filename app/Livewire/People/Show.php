<?php

namespace App\Livewire\People;

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

    public $isEditing = false;

    public $editingNote = null;

    public $noteEditForm = [
        'id' => null,
        'note_text' => '',
    ];

    public $editForm = [
        'first_name' => '',
        'last_name' => '',
        'pers_code' => '',
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

        'linkedin_profile' => '',
        'github_profile' => '',
        'portfolio_url' => '',
        'emergency_contact_name' => '',
        'emergency_contact_relationship' => '',
        'emergency_contact_phone' => '',
        'starting_date' => '',
        'last_working_date' => '',
    ];

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
        ]);

        $this->initializeEditForm();
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

            // Sort by date first, then by ID (newest first)
            return $timeline->sortByDesc(function ($item) {
                return $item['date']->timestamp.str_pad((string) $item['id'], 10, '0', STR_PAD_LEFT);
            })->values();
        });
    }

    private function initializeEditForm()
    {
        $this->editForm = [
            'first_name' => $this->person->first_name,
            'last_name' => $this->person->last_name,
            'pers_code' => $this->person->pers_code,
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

            'linkedin_profile' => $this->person->linkedin_profile,
            'github_profile' => $this->person->github_profile,
            'portfolio_url' => $this->person->portfolio_url,
            'emergency_contact_name' => $this->person->emergency_contact_name,
            'emergency_contact_relationship' => $this->person->emergency_contact_relationship,
            'emergency_contact_phone' => $this->person->emergency_contact_phone,
        ];
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

    public function toggleEditMode()
    {
        $this->isEditing = ! $this->isEditing;

        if ($this->isEditing) {
            $this->initializeEditForm();
        }
    }

    public function savePerson()
    {
        $this->authorize('update', $this->person);

        $this->validate([
            'editForm.first_name' => 'required|string|max:255',
            'editForm.last_name' => 'required|string|max:255',
            'editForm.pers_code' => 'required|string|unique:people,pers_code,'.$this->person->id,
            'editForm.phone' => 'nullable|string|max:20',
            'editForm.phone2' => 'nullable|string|max:20',
            'editForm.email' => 'required|email:rfc,spoof|unique:people,email,'.$this->person->id,
            'editForm.email2' => 'nullable|email:rfc,spoof|unique:people,email2,'.$this->person->id,
            'editForm.date_of_birth' => 'required|date|before:today',
            'editForm.address' => 'nullable|string|max:1000',
            'editForm.starting_date' => 'nullable|date|before_or_equal:today',
            'editForm.last_working_date' => 'nullable|date|before_or_equal:today',
            'editForm.position' => 'nullable|string|max:255',
            'editForm.status' => 'required|in:Candidate,Employee,Retired',
            'editForm.client_id' => 'nullable|exists:clients,id',
            'editForm.vacancy_id' => 'nullable|exists:vacancies,id',
        ]);

        $this->person->update([
            'first_name' => $this->editForm['first_name'],
            'last_name' => $this->editForm['last_name'],
            'pers_code' => $this->editForm['pers_code'],
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
            'linkedin_profile' => $this->editForm['linkedin_profile'],
            'github_profile' => $this->editForm['github_profile'],
            'portfolio_url' => $this->editForm['portfolio_url'],
            'emergency_contact_name' => $this->editForm['emergency_contact_name'],
            'emergency_contact_relationship' => $this->editForm['emergency_contact_relationship'],
            'emergency_contact_phone' => $this->editForm['emergency_contact_phone'],
        ]);

        // Refresh person data
        $this->person->load([
            'client',
            'vacancy',
            'files',
            'personHistory.performedBy',
        ]);

        // Clear timeline cache
        $this->clearTimelineCache();

        $this->isEditing = false;
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->initializeEditForm();
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

    protected function rules(): array
    {
        return [
            'newNote' => 'required|string|max:1000',
            'noteEditForm.note_text' => 'required|string|max:1000',
            'editForm.first_name' => 'required|string|max:255',
            'editForm.last_name' => 'required|string|max:255',
            'editForm.email' => 'nullable|email:rfc,spoof|max:255',
            'editForm.email2' => 'nullable|email:rfc,spoof|max:255',
            'editForm.phone' => ['nullable', 'string', 'max:255', new LatvianPhoneNumber],
            'editForm.phone2' => ['nullable', 'string', 'max:255', new LatvianPhoneNumber],
            'editForm.date_of_birth' => 'nullable|date|before:today',
            'editForm.starting_date' => 'nullable|date|before_or_equal:today',
            'editForm.last_working_date' => 'nullable|date|before_or_equal:today',
            'editForm.address' => 'nullable|string|max:1000',
            'editForm.position' => 'nullable|string|max:255',
            'editForm.status' => 'required|in:Candidate,Employee,Retired',
            'editForm.client_id' => 'nullable|exists:clients,id',
            'editForm.vacancy_id' => 'nullable|exists:vacancies,id',
            'editForm.linkedin_profile' => 'nullable|url|max:500',
            'editForm.github_profile' => 'nullable|url|max:500',
            'editForm.portfolio_url' => 'nullable|url|max:500',
            'editForm.emergency_contact_name' => 'nullable|string|max:255',
            'editForm.emergency_contact_relationship' => 'nullable|string|max:255',
            'editForm.emergency_contact_phone' => ['nullable', 'string', 'max:255', new LatvianPhoneNumber],
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
