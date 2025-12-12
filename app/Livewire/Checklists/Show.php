<?php

namespace App\Livewire\Checklists;

use App\Models\Checklist;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Checklist $checklist;

    public function mount(Checklist $checklist)
    {
        $this->authorize('view', $checklist);

        $this->checklist = $checklist->load('items');
    }

    public function delete()
    {
        $this->authorize('delete', $this->checklist);

        $this->checklist->delete();

        session()->flash('message', 'Checklist deleted successfully.');

        return redirect()->route('checklists.index');
    }

    public function render()
    {
        return view('livewire.checklists.show');
    }
}
