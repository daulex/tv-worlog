<?php

namespace App\Livewire\Notes;

use App\Models\Note;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public $search = '';

    public function delete(Note $note)
    {
        $this->authorize('delete', $note);

        $note->delete();
        session()->flash('message', 'Note deleted successfully.');
    }

    public function render()
    {
        $this->authorize('viewAny', Note::class);

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
