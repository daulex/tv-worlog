<?php

namespace App\Livewire\Events;

use App\Models\Event;
use App\Models\Person;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Event $event;

    public $newNote = '';

    public $showNoteForm = false;

    public $editingNote = null;

    public $noteEditForm = [
        'id' => null,
        'note_text' => '',
    ];

    public function mount(Event $event)
    {
        $this->authorize('view', $event);

        $this->event = $event->load([
            'participants.person',
            'notes',
        ]);
    }

    public function addNote()
    {
        $this->validate([
            'newNote' => 'required|string|max:1000',
        ]);

        $this->event->notes()->create([
            'note_type' => 'event',
            'entity_id' => $this->event->id,
            'note_text' => $this->newNote,
            'created_by' => auth()->id(),
        ]);

        $this->newNote = '';
        $this->showNoteForm = false;
        $this->event->load('notes');
    }

    public function editNote($noteId)
    {
        $note = $this->event->notes()->findOrFail($noteId);
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

        $note = $this->event->notes()->findOrFail($this->noteEditForm['id']);
        $note->update([
            'note_text' => $this->noteEditForm['note_text'],
        ]);

        $this->cancelEditNote();
        $this->event->load('notes');
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
        $note = $this->event->notes()->findOrFail($noteId);
        $note->delete();
        $this->event->load('notes');
    }

    public function addParticipant()
    {
        $this->validate([
            'newParticipantId' => 'required|exists:people,id',
        ]);

        $this->event->participants()->create([
            'person_id' => $this->newParticipantId,
            'joined_at' => now(),
        ]);

        $this->newParticipantId = '';
        $this->event->load('participants.person');
    }

    public function removeParticipant($participantId)
    {
        $participant = $this->event->participants()->findOrFail($participantId);
        $participant->delete();
        $this->event->load('participants.person');
    }

    public function getAvailablePeopleProperty()
    {
        return Cache::remember('available_people_for_event', 3600, function () {
            return Person::whereNotIn('id', $this->event->participants->pluck('person_id'))
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get(['id', 'first_name', 'last_name']);
        });
    }

    public function render()
    {
        return view('livewire.events.show', [
            'event' => $this->event,
            'availablePeople' => $this->availablePeople,
        ]);
    }
}
