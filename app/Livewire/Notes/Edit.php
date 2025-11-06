<?php

namespace App\Livewire\Notes;

use App\Models\Client;
use App\Models\Equipment;
use App\Models\Note;
use App\Models\Person;
use App\Models\Vacancy;
use Livewire\Component;

class Edit extends Component
{
    public Note $note;

    public $note_text;

    public $note_type;

    public $entity_id;

    public $people;

    public $clients;

    public $vacancies;

    public $equipment;

    protected function rules(): array
    {
        return [
            'note_text' => 'required|string|max:65535',
            'note_type' => 'required|in:person,client,vacancy,equipment',
            'entity_id' => 'required|integer|exists:people,id|exists:clients,id|exists:vacancies,id|exists:equipment,id',
        ];
    }

    public function mount(Note $note)
    {
        $this->note = $note;
        $this->note_text = $note->note_text;
        $this->note_type = $note->note_type;
        $this->entity_id = $note->entity_id;

        $this->people = Person::orderBy('name')->get();
        $this->clients = Client::orderBy('name')->get();
        $this->vacancies = Vacancy::orderBy('title')->get();
        $this->equipment = Equipment::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();

        $this->note->update([
            'note_text' => $this->note_text,
            'note_type' => $this->note_type,
            'entity_id' => $this->entity_id,
        ]);

        session()->flash('message', 'Note updated successfully.');

        return redirect()->route('notes.index');
    }

    public function render()
    {
        return view('livewire.notes.edit');
    }
}
