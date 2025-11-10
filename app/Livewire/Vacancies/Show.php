<?php

namespace App\Livewire\Vacancies;

use App\Models\Person;
use App\Models\Vacancy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Vacancy $vacancy;

    public $newNote = '';

    public $showNoteForm = false;

    public $editingNote = null;

    public $noteEditForm = [
        'id' => null,
        'note_text' => '',
    ];

    public function mount(Vacancy $vacancy)
    {
        $this->authorize('view', $vacancy);

        $this->vacancy = $vacancy->load([
            'client',
            'people',
            'notes',
        ]);
    }

    public function addNote()
    {
        $this->validate([
            'newNote' => 'required|string|max:1000',
        ]);

        $this->vacancy->notes()->create([
            'note_type' => 'vacancy',
            'entity_id' => $this->vacancy->id,
            'note_text' => $this->newNote,
            'created_by' => auth()->id(),
        ]);

        $this->newNote = '';
        $this->showNoteForm = false;
        $this->vacancy->load('notes');
    }

    public function editNote($noteId)
    {
        $note = $this->vacancy->notes()->findOrFail($noteId);
        $this->editingNote = $note;
        $this->noteEditForm = [
            'id' => $note->id,
            'note_text' => $note->note_text,
        ];
    }

    public function updateNote()
    {
        $this->validate([
            'noteEditForm.note_text' => 'required|string|max:1000',
        ]);

        $note = $this->vacancy->notes()->findOrFail($this->noteEditForm['id']);
        $note->update([
            'note_text' => $this->noteEditForm['note_text'],
        ]);

        $this->cancelEditNote();
        $this->vacancy->load('notes');
    }

    public function cancelEditNote()
    {
        $this->editingNote = null;
        $this->noteEditForm = [
            'id' => null,
            'note_text' => '',
        ];
    }

    public function deleteNote($noteId)
    {
        $note = $this->vacancy->notes()->findOrFail($noteId);
        $note->delete();
        $this->vacancy->load('notes');
    }

    public function assignPerson($personId)
    {
        $person = Person::findOrFail($personId);

        // Check if person is already assigned to this vacancy
        if (! $this->vacancy->people()->where('person_id', $personId)->exists()) {
            $this->vacancy->people()->attach($personId);
            $this->vacancy->load('people');
        }
    }

    public function removePerson($personId)
    {
        $this->vacancy->people()->detach($personId);
        $this->vacancy->load('people');
    }

    public function getAvailablePeopleProperty()
    {
        return Cache::remember('available_people_for_vacancy', 3600, function () {
            return Person::whereNotIn('id', $this->vacancy->people->pluck('id'))
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get(['id', 'first_name', 'last_name']);
        });
    }

    public function render()
    {
        return view('livewire.vacancies.show', [
            'vacancy' => $this->vacancy,
            'availablePeople' => $this->availablePeople,
        ]);
    }
}
