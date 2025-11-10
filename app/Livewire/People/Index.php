<?php

namespace App\Livewire\People;

use App\Models\Client;
use App\Models\Person;
use App\Models\Vacancy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public $search = '';

    public $statusFilter = '';

    public $clientFilter = '';

    public $vacancyFilter = '';

    protected $paginationTheme = 'tailwind';

    public function delete(Person $person)
    {
        $this->authorize('delete', $person);

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
        return Cache::remember('clients_for_select', 3600, function () {
            return Client::orderBy('name')->get(['id', 'name']);
        });
    }

    public function getVacanciesProperty()
    {
        return Cache::remember('vacancies_for_select', 3600, function () {
            return Vacancy::with('client:id,name')->orderBy('title')->get(['id', 'title', 'client_id']);
        });
    }

    public function render()
    {
        $people = Person::query()
            ->when($this->search, function ($query) {
                $searchTerm = '%'.$this->search.'%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('first_name', 'like', $searchTerm)
                        ->orWhere('last_name', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm);
                });
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
            ->with(['client:id,name', 'vacancy:id,title,client_id'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('livewire.people.index', [
            'people' => $people,
            'clients' => $this->clients,
            'vacancies' => $this->vacancies,
        ]);
    }
}
