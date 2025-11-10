<?php

namespace App\Livewire\Notes;

use App\Models\Note;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Note $note;

    public function mount(Note $note)
    {
        $this->authorize('view', $note);

        $this->note = $note->load([
            'creator',
        ]);
    }

    public function render()
    {
        return view('livewire.notes.show', [
            'note' => $this->note,
        ]);
    }
}
