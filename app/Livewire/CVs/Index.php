<?php

namespace App\Livewire\CVs;

use App\Models\CV;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete(CV $cv)
    {
        $cv->delete();
        session()->flash('message', 'CV deleted successfully.');
    }

    public function render()
    {
        $cvs = CV::with('person')
            ->where('file_path_or_url', 'like', '%'.$this->search.'%')
            ->orWhereHas('person', function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('uploaded_at', 'desc')
            ->paginate(10);

        return view('livewire.c-vs.index', [
            'cvs' => $cvs,
        ]);
    }
}
