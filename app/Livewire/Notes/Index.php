<?php

namespace App\Livewire\Notes;

use App\Models\Note;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete(Note $note)
    {
        $note->delete();
        session()->flash('message', 'Note deleted successfully.');
    }

    public function render()
    {
        $notes = Note::with('noteable')
            ->where('note_text', 'like', '%'.$this->search.'%')
            ->orWhere('note_type', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('livewire.notes.index', [
            'notes' => $notes,
        ]);
    }
}
