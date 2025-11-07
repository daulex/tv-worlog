<?php

namespace App\Livewire\People;

use App\Models\Client;
use App\Models\Person;
use App\Models\Vacancy;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = '';

    public $clientFilter = '';

    public $vacancyFilter = '';

    protected $paginationTheme = 'tailwind';

    public function delete(Person $person)
    {
        $person->delete();
        session()->flash('message', 'Person deleted successfully.');
    }

    public function clearFilters()
    {
        $this->statusFilter = '';
        $this->clientFilter = '';
        $this->vacancyFilter = '';
        $this->search = '';
    }

    public function getClientsProperty()
    {
        return Client::orderBy('name')->get();
    }

    public function getVacanciesProperty()
    {
        return Vacancy::with('client')->orderBy('title')->get();
    }

    public function render()
    {
        $people = Person::query()
            ->when($this->search, function ($query) {
                $query->where('first_name', 'like', '%'.$this->search.'%')
                    ->orWhere('last_name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->clientFilter, function ($query) {
                $query->where('client_id', $this->clientFilter);
            })
            ->when($this->vacancyFilter, function ($query) {
                $query->where('vacancy_id', $this->vacancyFilter);
            })
            ->with(['client', 'vacancy'])
            ->latest()
            ->paginate(50);

        return view('livewire.people.index', [
            'people' => $people,
            'clients' => $this->clients,
            'vacancies' => $this->vacancies,
        ]);
    }
}
