<?php

namespace App\Livewire\CVs;

use App\Models\CV;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public $search = '';

    public function delete(CV $cv)
    {
        $this->authorize('delete', $cv);

        $cv->delete();
        session()->flash('message', 'CV deleted successfully.');
    }

    public function render()
    {
        $this->authorize('viewAny', CV::class);

        $cvs = CV::with('person')
            ->where('file_path_or_url', 'like', '%'.$this->search.'%')
            ->orWhereHas('person', function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('uploaded_at', 'desc')
            ->paginate(50);

        return view('livewire.c-vs.index', [
            'cvs' => $cvs,
        ]);
    }
}
