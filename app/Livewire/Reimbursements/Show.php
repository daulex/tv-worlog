<?php

namespace App\Livewire\Reimbursements;

use App\Models\Note;
use App\Models\Reimbursement;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Show extends Component
{
    public Reimbursement $reimbursement;

    public $newNote = '';

    public $showNoteForm = false;

    public $editingNote = null;

    public $noteEditForm = [
        'id' => null,
        'note_text' => '',
    ];

    public function mount(Reimbursement $reimbursement)
    {
        $this->reimbursement = $reimbursement->load([
            'client',
            'history.performedBy',
        ]);
    }

    public function getTimeline()
    {
        $cacheKey = "reimbursement_timeline_{$this->reimbursement->id}";

        return Cache::remember($cacheKey, 300, function () {
            $freshReimbursement = Reimbursement::with([
                'history' => function ($query) {
                    $query->orderBy('change_date', 'desc')->orderBy('id', 'desc');
                },
            ])->find($this->reimbursement->id);

            $timeline = collect();

            // Add reimbursement history items
            $freshReimbursement->history->each(function ($history) use ($timeline) {
                $timeline->push([
                    'type' => 'history',
                    'date' => $history->change_date,
                    'id' => $history->id,
                    'data' => $history,
                ]);
            });

            // Add notes using query approach to avoid relationship caching issue
            $notes = Note::where('note_type', 'reimbursement')
                ->where('entity_id', $this->reimbursement->id)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->get();

            $notes->each(function ($note) use ($timeline) {
                $timeline->push([
                    'type' => 'note',
                    'date' => $note->created_at,
                    'id' => $note->id,
                    'data' => $note,
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
        $this->validate([
            'newNote' => 'required|string|max:1000',
        ]);

        $this->reimbursement->notes()->create([
            'note_type' => 'reimbursement',
            'entity_id' => $this->reimbursement->id,
            'note_text' => $this->newNote,
        ]);

        $this->newNote = '';
        $this->showNoteForm = false;

        // Clear timeline cache
        $this->clearTimelineCache();

        session()->flash('message', 'Note added successfully.');
    }

    public function toggleNoteForm()
    {
        $this->showNoteForm = ! $this->showNoteForm;
        $this->newNote = '';
    }

    public function editNote($noteId)
    {
        $note = Note::find($noteId);

        if (! $note || $note->note_type !== 'reimbursement' || $note->entity_id !== $this->reimbursement->id) {
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

        if (! $note || $note->note_type !== 'reimbursement' || $note->entity_id !== $this->reimbursement->id) {
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

        session()->flash('message', 'Note updated successfully.');
    }

    public function deleteNote($noteId)
    {
        $note = Note::find($noteId);

        if (! $note || $note->note_type !== 'reimbursement' || $note->entity_id !== $this->reimbursement->id) {
            return;
        }

        $note->delete();

        // Clear timeline cache
        $this->clearTimelineCache();

        session()->flash('message', 'Note deleted successfully.');
    }

    private function clearTimelineCache(): void
    {
        Cache::forget("reimbursement_timeline_{$this->reimbursement->id}");
    }

    public function render()
    {
        return view('livewire.reimbursements.show');
    }
}
