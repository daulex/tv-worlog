<?php

namespace App\Livewire\People;

use App\Models\Person;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $paginationTheme = 'tailwind';

    public function delete(Person $person)
    {
        $person->delete();
        session()->flash('message', 'Person deleted successfully.');
    }

    public function render()
    {
        $people = Person::query()
            ->when($this->search, function ($query) {
                $query->where('first_name', 'like', '%'.$this->search.'%')
                    ->orWhere('last_name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->with(['client', 'vacancy'])
            ->latest()
            ->paginate(50);

        return view('livewire.people.index', [
            'people' => $people,
        ]);
    }
}
