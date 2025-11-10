<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Client $client;

    public $newNote = '';

    public $showNoteForm = false;

    public $editingNote = null;

    public $noteEditForm = [
        'id' => null,
        'note_text' => '',
    ];

    public function mount(Client $client)
    {
        $this->authorize('view', $client);

        $this->client = $client->load([
            'vacancies',
            'notes',
        ]);
    }

    public function addNote()
    {
        $this->validate([
            'newNote' => 'required|string|max:1000',
        ]);

        $this->client->notes()->create([
            'note_type' => 'client',
            'entity_id' => $this->client->id,
            'note_text' => $this->newNote,
            'created_by' => auth()->id(),
        ]);

        $this->newNote = '';
        $this->showNoteForm = false;
        $this->client->load('notes');
    }

    public function editNote($noteId)
    {
        $note = $this->client->notes()->findOrFail($noteId);
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

        $note = $this->client->notes()->findOrFail($this->noteEditForm['id']);
        $note->update([
            'note_text' => $this->noteEditForm['note_text'],
        ]);

        $this->cancelEditNote();
        $this->client->load('notes');
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
        $note = $this->client->notes()->findOrFail($noteId);
        $note->delete();
        $this->client->load('notes');
    }

    public function getVacanciesProperty()
    {
        return Cache::remember('client_vacancies_'.$this->client->id, 3600, function () {
            return $this->client->vacancies()->with('people')->get();
        });
    }

    public function render()
    {
        return view('livewire.clients.show', [
            'client' => $this->client,
            'vacancies' => $this->vacancies,
        ]);
    }
}
