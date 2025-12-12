<?php

namespace App\Livewire\Checklists;

use App\Models\Checklist;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

    public $search = '';

    public function mount()
    {
        $this->authorize('viewAny', Checklist::class);
    }

    public function delete($checklistId)
    {
        $checklist = Checklist::find($checklistId);
        $this->authorize('delete', $checklist);

        $checklist->delete();

        session()->flash('message', 'Checklist deleted successfully.');
    }

    public function render()
    {
        $checklists = Checklist::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->withCount('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.checklists.index', [
            'checklists' => $checklists,
        ]);
    }
}
